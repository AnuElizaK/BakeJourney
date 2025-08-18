<?php session_start();
include 'db.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
  header("Location: index.php"); // Redirect to login if not authorized
  exit();
}

// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

/*if (!isset($_GET['product_id'])) {
    echo "Product not found.";
    exit;
}

$product_id = $_GET['product_id'];
$sql = "SELECT *
        FROM products 
        JOIN users ON products.user_id = users.user_id 
        WHERE products.product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();

// Fetch baker by product_id
$stmt = $conn->prepare("SELECT *
        FROM bakers 
        JOIN users ON bakers.user_id = users.user_id 
        WHERE bakers.baker_id = ?");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$bakerResult = $stmt->get_result();
$bakers = $bakerResult->fetch_all(MYSQLI_ASSOC);
*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sourdough Bread - BakeJourney</title>
    <link rel="stylesheet" href="productinfopage.css">
</head>

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
    <div class="container">
        <!-- Product Main Info -->
        <div class="product-card">
            <div class="product-header">
                <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Artisan Sourdough Bread" class="product-image">
                
                <div class="product-info">
                    <h1 class="product-title">Sourdough Bread</h1>
                    <div class="product-category">Artisan Breads & Sourdoughs</div>
                    
                    <div class="product-meta">
                        <div class="meta-item">
                            <div class="meta-value">127</div>
                            <div class="meta-label">Reviews</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value">2.5</div>
                            <div class="meta-label">Hours Prep</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value">800g</div>
                            <div class="meta-label">Weight</div>
                        </div>
                    </div>

                    <div class="rating">
                        <div class="stars">★★★★★</div>
                        <span class="rating-text">5.0 • 98% satisfaction rate</span>
                    </div>

                    <div class="baker-info">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Sarah John" class="baker-avatar">
                        <div class="baker-details">
                            <h4>Made by Sarah John</h4>
                            <p>📍 Bengaluru Urban, Karnataka</p>
                        </div>
                    </div>

                    <div class="product-actions">
                        <div class="price">₹40.00</div>
                        <a href="#" class="btn btn-primary">Add to Cart</a>
                        <a href="#" class="btn btn-secondary">Message Baker</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div class="section-card">
            <h2 class="section-title">Product Description</h2>
            <div class="product-description">
                <p>Our signature artisan sourdough bread is crafted using traditional methods with a 48-hour fermentation process. Made with organic flour and our carefully maintained sourdough starter, this bread offers a perfect balance of tangy flavor and chewy texture with a beautifully crispy crust.</p>
                
                <p>Each loaf is hand-shaped and baked in small batches to ensure consistent quality. The long fermentation process not only develops complex flavors but also makes the bread easier to digest. Perfect for sandwiches, toast, or simply enjoyed with butter.</p>
                
                <p><strong>Storage:</strong> Best consumed within 3-4 days. Store in a paper bag at room temperature or freeze for up to 3 months.</p>
            </div>
        </div>

        <!-- Ingredients -->
        <div class="section-card">
            <h2 class="section-title">Ingredients</h2>
            <div class="ingredients-grid">
                <div class="ingredient-item">
                    <div class="ingredient-name">Organic Bread Flour</div>
                    <div class="ingredient-amount">500g</div>
                </div>
                <div class="ingredient-item">
                    <div class="ingredient-name">Sourdough Starter</div>
                    <div class="ingredient-amount">100g</div>
                </div>
                <div class="ingredient-item">
                    <div class="ingredient-name">Filtered Water</div>
                    <div class="ingredient-amount">350ml</div>
                </div>
                <div class="ingredient-item">
                    <div class="ingredient-name">Sea Salt</div>
                    <div class="ingredient-amount">10g</div>
                </div>
                <div class="ingredient-item">
                    <div class="ingredient-name">Olive Oil</div>
                    <div class="ingredient-amount">15ml</div>
                </div>
            </div>
        </div>

        <!-- Customer Reviews -->
        <div class="section-card">
            <div class="reviews-header">
                <h2 class="section-title">Customer Reviews</h2>
                <div class="rating">
                    <div class="stars">★★★★★</div>
                    <span class="rating-text">5.0 out of 5 stars • 127 reviews</span>
                </div>
            </div>

            <div class="review-item">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Emily R." class="reviewer-avatar">
                        <div>
                            <div class="reviewer-name">Emily R.</div>
                            <div class="review-date">2 days ago</div>
                        </div>
                    </div>
                    <div class="review-stars">★★★★★</div>
                </div>
                <div class="review-text">
                    Sarah's sourdough is absolutely incredible! The texture and flavor are unmatched. I've been ordering weekly for months now and every loaf is consistently perfect. The 48-hour fermentation really makes a difference!
                </div>
            </div>

            <div class="review-item">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Michael C." class="reviewer-avatar">
                        <div>
                            <div class="reviewer-name">Michael C.</div>
                            <div class="review-date">1 week ago</div>
                        </div>
                    </div>
                    <div class="review-stars">★★★★★</div>
                </div>
                <div class="review-text">
                    Best bread I've ever had! Sarah's attention to detail and passion really shows in every loaf. The sourdough tang is perfect and the crust has that amazing crunch. Professional quality with that perfect homemade touch.
                </div>
            </div>

            <div class="review-item">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80" alt="Jennifer L." class="reviewer-avatar">
                        <div>
                            <div class="reviewer-name">Jennifer L.</div>
                            <div class="review-date">2 weeks ago</div>
                        </div>
                    </div>
                    <div class="review-stars">★★★★★</div>
                </div>
                <div class="review-text">
                    Amazing experience! Sarah is so professional and her bread is restaurant quality. The delivery was prompt and the packaging kept everything fresh. This sourdough has become a weekly staple in our household!
                </div>
            </div>
        </div>
    </div>

    <script>
        let quantity = 1;

        function changeQuantity(change) {
            quantity += change;
            if (quantity < 1) quantity = 1;
            if (quantity > 10) quantity = 10;
            
            document.getElementById('quantity').textContent = quantity;
            
            // Update price
            const basePrice = 40;
            const totalPrice = basePrice * quantity;
            document.querySelector('.price').textContent = `₹${totalPrice.toFixed(2)}`;
        }

        // Add smooth scrolling for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>