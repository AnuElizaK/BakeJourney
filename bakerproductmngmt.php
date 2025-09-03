<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'baker') {
    header("Location: index.php"); // Redirect to login if not authorized
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the actual baker_id first
$checkBaker = $conn->prepare("SELECT baker_id FROM bakers WHERE user_id = ?");
$checkBaker->bind_param("i", $user_id);
$checkBaker->execute();
$result = $checkBaker->get_result();

if ($result->num_rows > 0) {
    $baker = $result->fetch_assoc();
    $baker_id = $baker['baker_id'];

    // Now fetch products with correct baker_id
    $stmt = $conn->prepare("SELECT * FROM products WHERE baker_id = ?");
    $stmt->bind_param("i", $baker_id);
    $stmt->execute();
    $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $product_ids = array_column($products, 'product_id');

    $all_reviews = [];  // array to store reviews for each product
    $all_like_counts = []; // Array to store like counts for each product

    foreach ($product_ids as $pid) {
        $reviewstmt = $conn->prepare(
            "SELECT r.*, u.*
    FROM reviews r
    JOIN users u ON r.customer_id = u.user_id
    WHERE r.product_id = ?
    ORDER BY r.review_date DESC"
        );
        $reviewstmt->bind_param("i", $pid);
        $reviewstmt->execute();
        $reviews = $reviewstmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $all_reviews[$pid] = $reviews; // store reviews under that product_id

        // Fetch like count for the product
        $likestmt = $conn->prepare("SELECT COUNT(*) as like_count FROM product_likes WHERE product_id = ?");
        $likestmt->bind_param("i", $pid);
        $likestmt->execute();
        $like_result = $likestmt->get_result()->fetch_assoc();
        $all_like_counts[$pid] = $like_result['like_count'];

        // check if this user has already liked this product
        $user_like_stmt = $conn->prepare("SELECT COUNT(*) as user_liked FROM product_likes WHERE product_id = ? AND customer_id = ?");
        $user_like_stmt->bind_param("ii", $pid, $user_id);
        $user_like_stmt->execute();
        $user_like_result = $user_like_stmt->get_result()->fetch_assoc();
        $all_user_likes[$pid] = $user_like_result['user_liked'] > 0;
    }

} else {
    echo "Baker not found.";
}

// Handle like/unlike action
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] === 'toggle_like') {
    header('Content-Type: application/json');

    $product_id = intval($_POST['product_id']);

    try {
        // Check if user already liked this product
        $check_stmt = $conn->prepare("SELECT like_id FROM product_likes WHERE product_id = ? AND customer_id = ?");
        $check_stmt->bind_param("ii", $product_id, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Unlike - remove the like
            $delete_stmt = $conn->prepare("DELETE FROM product_likes WHERE product_id = ? AND customer_id = ?");
            $delete_stmt->bind_param("ii", $product_id, $user_id);
            $delete_stmt->execute();
            $liked = false;
        } else {
            // Like - add the like
            $insert_stmt = $conn->prepare("INSERT INTO product_likes (product_id, customer_id) VALUES (?, ?)");
            $insert_stmt->bind_param("ii", $product_id, $user_id);
            $insert_stmt->execute();
            $liked = true;
        }

        // Get updated like count
        $count_stmt = $conn->prepare("SELECT COUNT(*) as like_count FROM product_likes WHERE product_id = ?");
        $count_stmt->bind_param("i", $product_id);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $like_count = $count_result->fetch_assoc()['like_count'];

        echo json_encode([
            'success' => true,
            'liked' => $liked,
            'like_count' => $like_count
        ]);
        exit();

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Database error']);
        exit();
    }
}


// --- Product Image Remove (AJAX, for cancel/remove only) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_product_image']) && !isset($_POST['save_changes'])) {
    if (isset($_FILES['product_image_cropped'])) {
        $imgPath = $_FILES['product_image_cropped']['tmp_name'];
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }
    }
    echo '<script>alert("✅ Product image removed."); window.location.href = "bakerproductmngmt.php";</script>';
    exit;
}

// Function to format relative time
function timeAgo($datetime)
{
    $time = time() - strtotime($datetime);

    if ($time < 60)
        return 'just now';
    if ($time < 3600)
        return floor($time / 60) . 'm';
    if ($time < 86400)
        return floor($time / 3600) . 'h';
    if ($time < 2592000)
        return floor($time / 86400) . 'd';
    if ($time < 31104000)
        return floor($time / 2592000) . 'm';
    return floor($time / 31104000) . 'y';
}

//delete a review by a user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_comment'])) {
    $review_id = intval($_POST['review_id']);
    $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ? ");
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    header("Location: bakerproductmngmt.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management | BakeJourney</title>
    <link rel="stylesheet" href="bakerproductmngmt.css">
    <!-- Cropper.js CSS for product image upload -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="image-cropper.css">
    <link rel="stylesheet" href="edit-cropper.css">
</head>

<?php include 'bakernavbar.php'; ?>

<body>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Filter Tabs -->
        <div class="add-product-section">
            <div class="add-container">
                <div class="add-action">
                    <button onclick="toggleUploadModal()" class="btn">
                        + Add product
                    </button> Got something new cooking? Add it here!
                </div>
            </div>
        </div>
        <div class="filter-section">
            <div class="search-box">
                <input type="search" placeholder="Search or filter products..." class="search-input">
            </div>
            <div class="filter-tabs">
                <button onclick="filterProducts('all')" class="filter-btn active">All Products</button>
                <button onclick="filterProducts('breads')" class="filter-btn">Breads</button>
                <button onclick="filterProducts('cakes')" class="filter-btn">Cakes</button>
                <button onclick="filterProducts('brownies')" class="filter-btn">Brownies</button>
                <button onclick="filterProducts('pastries')" class="filter-btn">Pastries</button>
                <button onclick="filterProducts('cookies')" class="filter-btn">Cookies</button>
                <button onclick="filterProducts('crackers')" class="filter-btn">Crackers</button>
                <button onclick="filterProducts('candy')" class="filter-btn">Candy</button>
                <button onclick="filterProducts('pudding')" class="filter-btn">Pudding</button>
                <button onclick="filterProducts('piestarts')" class="filter-btn">Pies & Tarts</button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card" data-category="<?php echo htmlspecialchars($product['category']); ?>">
                    <div class="product-image-container">
                        <img src="<?php echo htmlspecialchars($product['image'] ? 'uploads/' . $product['image'] : 'https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png'); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                        <div class="product-actions">

                            <button class="action-btn edit-btn" onclick="editProduct(this)"
                                data-id="<?= $product['product_id']; ?>"
                                data-name="<?= htmlspecialchars($product['name']); ?>"
                                data-category="<?= $product['category']; ?>" data-price="<?= $product['price']; ?>"
                                data-weight="<?= $product['weight']; ?>"
                                data-description="<?= htmlspecialchars($product['description']); ?>">

                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            <form method="POST" style="display:inline;"
                                onsubmit="return confirm('Are you sure you want to delete this product?');">
                                <input type="hidden" name="delete_product_id" value="<?= $product['product_id']; ?>">
                                <button type="submit" name="delete_product" class="action-btn delete-btn">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>

                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-header">
                            <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <span class="product-price">₹<?php echo number_format($product['price'], 2); ?></span>
                        </div>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>


                        <!-- like button -->
                        <div class="engagement-actions">
                            <div class="social-actions">
                                <?php $is_liked = $all_user_likes[$product['product_id']] ?? false; ?>
                                <button class="social-btn like-btn <?= $is_liked ? 'liked' : '' ?>"
                                    data-product-id="<?= $product['product_id'] ?>">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span class="like-count"><?= $all_like_counts[$product['product_id']] ?></span>
                                </button>

                                <!-- comment section -->
                                <button onclick="togglecomment(<?php echo $product['product_id']; ?>)"
                                    class="social-btn comment-btn">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <span><?php echo count($all_reviews[$product['product_id']] ?? []); ?></span>
                                </button>
                            </div>
                        </div>
                        <div id="comment-<?php echo $product['product_id']; ?>" class="comment-section hidden">
                            <?php $reviews = $all_reviews[$product['product_id']] ?? []; ?>

                            <div class="comment-list">
                                <?php if (!empty($reviews)): ?>
                                    <?php foreach ($reviews as $review): ?>
                                        <div class="comment">

                                            <form method="post" style="margin-top: 12px;">
                                                <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                                                <button type="submit" name="delete_comment" value="delete_comment"
                                                    class="delete-btn-modern"
                                                    onclick="return confirm('Are you sure you want to delete this comment?');">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <polyline points="3,6 5,6 21,6"></polyline>
                                                        <path
                                                            d="M19,6V20a2,2 0 0,1 -2,2H7a2,2 0,0,1 -2,-2V6M8,6V4a2,2 0,0,1 2,-2h4a2,2 0,0,1 2,2V6">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                </button>
                                            </form>
                                            <span
                                                class="comment-author"><?php echo htmlspecialchars($review['full_name']); ?></span>
                                            <small class="comment-date"><?php echo timeAgo($review['review_date']); ?></small>
                                            <br />
                                            <span class="comment-text"><?php echo htmlspecialchars($review['comments']); ?></span>
                                            <span
                                                class="stars"><?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?></span>


                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="no-comments">No comments yet for this product!</p>
                                <?php endif; ?>

                            </div>
                            <div class="comment-form">
                                <!-- <form method ="POST"> -->
                                <input type="text" name="comment" placeholder="Add a comment..." class="comment-input">
                                <button class="comment-submit" type="submit" name="post_comment">Post</button>
                                <!-- </form> -->
                            </div>
                        </div>


                    </div>
                </div>
            <?php endforeach; ?>


        </div>
        <div id="no-results-message"
            style="display:none; text-align:center; color:#f59e0b; font-weight:600; margin:32px 0;">
            No products found.
        </div>
    </main>

    <!-- Upload Modal -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add New Product</h2>
                <button onclick="toggleUploadModal()" class="close-btn">&times;</button>
            </div>

            <form class="modal-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" class="form-input" name="name" placeholder="Enter product name" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select class="form-select" name="category" required>
                        <option value="">Select category</option>
                        <option value="breads">Breads</option>
                        <option value="cakes">Cakes</option>
                        <option value="pastries">Pastries</option>
                        <option value="cookies">Cookies</option>
                        <option value="brownies">Brownies</option>
                        <option value="crackers">Crackers</option>
                        <option value="candy">Candy</option>
                        <option value="pudding">Pudding</option>
                        <option value="piestarts">Pies & Tarts</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Price</label>
                    <div class="price-input-container">
                        <span class="price-symbol">₹</span>
                        <input type="number" name="price" step="0.01" class="form-input price-input" placeholder="0.00"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Weight (in kg)</label>
                    <input type="number" name="weight" step="0.01" class="form-input" placeholder="0.00" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-textarea" name="description" required
                        placeholder="Describe your product..."></textarea>
                </div>

                <!-- Image upload field handled by image-cropper.js -->
                <?php if (isset($_SESSION['uploaded_product_image'])): ?>
                    <div style="margin:10px 0; color:green;">Image uploaded:
                        <?php echo htmlspecialchars($_SESSION['uploaded_product_image']); ?>
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="button" onclick="toggleUploadModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary" name="add_product">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Product</h2>
                <button onclick="closeEditModal()" class="close-btn">&times;</button>
            </div>

            <form class="modal-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editProductId" name="product_id">

                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" id="editProductName" name="name" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select id="editProductCategory" name="category" class="form-select" required>
                        <option value="breads">Breads</option>
                        <option value="cakes">Cakes</option>
                        <option value="pastries">Pastries</option>
                        <option value="cookies">Cookies</option>
                        <option value="brownies">Brownies</option>
                        <option value="crackers">Crackers</option>
                        <option value="candy">Candy</option>
                        <option value="pudding">Pudding</option>
                        <option value="piestarts">Pies & Tarts</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Price</label>
                    <div class="price-input-container">
                        <span class="price-symbol">₹</span>
                        <input type="number" step="0.01" id="editProductPrice" name="price"
                            class="form-input price-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Weight (in kg)</label>
                    <input type="number" name="weight" step="0.01" id="editProductWeight" class="form-input"
                        placeholder="0.00" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea id="editProductDescription" name="description" class="form-textarea" maxlength="500"
                        require></textarea>
                </div>

                <!-- Image edit field handled by edit-cropper.js -->
                <input type="hidden" id="removeProductImage" name="remove_product_image" value="0">

                <div class="form-actions">
                    <button type="button" onclick="closeEditModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" name="save_changes" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'globalfooter.php'; ?>

    <script>
        // Modal Functions
        function toggleUploadModal() {
            const modal = document.getElementById('uploadModal');
            modal.classList.toggle('active');
        }

        function editProduct(button) {
            const modal = document.getElementById('editModal');
            modal.classList.add('active');

            // Extract data from the button
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const category = button.getAttribute('data-category');
            const price = button.getAttribute('data-price');
            const weight = button.getAttribute('data-weight');
            const description = button.getAttribute('data-description');

            document.getElementById('editProductId').value = id;
            document.getElementById('editProductName').value = name;
            document.getElementById('editProductCategory').value = category;
            document.getElementById('editProductPrice').value = price;
            document.getElementById('editProductWeight').value = weight;
            document.getElementById('editProductDescription').value = description;

            // Remove any previous image edit group to avoid duplicates
            const prevEditGroup = modal.querySelector('.image-edit-group');
            if (prevEditGroup) prevEditGroup.remove();
            // Dynamically insert the image edit field (handled by edit-cropper.js)
            // Pass the current image URL to the cropper initializer
            let imageUrl = button.closest('.product-card').querySelector('.product-image').getAttribute('src');
            if (typeof window.initEditCropper === 'function') {
                window.initEditCropper(imageUrl);
            }
        }
        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('active');
        }

        // Filter Functions
        function filterProducts(category) {
            const products = document.querySelectorAll('.product-card');
            const buttons = document.querySelectorAll('.filter-btn');
            const noResults = document.getElementById('no-results-message');
            let visibleCount = 0;

            // Update active button
            buttons.forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            // Filter products
            products.forEach(product => {
                if (category === 'all' || product.dataset.category === category) {
                    product.style.display = 'block';
                    product.classList.add('fade-in');
                } else {
                    product.style.display = 'none';
                }
            });
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        // Search Functionality
        document.querySelector('.search-input').addEventListener('input', function (e) {
            const searchValue = e.target.value.toLowerCase();
            const products = document.querySelectorAll('.product-card');
            const noResults = document.getElementById('no-results-message');
            let visibleCount = 0;

            products.forEach(product => {
                const title = product.querySelector('.product-title').textContent.toLowerCase();
                const desc = product.querySelector('.product-description').textContent.toLowerCase();
                if (title.includes(searchValue) || desc.includes(searchValue)) {
                    product.style.display = 'block';
                    visibleCount++;
                } else {
                    product.style.display = 'none';
                }
            });
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });

        // like Functions
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.like-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const productId = this.dataset.productId;
                    const likeCountSpan = this.querySelector('.like-count');

                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=toggle_like&product_id=${productId}`
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                this.classList.toggle('liked', data.liked);
                                likeCountSpan.textContent = data.like_count;
                                if (data.liked) {
                                    this.classList.add('heart-animation');
                                    setTimeout(() => this.classList.remove('heart-animation'), 300);
                                }
                            } else {
                                alert('Error: ' + data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while processing your request.');
                        });
                });
            });
        });


        function togglecomment(productId) {
            const commentSection = document.getElementById(`comment-${productId}`);
            commentSection.classList.toggle('hidden');

            if (!commentSection.classList.contains('hidden')) {
                commentSection.classList.add('fade-in');
            }
        }

        // Close modals when clicking outside
        window.onclick = function (event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                }
            });
        }
    </script>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $weight = $_POST['weight'];
        $image = null;
        if (isset($_FILES['product_image_cropped']) && $_FILES['product_image_cropped']['error'] == 0) {
            $fileTmp = $_FILES['product_image_cropped']['tmp_name'];
            $fileType = mime_content_type($fileTmp);
            if ($fileType === 'image/jpeg') {
                $filename = 'product_' . $baker_id . '_' . time() . '.jpg';
                $dest = __DIR__ . '/uploads/' . $filename;
                if (move_uploaded_file($fileTmp, $dest)) {
                    $image = $filename;
                }
            }
        }
        // Proceed with insert
        $stmt = $conn->prepare("INSERT INTO products (name, category, price, description, baker_id, image,weight) VALUES (?, ?, ?, ?, ?, ?,?)");
        $stmt->bind_param("ssdsisd", $name, $category, $price, $description, $baker_id, $image, $weight);

        if ($stmt->execute()) {
            echo "<script>alert('Product added successfully!');window.location.href = 'bakerproductmngmt.php';</script>";
        } else {
            echo "<script>alert('Error adding product');</script>";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_changes'])) {
        $id = $_POST['product_id'];
        $updated_name = $_POST['name'];
        $updated_category = $_POST['category'];
        $updated_price = $_POST['price'];
        $updated_description = $_POST['description'];
        $updated_weight = $_POST['weight'];
        $new_image = null;
        $remove_image = isset($_POST['remove_product_image']) && $_POST['remove_product_image'] === '1';

        // Handle new image upload
        if (isset($_FILES['product_image_cropped']) && $_FILES['product_image_cropped']['error'] == 0) {
            $fileTmp = $_FILES['product_image_cropped']['tmp_name'];
            $fileType = mime_content_type($fileTmp);
            if ($fileType === 'image/jpeg') {
                $filename = 'product_' . $baker_id . '_' . time() . '.jpg';
                $dest = __DIR__ . '/uploads/' . $filename;
                if (move_uploaded_file($fileTmp, $dest)) {
                    $new_image = $filename;
                }
            }
        }

        // If remove image or new image, delete old image file
        if ($remove_image || $new_image) {
            $imgStmt = $conn->prepare("SELECT image FROM products WHERE product_id = ?");
            $imgStmt->bind_param("i", $id);
            $imgStmt->execute();
            $imgResult = $imgStmt->get_result();
            if ($imgResult->num_rows > 0) {
                $row = $imgResult->fetch_assoc();
                if (!empty($row['image'])) {
                    $imgPath = __DIR__ . '/uploads/' . $row['image'];
                    if (file_exists($imgPath)) {
                        unlink($imgPath);
                    }
                }
            }
        }

        if ($remove_image) {
            // Remove image from DB
            $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, description=?,weight=? ,image=NULL WHERE product_id=? ");
            $stmt->bind_param("ssdsdi", $updated_name, $updated_category, $updated_price, $updated_description, $updated_weight, $id);
        } else if ($new_image) {
            // Update with new image
            $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, description=?,weight=? , image=? WHERE product_id=? ");
            $stmt->bind_param("ssdsdsi", $updated_name, $updated_category, $updated_price, $updated_description, $updated_weight, $new_image, $id);
        } else {
            // No image change
            $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, description=? , weight=? WHERE product_id=? ");
            $stmt->bind_param("ssdsdi", $updated_name, $updated_category, $updated_price, $updated_description, $updated_weight, $id);
        }
        if ($stmt->execute()) {
            echo "<script>alert('Product updated successfully'); window.location.href = 'bakerproductmngmt.php';</script>";
        } else {
            echo "<script>alert('Update failed.');</script>";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
        $productId = $_POST['delete_product_id'];
        // Remove product image from uploads if exists
        $imgStmt = $conn->prepare("SELECT image FROM products WHERE product_id = ?");
        $imgStmt->bind_param("i", $productId);
        $imgStmt->execute();
        $imgResult = $imgStmt->get_result();
        if ($imgResult->num_rows > 0) {
            $row = $imgResult->fetch_assoc();
            if (!empty($row['image'])) {
                $imgPath = __DIR__ . '/uploads/' . $row['image'];
                if (file_exists($imgPath)) {
                    unlink($imgPath);
                }
            }
        }
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            echo "<script>alert('Product deleted successfully!'); window.location.href = window.location.href;</script>";
        } else {
            echo "<script>alert('Error deleting product');</script>";
        }
    }

    ?>
    <!-- Cropper.js JS for product image upload -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="image-cropper.js"></script>
    <script src="edit-cropper.js"></script>
</body>

</html>