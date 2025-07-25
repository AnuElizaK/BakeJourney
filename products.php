<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Products | BakeJourney</title>
  <meta name="description" content="BakeJourney - The Home Baker's Marketplace" />
  <meta name="author" content="BakeJourney" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="@bakejourney" />
  <link rel="stylesheet" href="customerdashboard.css">
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

  </script>
</body>

</html>