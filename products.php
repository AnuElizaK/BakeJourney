<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
  header("Location: index.php"); // Redirect to login if not authorized
  exit();
}

// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

$user_id = $_SESSION['user_id'];

// Get products in cart for this customer
$cart_stmt = $conn->prepare("SELECT product_id FROM cart WHERE user_id = ?");
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

$cart_products = [];
while ($cart_item = $cart_result->fetch_assoc()) {
  $cart_products[] = $cart_item['product_id'];
}

// Get liked products for this customer
$like_stmt = $conn->prepare("SELECT product_id FROM product_likes WHERE customer_id = ?");
$like_stmt->bind_param("i", $user_id);
$like_stmt->execute();
$like_result = $like_stmt->get_result();

$liked_products = [];
while ($like_item = $like_result->fetch_assoc()) {
  $liked_products[] = $like_item['product_id'];
}

$stmt = $conn->prepare("
  SELECT *,
  (SELECT COUNT(*) 
          FROM product_likes pl 
          WHERE pl.product_id = p.product_id) AS like_count
  FROM products p
  JOIN bakers b ON p.baker_id = b.baker_id
  JOIN users u ON b.user_id = u.user_id
  ORDER BY RAND()
");
$stmt->execute();
$result = $stmt->get_result();

// Like function
if (isset($_POST['action']) && $_POST['action'] === 'toggle_like') {
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

    echo json_encode([
      'success' => true,
      'liked' => $liked,
    ]);
    exit();
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Products | BakeJourney</title>
  <meta name="description" content="BakeJourney - The Home Baker's Marketplace" />
  <meta name="author" content="BakeJourney" />
  <link rel="stylesheet" href="products.css" />
</head>

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
  <!-- Products Section -->
  <section class="products" id="products">
    <div class="container">
      <div class="section-header">
        <h2>Explore All Products</h2>
        <p>Discover our most loved creations, baked fresh with only the finest ingredients.</p>
      </div>

      <!-- Product Search and Filter -->
      <div class="filter-section">
        <div class="search-box">
          <input type="search" placeholder="Search or filter products..." class="product-search-input">
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
          <button onclick="filterProducts('pies tarts')" class="filter-btn">Pies & Tarts</button>
          <button onclick="filterProducts('liked')" class="filter-btn l-btn">Liked Products</button>
        </div>
      </div>

      <div class="products-grid">
        <?php while ($product = $result->fetch_assoc()): ?>
          <?php
          $is_in_cart = in_array($product['product_id'], $cart_products);
          $is_liked = in_array($product['product_id'], $liked_products);
          ?>
          <div class="product-card"
               data-category="<?= htmlspecialchars($product['category']) ?>"
               data-liked="<?= $is_liked ? 'true' : 'false' ?>"
               onclick="window.location.href='productinfopage.php?product_id=<?= $product['product_id']; ?>'">
            <div class="product-image">
              <img
                src="<?= !empty($product['image']) ? 'uploads/' . htmlspecialchars($product['image']) : 'media/pastry.png' ?>"
                alt="<?= htmlspecialchars($product['name']) ?>">

              <?php if ($is_in_cart): ?>
                <!-- Show "Added to Cart" button if product is in cart -->
                <button class="cart-button added" disabled>
                  <img src="media/cart2yellow.png" alt="Added" style="width: 20px; height: 20px; vertical-align: top;">
                  Added to Cart
                </button>
              <?php else: ?>
                <form method="POST" action="cart.php">
                  <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                  <input type="hidden" name="quantity" value="1">
                  <button type="submit" name="add_to_cart" class="cart-button">
                    <img src="media/cart2.png" alt="Cart" style="width: 20px; height: 20px; vertical-align: top;"> Add to Cart
                  </button>
                </form>
              <?php endif; ?>

              <button class="social-btn like-btn <?= $is_liked ? 'liked' : '' ?>"
                      data-product-id="<?= $product['product_id'] ?>">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
              </button>
            </div>
            <div class="product-content">
              <div class="product-header">
                <h3><?= htmlspecialchars($product['name']) ?> <br>
                  <small style="font-size:small;">By
                    <a href="bakerinfopage.php?baker_id=<?= $product['baker_id']; ?>"
                       style="color:orange; text-decoration:none;">
                      <?= htmlspecialchars($product['brand_name'] ?: $product['full_name']) ?>
                    </a>
                  </small>
                </h3>
                <span class="product-price">₹<?= number_format($product['price'], 2) ?></span>
              </div>
              <p style="font-size: 0.8rem">
                <?php
                $desc = strip_tags($product['description']);
                $words = explode(' ', $desc);
                $max_words = 10;
                if (count($words) > $max_words) {
                  $short = implode(' ', array_slice($words, 0, $max_words)) . '...' . ' more';
                } else {
                  $short = $desc;
                }
                echo htmlspecialchars($short);
                ?>
              </p>
              <br />
              <p style="font-size: 0.65rem; position: absolute; bottom: 20px; left: 20px; color: #8b919c">Posted on
                <?= date('d M Y', strtotime($product['created_at'])) ?>
              </p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
      <div id="no-products-message"
           style="display:none; text-align:center; color:#f59e0b; font-weight:600; margin:32px 0;">
        No products found.
      </div>
    </div>
  </section>

  <?php include 'globalfooter.php'; ?>

  <script>
    // ---Product Filter Functions---
    function filterProducts(category) {
      const products = document.querySelectorAll('.product-card');
      const buttons = document.querySelectorAll('.filter-btn');
      const noProducts = document.getElementById('no-products-message');
      let visibleCount = 0;

      // Update active button
      buttons.forEach(btn => {
        btn.classList.remove('active');
        const btnText = btn.textContent.toLowerCase().replace(/ & /g, ' ');
        if (btnText === category || (category === 'all' && btnText === 'all products') || (category === 'liked' && btnText === 'liked products')) {
          btn.classList.add('active');
        }
      });

      // Filter products
      products.forEach(product => {
        if (category === 'all' || 
            (category === 'liked' && product.dataset.liked === 'true') ||
            (category !== 'liked' && product.dataset.category === category)) {
          product.style.display = 'block';
          product.classList.add('fade-in');
          visibleCount++;
        } else {
          product.style.display = 'none';
        }
      });

      noProducts.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    // Product Search
    document.querySelector('.product-search-input').addEventListener('input', function (e) {
      const searchValue = e.target.value.toLowerCase();
      const products = document.querySelectorAll('.product-card');
      const noProducts = document.getElementById('no-products-message');
      let visibleCount = 0;

      products.forEach(product => {
        const title = product.querySelector('.product-header h3').textContent.toLowerCase();
        const desc = product.querySelector('.product-content p').textContent.toLowerCase();
        if (title.includes(searchValue) || desc.includes(searchValue)) {
          product.style.display = 'block';
          visibleCount++;
        } else {
          product.style.display = 'none';
        }
      });
      noProducts.style.display = visibleCount === 0 ? 'block' : 'none';
    });

    // ---Like Button Functionality---
    document.querySelectorAll('.like-btn').forEach(button => {
      button.addEventListener('click', function (e) {
        e.stopPropagation(); // Prevent card click from triggering
        const productId = this.dataset.productId;

        fetch('', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `action=toggle_like&product_id=${productId}`
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              this.classList.toggle('liked', data.liked);
              // Update data-liked attribute dynamically
              this.closest('.product-card').dataset.liked = data.liked ? 'true' : 'false';
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
  </script>
</body>

</html>