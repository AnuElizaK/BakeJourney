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

    /* Products */

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
      background: white;
      border-radius: 20px;
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