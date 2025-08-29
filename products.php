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
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Roboto, sans-serif;
      line-height: 1.6;
      padding-top: 80px;
      color: #1f2a38;
    }

    h1,
    h2 {
      font-family: 'Puanto', Roboto, sans-serif;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
    }

    /* Buttons */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 14px 36px;
      font-size: 1.125rem;
      font-weight: 600;
      border-radius: 50px;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      position: relative;
      overflow: hidden;
      gap: 8px;
    }

    .btn-primary {
      background: linear-gradient(135deg, #fcd34d, #f59e0b);
      color: white;
      cursor: pointer;
      box-shadow: 0 8px 20px rgba(217, 119, 6, 0.3);
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      transform: translateY(-2px);
      box-shadow: 0 12px 25px rgba(217, 119, 6, 0.4);
    }

    .btn-large {
      padding: 18px 48px;
      font-size: 1.25rem;
    }

    .btn-full {
      width: 100%;
    }

    .btn-outline {
      border: 2px solid white;
      color: white;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
    }

    .btn-outline:hover {
      background: white;
      color: #f59e0b;
      transform: translateY(-2px);
    }

    /* Section Headers */
    .section-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .section-header h2 {
      font-size: 3rem;
      font-weight: bold;
      color: #1f2a38;
      margin-bottom: 20px;
      letter-spacing: -0.02em;
    }

    .section-header p {
      font-size: 1.25rem;
      color: #6b7280;
      max-width: 600px;
      margin: 0 auto;
      line-height: 1.7;
    }

    /* Like Button */
    .social-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background: rgba(255, 255, 255, 0.9);
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
      color: orange;
    }

    .like-btn svg {
      width: 20px;
      height: 20px;
      transition: all 0.3s ease;
    }

    .like-btn.liked svg {
      fill: #ef4444;
      stroke: #ef4444;
    }

    .like-btn:hover {
      background: rgba(255, 255, 255, 1);
      transform: scale(1.1);
    }

    .like-count {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #f59e0b;
      color: white;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 600;
    }

    /* Filter Tabs */
    .filter-section {
      background: none;
      padding: 15px 20px;
      border-radius: 0.75rem;
      margin-bottom: 2rem;
    }

    .product-search-input {
      width: 100%;
      padding: 12px 45px;
      background: transparent url("media/search.png") no-repeat 10px center;
      border: 1.5px solid #c6c8ca;
      border-radius: 0.65rem;
      font-size: 1rem;
      outline: none;
      transition: border-color 0.3s ease;
      margin-top: 0.5rem;
      margin-bottom: 0.5rem;
    }

    .product-search-input:hover {
      border-color: #8b919c;
      background-color: #f8f9fa;
    }

    .product-search-input:focus {
      outline: none;
      border-color: #f59e0b;
      box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
    }

    .filter-tabs {
      display: flex;
      margin-top: 0.5rem;
      margin-bottom: 0.5rem;
      border-radius: 0.65rem;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .filter-btn {
      padding: 12px 15px;
      border: none;
      background: #f8f9fa;
      border-radius: 0.65rem;
      font-size: 0.9rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      color: #6b7280;
    }

    .filter-btn.active {
      background: linear-gradient(135deg, #fcd34d, #f59e0b);
      color: white;
    }

    .filter-btn:hover:not(.active) {
      background: #fee996;
    }

    .products {
      padding: 50px 0;
      background: linear-gradient(#fff1bb, #ffffff);
    }

    .products-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 40px;
    }

    @media (min-width: 768px) {
      .products-grid {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    .product-card {
      position: relative;
      background: white;
      border-radius: 20px;
      cursor: pointer;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      transition: all 0.4s ease;
    }

    .product-card:hover {
      box-shadow: 0 25px 45px rgba(0, 0, 0, 0.15);
      transform: translateY(-10px);
    }

    .product-image {
      position: relative;
      overflow: hidden;
    }

    .product-image img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .product-card:hover .product-image img {
      transform: scale(1.1);
    }

    .cart-button {
      position: absolute;
      top: 20px;
      left: 20px;
      background: linear-gradient(135deg, #fcd34d, #d97706);
      color: white;
      padding: 8px 16px;
      border: none;
      cursor: pointer;
      font-family: 'Segoe UI', Roboto, sans-serif;
      border-radius: 25px;
      font-size: 0.875rem;
      font-weight: 600;
      transition: all 0.4s ease;
      box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .cart-button:hover {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      transform: translateY(-3px);
      box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
    }

    .cart-button.added {
      background: white;
      border: 2px solid #f59e0b;
      color: #f59e0b;
    }

    .product-content {
      padding: 10px 20px 20px;
    }

    .product-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }

    .product-header h3 {
      font-size: 1.3rem;
      font-weight: 600;
      color: #1f2a38;
      line-height: 1.3;
      margin: 0;
    }

    .product-price {
      font-size: 1.5rem;
      font-weight: bold;
      color: #f59e0b;
      margin: 0;
      line-height: 1.3;
    }

    .product-content p {
      color: #6b7280;
      line-height: 1.6;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .section-header h2 {
        font-size: 2rem;
      }

      .section-header p {
        font-size: 1rem;
      }

      .products-grid {
        gap: 20px;
      }

      .product-card {
        border-radius: 14px;
      }

      .product-image img {
        height: 120px;
      }

      .product-content {
        padding: 24px;
      }
    }
  </style>
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
        </div>
      </div>

      <div class="products-grid">
        <?php while ($product = $result->fetch_assoc()): ?>
          <?php 
            $is_in_cart = in_array($product['product_id'], $cart_products);
            $is_liked = in_array($product['product_id'], $liked_products);
          ?>
          <div class="product-card"
            onclick="window.location.href='productinfopage.php?product_id=<?= $product['product_id']; ?>'"
            data-category="<?= htmlspecialchars($product['category']) ?>">
            <div class="product-image">
              <img
                src="<?= !empty($product['image']) ? 'uploads/' . htmlspecialchars($product['image']) : 'media/pastry.png' ?>"
                alt="<?= htmlspecialchars($product['name']) ?>">

              <?php if ($is_in_cart): ?>
                <!-- Show "Added to Cart" button if product is in cart -->
                <button class="cart-button added" disabled>
                  <img src="media/cart2yellow.png" alt="Added" style="width: 20px; height: 20px; vertical-align: top;"> Added to
                  Cart
                </button>
              <?php else: ?>
                <form method="POST" action="cart.php">
                  <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                  <input type="hidden" name="quantity" value="1">
                  <button type="submit" name="add_to_cart" class="cart-button">
                    <img src="media/cart2.png" alt="Cart" style="width: 20px; height: 20px; vertical-align: top;"> Add to
                    Cart
                  </button>
                </form>
              <?php endif; ?>

              <button class="social-btn like-btn <?= $is_liked ? 'liked' : '' ?>" data-product-id="<?= $product['product_id'] ?>">
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
                      <?= htmlspecialchars($product['brand_name'] ?: $product['full_name']) ?></a>
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
                  $short = implode(' ', array_slice($words, 0, $max_words)) . '...'. ' more';
                } else {
                  $short = $desc;
                }
                echo htmlspecialchars($short);
                ?>
              </p>
              <br />
              <p style="font-size: 0.65rem; position: absolute; bottom: 20px; left: 20px; color: #8b919c">Posted on
                <?= date('d M Y', strtotime($product['created_at'])) ?></p>
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
    // ---Product Search and Filter Functions---
    function filterProducts(category) {
      const products = document.querySelectorAll('.product-card');
      const buttons = document.querySelectorAll('.filter-btn');
      const noProducts = document.getElementById('no-products-message');
      let visibleCount = 0;

      // Update active button
      buttons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.textContent.toLowerCase().replace(/ & /g, ' ') === category || (category === 'all' && btn.textContent === 'All Products')) {
          btn.classList.add('active');
        }
      });

      // Products Filters
      products.forEach(product => {
        if (category === 'all' || product.dataset.category === category) {
          product.style.display = 'block';
          product.classList.add('fade-in');
          visibleCount++;
        } else {
          product.style.display = 'none';
        }
      });
      if (noProducts) {
        noProducts.style.display = visibleCount === 0 ? 'block' : 'none';
      }
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
      if (noProducts) {
        noProducts.style.display = visibleCount === 0 ? 'block' : 'none';
      }
    });

    // ---Like Button Functionality---
    document.querySelectorAll('.like-btn').forEach(button => {
      button.addEventListener('click', function(e) {
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