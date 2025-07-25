<?php 
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php"); // Redirect to login if not authorized
    exit();
}
// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

 ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home | BakeJourney</title>
  <meta name="description" content="BakeJourney - The Home Baker's Marketplace" />
  <meta name="author" content="BakeJourney" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="@bakejourney" />
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
        <div class="product-card" data-category="breads">
          <div class="product-image">
            <img
              src="https://images.unsplash.com/photo-1549931319-a545dcf3bc73?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
              alt="Artisan Sourdough">
            <button class="cart-button">
              <img src="media/cart2.png" alt="Cart" style="vertical-align:top; width: 20px; height: 20px;"> Add to Cart
            </button>
          </div>
          <div class="product-content">
            <div class="product-header">
              <h3>Artisan Sourdough</h3>
              <span class="product-price">$8.50</span>
            </div>
            <p>Traditional 48-hour fermented sourdough with a perfect crust.</p>
          </div>
        </div>

        <div class="product-card" data-category="pastries">
          <div class="product-image">
            <img
              src="https://images.unsplash.com/photo-1722085609594-1bc764876867?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Chocolate Croissants">
            <button class="cart-button">
              <img src="media/cart2.png" alt="Cart" style="vertical-align:top; width: 20px; height: 20px;"> Add to Cart
            </button>
          </div>
          <div class="product-content">
            <div class="product-header">
              <h3>Chocolate Croissants</h3>
              <span class="product-price">$4.25</span>
            </div>
            <p>Buttery, flaky pastry filled with premium Belgian chocolate.</p>
          </div>
        </div>

        <div class="product-card" data-category="pies tarts">
          <div class="product-image">
            <img
              src="https://images.unsplash.com/photo-1666812663733-7a4e23369f6a?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Key Lime Pie">
            <button class="cart-button">
              <img src="media/cart2.png" alt="Cart" style="vertical-align:top; width: 20px; height: 20px;"> Add to Cart
            </button>
          </div>
          <div class="product-content">
            <div class="product-header">
              <h3>Key Lime Pie</h3>
              <span class="product-price">$15.00</span>
            </div>
            <p>Classic key lime pie with a graham cracker crust.</p>
          </div>
        </div>

        <div class="product-card" data-category="pies tarts">
          <div class="product-image">
            <img
              src="https://images.unsplash.com/photo-1666812663733-7a4e23369f6a?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Key Lime Pie">
            <button class="cart-button">
              <img src="media/cart2.png" alt="Cart" style="vertical-align:top; width: 20px; height: 20px;"> Add to Cart
            </button>
          </div>
          <div class="product-content">
            <div class="product-header">
              <h3>Key Lime Pie</h3>
              <span class="product-price">$15.00</span>
            </div>
            <p>Classic key lime pie with a graham cracker crust.</p>
          </div>
        </div>

        <div class="product-card" data-category="pies tarts">
          <div class="product-image">
            <img
              src="https://images.unsplash.com/photo-1666812663733-7a4e23369f6a?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Key Lime Pie">
            <button class="cart-button">
              <img src="media/cart2.png" alt="Cart" style="vertical-align:top; width: 20px; height: 20px;"> Add to Cart
            </button>
          </div>
          <div class="product-content">
            <div class="product-header">
              <h3>Key Lime Pie</h3>
              <span class="product-price">$15.00</span>
            </div>
            <p>Classic key lime pie with a graham cracker crust.</p>
          </div>
        </div>

        <div class="product-card" data-category="pies tarts">
          <div class="product-image">
            <img
              src="https://images.unsplash.com/photo-1666812663733-7a4e23369f6a?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Key Lime Pie">
            <button class="cart-button">
              <img src="media/cart2.png" alt="Cart" style="vertical-align:top; width: 20px; height: 20px;"> Add to Cart
            </button>
          </div>
          <div class="product-content">
            <div class="product-header">
              <h3>Key Lime Pie</h3>
              <span class="product-price">$15.00</span>
            </div>
            <p>Classic key lime pie with a graham cracker crust.</p>
          </div>
        </div>

        <div class="product-card" data-category="pies tarts">
          <div class="product-image">
            <img
              src="https://images.unsplash.com/photo-1666812663733-7a4e23369f6a?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Key Lime Pie">
            <button class="cart-button">
              <img src="media/cart2.png" alt="Cart" style="vertical-align:top; width: 20px; height: 20px;"> Add to Cart
            </button>
          </div>
          <div class="product-content">
            <div class="product-header">
              <h3>Key Lime Pie</h3>
              <span class="product-price">$15.00</span>
            </div>
            <p>Classic key lime pie with a graham cracker crust.</p>
          </div>
        </div>

        <div class="product-card" data-category="pies tarts">
          <div class="product-image">
            <img
              src="https://images.unsplash.com/photo-1666812663733-7a4e23369f6a?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Key Lime Pie">
            <button class="cart-button">
              <img src="media/cart2.png" alt="Cart" style="vertical-align:top; width: 20px; height: 20px;"> Add to Cart
            </button>
          </div>
          <div class="product-content">
            <div class="product-header">
              <h3>Key Lime Pie</h3>
              <span class="product-price">$15.00</span>
            </div>
            <p>Classic key lime pie with a graham cracker crust.</p>
          </div>
        </div>
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
        <div class="baker-card" onclick="window.location.href='bakerinfopage.php'">
          <div class="baker-image">
            <img
              src="https://images.unsplash.com/photo-1675285458906-26993548039c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Sarah Johnson">
            <div class="ranking-badge">#1</div>
          </div>
          <div class="baker-content">
            <h3>Sarah Johnson</h3>
            <p class="baker-specialty">Specialty: Artisan Breads & Sourdoughs</p>
            <div class="baker-stats">
              <span class="stat">5+ Years exp.</span>
              <span class="stat">200+ Orders</span>
            </div>
          </div>
        </div>

        <div class="baker-card" onclick="window.location.href='bakerinfopage.php'">
          <div class="baker-image">
            <img
              src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
              alt="Mike Chen">
            <div class="ranking-badge">#2</div>
          </div>
          <div class="baker-content">
            <h3>Mike Chen</h3>

            <p class="baker-specialty">Specialty: Custom Cakes & Pastries</p>
            <div class="baker-stats">
              <span class="stat">3+ Years exp.</span>
              <span class="stat">150+ Orders</span>
            </div>
          </div>
        </div>

        <div class="baker-card" onclick="window.location.href='bakerinfopage.php'">
          <div class="baker-image">
            <img
              src="https://images.unsplash.com/photo-1611432579402-7037e3e2c1e4?q=80&w=1965&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Emma Williams">
            <div class="ranking-badge">#3</div>
          </div>
          <div class="baker-content">
            <h3>Emma Williams</h3>
            <p class="baker-specialty">Specialty: Gluten-Free Treats</p>
            <div class="baker-stats">
              <span class="stat">4+ Years exp.</span>
              <span class="stat">120+ Orders</span>
            </div>
          </div>
        </div>

        <div class="baker-card" onclick="window.location.href='bakerinfopage.php'">
          <div class="baker-image">
            <img
              src="https://images.pexels.com/photos/7966423/pexels-photo-7966423.jpeg?_gl=1*jma4f6*_ga*MTY3NDQ3MzE4NC4xNzM5NTAyMzg1*_ga_8JE65Q40S6*czE3NTExMDg2OTEkbzgkZzEkdDE3NTExMDg5MDckajEyJGwwJGgw"
              alt="Emma Williams">
            <div class="ranking-badge">#4</div>
          </div>
          <div class="baker-content">
            <h3>Clara Mei</h3>
            <p class="baker-specialty">Specialty: French Pastries</p>
            <div class="baker-stats">
              <span class="stat">3+ Years exp.</span>
              <span class="stat">100+ Orders</span>
            </div>
          </div>
        </div>
      </div>
      <div id="no-bakers-message"
        style="display:none; text-align:center; color:#f59e0b; font-weight:600; margin:32px 0;">
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
          </div>
        </div>

        <div class="service-card">
          <div class="service-image">
            <img
              src="https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
              alt="Custom Cakes">
          </div>
          <div class="service-content">
            <h3>Custom Cakes</h3>
            <p>Personalized cakes for all your special moments.</p>
            <ul class="service-features">
              <li>Custom designs</li>
              <li>Countless flavors</li>
              <li>Dietary accommodations</li>
              <li>Delivery available</li>
            </ul>
          </div>
        </div>
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