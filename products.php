<?php session_start(); ?>
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
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="@bakejourney" />
<link rel="stylesheet" href="customerdashboard.css">
</head>

<body>
  <!-- Sticky Navigation Bar -->
  <nav class="navbar" id="navbar">
    <div class="container">
      <div class="nav-content">
        <div class="nav-brand">
          <img src="media/Logo.png" alt="BakeJourney Logo" width="40" height="40">
          <span><a class="nav-title" href="customerdashboard.php">BakeJourney</a></span>
        </div>

        <div class="nav-links">
          <a href="customerdashboard.php" class="nav-link">Home</a>
          <a href="products.php" class="nav-link">Products</a>
          <a href="bakers.php" class="nav-link">Find Your Baker</a>
          <a href="#services" class="nav-link">Services</a>
          <a href="#contact" class="nav-link">Contact Us</a>
          <a href="cart.php" class="nav-link"><img src="media/cart.png" title="Cart" alt="Cart" width="30" height="30"></a>
          <a href="customerprofile.php" class="nav-link nav-cta">Your Profile</a>
          <a href="signout.php" class="nav-link nav-cta">Sign Out</a>
        </div>

        <div class="nav-mobile-toggle" id="mobileToggle">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
  </nav>

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
            <button class="cart-button">Add to Cart</button>
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
            <button class="cart-button">Add to Cart</button>
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
            <button class="cart-button">Add to Cart</button>
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
            <button class="cart-button">Add to Cart</button>
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
            <button class="cart-button">Add to Cart</button>
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
            <button class="cart-button">Add to Cart</button>
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
            <button class="cart-button">Add to Cart</button>
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
            <button class="cart-button">Add to Cart</button>
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

  <!-- Contact Section -->
  <section class="linked-page-contact" id="contact">
    <div class="container">
      <div class="section-header">
        <h2>Get In Touch</h2>
        <p>Got any questions or complaints? We'd love to hear from you!</p>
      </div>

      <div class="contact-content">
        <!--<div class="contact-info">
            <h3>Visit Our Bakery</h3>
            
            <div class="info-section">
              <h4>Address</h4>
              <p>123 Baker Street<br>Sweet Valley, SV 12345</p>
            </div>
            
            <div class="info-section">
              <h4>Hours</h4>
              <p>Monday - Friday: 6:00 AM - 7:00 PM<br>
                 Saturday: 7:00 AM - 8:00 PM<br>
                 Sunday: 8:00 AM - 6:00 PM</p>
            </div>
            
            <div class="info-section">
              <h4>Contact</h4>
              <p>Phone: (555) 123-BAKE<br>
                 Email: hello@sweetdreamsbakery.com</p>
            </div>

            <div class="special-orders">
              <h4>Special Orders</h4>
              <p>Need something special? Custom cakes and large orders require 48 hours advance notice. Call us to discuss your requirements!</p>
            </div>
          </div>-->

        <div class="contact-form">
          <div class="form-card">
            <h3 class="contact-form-title">Send us a Message</h3>
            <form>
              <div class="form-row">
                <input type="text" placeholder="Your Name" required>
                <input type="email" placeholder="Email Address" required>
              </div>
              <input class="form-row" type="text" placeholder="Subject" required>
              <textarea class="form-row" placeholder="Your message..." rows="5" required></textarea>
              <button type="submit" class="btn btn-primary btn-full">Send Message</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-main">
          <div class="footer-brand">
            <img src="media/Logo.png" alt="BakeJourney Logo" width="40" height="40">
            <span class="footer-title">BakeJourney</span>
          </div>
          <p class="footer-subtitle">The Home Baker's Marketplace</p>
          <p>Handcrafted with love, baked to perfection. Experience the warmth of homemade goodness in every bite.</p>
          <div class="footer-contact">
            <p>123 Baker Street, Cake Valley, SV 12345</p>
            <p>Phone: +91 xxxxx baker</p>
            <p>Email: hello@bakejourney.com</p>
          </div>
        </div>

        <div class="footer-links">
          <h3 class="quick-links">Quick Links</h3>
          <ul>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Sitemap</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Blog</a></li>
          </ul>
        </div>
        <div class="footer-social">
          <h3 class="follow-us">Follow Us</h3>
          <ul>
            <li><a href="#">Facebook</a></li>
            <li><a href="#">Instagram</a></li>
            <li><a href="#">Pinterest</a></li>
            <li><a href="#">Twitter</a></li>
            <li><a href="#">LinkedIn</a></li>
          </ul>
        </div>
        <div class="footer-attributions">
          <h3 class="attributions">Attributions</h3>
          <ul>
            <li>Icons by <a href="https://icons8.com">Icons8</a> & <a href="https://www.flaticon.com/">Flaticon</a></li>
            <li>Images by <a href="https://unsplash.com/">Unsplash</a> & <a href="https://www.pexels.com/">Pexels</a></li>
            <li>Fonts by <a href="https://fonts.google.com/">Google Fonts</a></li>
            <li>Branding font (Puanto) by <a href="https://creativemarket.com/pasha.larin">Larin Type Co.</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; 2025 BakeJourney. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    // Navbar scroll behavior
    window.addEventListener('scroll', function () {
      const navbar = document.getElementById('navbar');
      const heroHeight = document.querySelector('.hero').offsetHeight;

      if (window.scrollY > heroHeight - 100) {
        navbar.classList.add('navbar-visible');
      } else {
        navbar.classList.remove('navbar-visible');
      }
    });

    // Mobile menu toggle
    document.getElementById('mobileToggle').addEventListener('click', function () {
      const navLinks = document.querySelector('.nav-links');
      navLinks.classList.toggle('nav-links-active');
      this.classList.toggle('active');
    });

    // Smooth scrolling for navigation links
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