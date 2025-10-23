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
// LEFT JOIN to include bakers with no reviews
$bkrStmt = $conn->prepare("
  SELECT u.*, b.*, AVG(br.rating) AS rating
  FROM users u
  JOIN bakers b ON u.user_id = b.user_id
  LEFT JOIN baker_reviews br ON b.baker_id = br.baker_id
  GROUP BY b.baker_id, u.user_id
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
          <div class="product-card" data-category="<?= htmlspecialchars($product['category']) ?>"
            onclick="window.location.href='productinfopage.php?product_id=<?= $product['product_id']; ?>'">
            <div class="product-image">
              <img
                src="<?= !empty($product['image']) ? 'uploads/' . htmlspecialchars($product['image']) : 'media/pastry.png' ?>"
                alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="product-content">
              <div class="product-header">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <span class="product-price">₹<?= number_format($product['price'], 2) ?></span>
              </div>
              <p style="font-size: 0.9rem">
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
          <div class="baker-card" onclick="window.location.href='bakerinfopage.php?baker_id=<?= $baker['baker_id']; ?>'">
            <div class="baker-image">
              <img
                src="<?= !empty($baker['profile_image']) ? 'uploads/' . htmlspecialchars($baker['profile_image']) : 'media/baker.png' ?>"
                alt="<?php echo htmlspecialchars($baker['full_name']); ?>">
              <div class="ranking-badge">#<?php echo htmlspecialchars($baker['baker_id']); ?></div>
            </div>
            <div class="baker-content">
              <h3><?php echo htmlspecialchars($baker['full_name']); ?></h3>
              <p class="baker-specialty"><?php echo htmlspecialchars($baker['specialty']); ?></p>
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

  <!-- Services Section -->
  <section class="services" id="services">
    <div class="container">
      <div class="section-header">
        <h2>Services From Our Bakers</h2>
        <p>From daily fresh baking to custom celebrations, we're here to make every moment sweeter.</p>
      </div>

      <div class="services-grid">
        <div class="service-card">
          <div class="service-image">
            <img
              src="https://images.unsplash.com/photo-1490644120458-f5e5c71d2ab0?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Home Delivery">
          </div>
          <div class="service-content">
            <h3>Home Delivery</h3>
            <p>From the kitchen straight to your doorstep.</p>
            <ul class="service-features">
              <li>Available from 500+ bakers</li>
              <li>Freshly made</li>
              <li>Your treat, your location</li>
              <li>Safe, contactless delivery</li>
            </ul>
            <button class="btn btn-primary" onclick="openDialog('homeDelivery')">Learn More</button>
          </div>
        </div>

        <div class="service-card">
          <div class="service-image">
            <img
              src="https://images.unsplash.com/photo-1464348026323-e1ee90c7e56b?q=80&w=1939&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Custom Cakes">
          </div>
          <div class="service-content">
            <h3>Custom Orders</h3>
            <p>Personalized treats for all your special moments.</p>
            <ul class="service-features">
              <li>Custom designs</li>
              <li>Countless flavors</li>
              <li>Dietary accommodations</li>
              <li>Delivery available</li>
            </ul>
            <button class="btn btn-primary" onclick="openDialog('customCakes')">Learn More</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Home Delivery Dialog -->
  <div class="dialog-overlay" id="homeDeliveryDialog">
    <div class="dialog">
      <div class="dialog-header">
        <h2 class="dialog-title">Home Delivery</h2>
        <button class="close-btn" onclick="closeDialog('homeDeliveryDialog')">&times;</button>
      </div>
      <div class="dialog-content">
        <p>Experience the convenience of fresh, artisanal baked goods delivered right to your doorstep. Our extensive
          network of professional bakers ensures you get the highest quality treats without leaving your home.</p>

        <h3>How It Works</h3>
        <ul>
          <li>Browse our selection of bakers and their specialties</li>
          <li>Place your order through our easy-to-use platform</li>
          <li>Your chosen baker prepares your order fresh</li>
          <li>Safe, contactless delivery to your specified location</li>
        </ul>

        <h3>Coverage & Availability</h3>
        <ul>
          <li><strong>500+ Partner Bakers:</strong> Extensive network across multiple cities</li>
          <li><strong>Daily Fresh Baking:</strong> Orders prepared the same day for maximum freshness</li>
          <li><strong>Flexible Locations:</strong> Deliver to your home, office, or any preferred address</li>
          <li><strong>Multiple Time Slots:</strong> Choose delivery times that work with your schedule</li>
        </ul>

        <div class="highlight-box">
          <strong>Safety First:</strong> All our delivery partners follow strict hygiene protocols and offer contactless
          delivery options to ensure your safety and peace of mind.
        </div>

        <h3>What You Can Order</h3>
        <ul>
          <li>Fresh bread and pastries</li>
          <li>Custom birthday and celebration cakes</li>
          <li>Seasonal specialties and holiday treats</li>
          <li>Corporate catering orders</li>
          <li>Wedding and event desserts</li>
        </ul>

        <div class="highlight-box">
          <strong>Note:</strong> While our bakers strive to deliver to as many locations as possible, certain remote or
          restricted areas may not
          be reachable. It is also important to note that not all bakers may offer delivery services, so please check
          individual baker profiles
          for specific delivery options and areas served.
        </div>
      </div>
    </div>
  </div>

  <!-- Custom Cakes Dialog -->
  <div class="dialog-overlay" id="customCakesDialog">
    <div class="dialog">
      <div class="dialog-header">
        <h2 class="dialog-title">Custom Orders</h2>
        <button class="close-btn" onclick="closeDialog('customCakesDialog')">&times;</button>
      </div>
      <div class="dialog-content">
        <p>Turn your special moments into unforgettable memories with customized orders. Our talented bakers will work
          with you to create the perfect treat that matches your vision, taste preferences, and dietary needs.</p>

        <h3>Personalization Options</h3>
        <ul>
          <li><strong>Custom Designs:</strong> From elegant minimalism to elaborate themed creations</li>
          <li><strong>Personal Messages:</strong> Custom text, names, and special dedications</li>
          <li><strong>Photo Printing:</strong> Edible photo transfers for truly personal touch</li>
          <li><strong>Theme Matching:</strong> Colors and designs to match your event or celebration</li>
        </ul>

        <h3>Flavor Varieties</h3>
        <ul>
          <li>Classic favorites: Vanilla, chocolate, strawberry, red velvet</li>
          <li>Gourmet options: Salted caramel, lemon lavender, chocolate raspberry</li>
          <li>Seasonal specialties: Pumpkin spice, peppermint, fresh fruit combinations</li>
          <li>Unique creations: Matcha, coconut lime, coffee tiramisu</li>
        </ul>

        <div class="highlight-box">
          <strong>Dietary Accommodations:</strong> We cater to all dietary needs including gluten-free, vegan,
          sugar-free, keto-friendly, and allergy-sensitive options without compromising on taste or design.
        </div>

        <h3>Perfect For</h3>
        <ul>
          <li>Birthday celebrations of all ages</li>
          <li>Wedding and anniversary cakes</li>
          <li>Corporate events and milestones</li>
          <li>Graduation and achievement celebrations</li>
          <li>Holiday and seasonal gatherings</li>
          <li>Baby showers and gender reveals</li>
        </ul>

        <h3>Ordering Process</h3>
        <ul>
          <li>Consultation to discuss your vision and requirements</li>
          <li>Design mockup and flavor selection</li>
          <li>Confirmation of details and delivery arrangements</li>
          <li>Fresh preparation 24-48 hours before your event</li>
          <li>Professional delivery and setup (when requested)</li>
        </ul>

        <div class="highlight-box">
          <strong>Note:</strong> While our bakers strive to accommodate all requests, certain complex designs or
          last-minute orders may
          require additional lead time. We recommend booking your custom orders at least two weeks in advance to
          ensure availability and the best possible outcome.
        </div>
      </div>
    </div>
  </div>

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

    function openDialog(serviceType) {
      const dialogId = serviceType + 'Dialog';
      const dialog = document.getElementById(dialogId);
      if (dialog) {
        dialog.classList.add('show');
        document.body.style.overflow = 'hidden';

        // Add click listener specifically to this dialog
        dialog.onclick = function (e) {
          if (e.target === dialog) {
            closeDialog(dialogId);
          }
        };
      }
    }

    function closeDialog(dialogId) {
      const dialog = document.getElementById(dialogId);
      if (dialog) {
        dialog.classList.remove('show');
        document.body.style.overflow = '';
        dialog.onclick = null; // Remove the click listener
      }
    }

  </script>
</body>

</html>