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

// Fetch 8 products from the database
$prdStmt = $conn->prepare("
  SELECT *
  FROM products p
  JOIN bakers b ON p.baker_id = b.baker_id
  ORDER BY RAND()
  LIMIT 8
");
$prdStmt->execute();
$pResult = $prdStmt->get_result();

// Fetch 8 bakers from the database
$bkrStmt = $conn->prepare("
  SELECT *
  FROM users u
  JOIN bakers b ON u.user_id = b.user_id
  LIMIT 8
");
$bkrStmt->execute();
$bResult = $bkrStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home | BakeJourney</title>
  <meta name="description" content="BakeJourney - The Home Baker's Marketplace" />
  <meta name="author" content="BakeJourney" />
  <link rel="stylesheet" href="customerdashboard.css">
</head>

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
  <!-- Hero Section -->
  <section class="hero" id="home">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <div class="hero-icon">
        <img src="media/Logo.png" alt="BakeJourney Logo" width="40" height="40" class="logo-image">
      </div>

      <h1 class="hero-title">
        BakeJourney
        <span class="hero-subtitle">The Home Baker's Marketplace</span>
      </h1>

      <p class="hero-description">
        Experience the warmth of homemade goodness in every bite.
      </p>
    </div>
  </section>


  <!-- Products Section -->
  <section class="products" id="products">
    <div class="container">
      <div class="section-header">
        <h2>Explore Homemade Delights</h2>
        <p>Discover our most loved creations, baked fresh with only the finest ingredients.</p>
      </div>

      <div class="products-grid">
        <?php while ($product = $pResult->fetch_assoc()): ?>
        <div class="product-card" data-category="<?= htmlspecialchars($product['category']) ?>">
          <div class="product-image">
            <img
              src="<?= !empty($product['image']) ? 'uploads/' . htmlspecialchars($product['image']) : 'https://images.unsplash.com/photo-1549931319-a545dcf3bc73?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' ?>"
              alt="<?= htmlspecialchars($product['name']) ?>">
            <button class="cart-button">
              <img src="media/cart2.png" alt="Cart" style="vertical-align:top; width: 20px; height: 20px;"> Add to Cart
            </button>
          </div>
          <div class="product-content">
            <div class="product-header">
              <h3><?= htmlspecialchars($product['name']) ?></h3>
              <span class="product-price">₹<?= number_format($product['price'], 2) ?></span>
            </div>
            <p><?= htmlspecialchars($product['description']) ?></p>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
      <div id="no-products-message"
        style="display:none; text-align:center; color:#f59e0b; font-weight:600; margin:32px 0;">
        No products found.
      </div>

      <div>
        <h4 class="btn-primary more" onclick="window.location.href='products.php'">
          See more →
        </h4>
      </div>

    </div>
  </section>


  <!-- Top Bakers Section -->
  <section class="top-bakers" id="bakers">
    <div class="container">
      <div class="section-header">
        <h2>Find Your Baker</h2>
        <p>Looking for the right baker but don't know where to start? Discover our community's most talented homebakers
          right here.</p>
      </div>

      <div class="bakers-grid">
        <?php while ($baker = $bResult->fetch_assoc()): ?>
        <div class="baker-card" onclick="window.location.href='bakerinfopage.php'">
          <div class="baker-image">
            <img
              src="<?= !empty($baker['image']) ? 'uploads/' . htmlspecialchars($baker['image']) : 'https://images.unsplash.com/photo-1675285458906-26993548039c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' ?>"
              alt="<?php echo htmlspecialchars($baker['full_name']); ?>">
            <div class="ranking-badge">#<?php echo htmlspecialchars($baker['baker_id']); ?></div>
          </div>
          <div class="baker-content">
            <h3><?php echo htmlspecialchars($baker['full_name']); ?></h3>
            <p class="baker-specialty">Specialty: Artisan Breads & Sourdoughs</p>
            <div class="baker-stats">
              <span class="stat"><?php echo htmlspecialchars($baker['experience']); ?>+ Years exp.</span>
              <span class="stat"><?php echo number_format($baker['rating'], 1); ?> Rating</span>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
      <div id="no-bakers-message"
        style="display: none; text-align: center; color: #f59e0b; font-weight: 600; margin: 32px 0;">
        No bakers found.
      </div>

      <div>
        <h4 class="btn-primary more" onclick="window.location.href='bakers.php'">
          Find more →
        </h4>
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
      });
      event.target.classList.add('active');

      // Products Filters
      products.forEach(product => {
        if (category === 'all' || product.dataset.category === category) {
          product.style.display = 'block';
          product.classList.add('fade-in');
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
        const title = product.querySelector('.product-content').textContent.toLowerCase();
        const desc = product.querySelector('.product-header').textContent.toLowerCase();
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

    // ---Baker Search Function---
    document.querySelector('.baker-search-input').addEventListener('input', function (e) {
      const searchValue = e.target.value.toLowerCase();
      const bakers = document.querySelectorAll('.baker-card');
      const noBakers = document.getElementById('no-bakers-message');
      let visibleCount = 0;

      bakers.forEach(baker => {
        const title = baker.querySelector('.baker-content').textContent.toLowerCase();
        const specialty = baker.querySelector('.baker-specialty').textContent.toLowerCase();
        if (title.includes(searchValue) || specialty.includes(searchValue)) {
          baker.style.display = 'block';
          visibleCount++;
        } else {
          baker.style.display = 'none';
        }
      });
      if (noBakers) {
        noBakers.style.display = visibleCount === 0 ? 'block' : 'none';
      }
    });

  </script>
</body>

</html>