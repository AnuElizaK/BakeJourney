<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home | BakeJourney</title>
    <meta name="description" content="BakeJourney - Handcrafted with love, baked to perfection. Fresh breads, pastries, and custom cakes made daily with the finest ingredients." />
    <meta name="author" content="BakeJourney" />

    <meta property="og:title" content="BakeJourney - Handcrafted Baked Goods" />
    <meta property="og:description" content="Experience the warmth of homemade goodness in every bite. Fresh breads, pastries, and custom cakes." />
    <meta property="og:type" content="website" />

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
              <span class="nav-title">BakeJourney</span>
          </div>
          
          <div class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="#featured" class="nav-link">Products</a>
            <a href="#services" class="nav-link">Services</a>
            <a href="#about" class="nav-link">About</a>
            <a href="#contact" class="nav-link">Contact Us</a>
            <a href="#orderplacement.php" class="nav-link"><img src="media/cart.png" title="Cart" alt="Cart" width="30" height="30"></a>
            <a href="customerprofile.php" class="nav-link nav-cta">Your Profile</a>
            <a href="signout.html" class="nav-link nav-cta">Sign Out</a>
          </div>
          
          <div class="nav-mobile-toggle" id="mobileToggle">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <div class="hero-icon">
          <img src="media/Logo.png" alt="BakeJourney Logo" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="logo-image">
        </div>

        <h1 class="hero-title">
          BakeJourney
          <span class="hero-subtitle">The Home Baker's Marketplace</span>
        </h1>
        
        <p class="hero-description">
          Handcrafted with love, baked to perfection. Experience the warmth of homemade goodness in every bite.
        </p>
        
        <div class="hero-buttons">
          <button class="btn btn-primary" onclick="document.getElementById('featured').scrollIntoView({behavior: 'smooth'})">Explore</button>
          <a href="login.html"><button class="btn btn-outline">Order Now</button></a>
        </div>
      </div>
    </section>

    <!-- Top Bakers Section -->
    <section class="top-bakers">
      <div class="container">
        <div class="section-header">
          <h2>Top Rated Bakers</h2>
          <p>Discover our community's most talented homebakers, ranked by customer reviews and ratings.</p>
        </div>

        <div class="bakers-grid">
          <div class="baker-card" onclick="window.location.href='login.html'">
            <div class="baker-image">
              <img src="https://images.unsplash.com/photo-1675285458906-26993548039c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Sarah Johnson">
              <div class="ranking-badge">#1</div>
            </div>
            <div class="baker-content">
              <h3>Sarah Johnson</h3>
              <div class="baker-rating">
                <div class="stars">
                  <span class="star filled">★</span>
                  <span class="star filled">★</span>
                  <span class="star filled">★</span>
                  <span class="star filled">★</span>
                  <span class="star filled">★</span>
                </div>
                <span class="rating-number">5.0 (127 reviews)</span>
              </div>
              <p class="baker-specialty">Specialty: Artisan Breads & Sourdoughs</p>
              <div class="baker-stats">
                <span class="stat">5+ Years Experience</span>
                <span class="stat">200+ Orders</span>
              </div>
            </div>
          </div>

          <div class="baker-card" onclick="window.location.href='login.html'">
            <div class="baker-image">
              <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Mike Chen">
              <div class="ranking-badge">#2</div>
            </div>
            <div class="baker-content">
              <h3>Mike Chen</h3>
              <div class="baker-rating">
                <div class="stars">
                  <span class="star filled">★</span>
                  <span class="star filled">★</span>
                  <span class="star filled">★</span>
                  <span class="star filled">★</span>
                  <span class="star half">★</span>
                </div>
                <span class="rating-number">4.8 (89 reviews)</span>
              </div>
              <p class="baker-specialty">Specialty: Custom Cakes & Pastries</p>
              <div class="baker-stats">
                <span class="stat">3+ Years Experience</span>
                <span class="stat">150+ Orders</span>
              </div>
            </div>
          </div>

          <div class="baker-card" onclick="window.location.href='login.html'">
            <div class="baker-image">
              <img src="https://images.unsplash.com/photo-1611432579402-7037e3e2c1e4?q=80&w=1965&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Emma Williams">
              <div class="ranking-badge">#3</div>
            </div>
            <div class="baker-content">
              <h3>Emma Williams</h3>
              <div class="baker-rating">
                <div class="stars">
                  <span class="star filled">★</span>
                  <span class="star filled">★</span>
                  <span class="star filled">★</span>
                  <span class="star filled">★</span>
                  <span class="star half">★</span>
                </div>
                <span class="rating-number">4.7 (64 reviews)</span>
              </div>
              <p class="baker-specialty">Specialty: Gluten-Free Treats</p>
              <div class="baker-stats">
                <span class="stat">4+ Years Experience</span>
                <span class="stat">120+ Orders</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Products Section -->
    <section class="featured-products" id="featured">
      <div class="container">
        <div class="section-header">
          <h2>Featured Delights</h2>
          <p>Discover our most loved creations, baked fresh with only the finest ingredients.</p>
        </div>

        <div class="products-grid">
          <div class="product-card">
            <div class="product-image">
              <img src="https://images.unsplash.com/photo-1549931319-a545dcf3bc73?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Artisan Sourdough">
              <span class="product-badge">Bestseller</span>
            </div>
            <div class="product-content">
              <div class="product-header">
                <h3>Artisan Sourdough</h3>
                <span class="product-price">$8.50</span>
              </div>
              <p>Traditional 48-hour fermented sourdough with a perfect crust.</p>
            </div>
          </div>

          <div class="product-card">
            <div class="product-image">
              <img src="https://images.unsplash.com/photo-1722085609594-1bc764876867?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Chocolate Croissants">
              <span class="product-badge">Fresh Daily</span>
            </div>
            <div class="product-content">
              <div class="product-header">
                <h3>Chocolate Croissants</h3>
                <span class="product-price">$4.25</span>
              </div>
              <p>Buttery, flaky pastry filled with premium Belgian chocolate.</p>
            </div>
          </div>

          <div class="product-card">
            <div class="product-image">
              <img src="https://images.unsplash.com/photo-1666812663733-7a4e23369f6a?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Key Lime Pie">
              <span class="product-badge">Made to Order</span>
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
              <img src="https://images.unsplash.com/photo-1490644120458-f5e5c71d2ab0?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Home Delivery">
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
              <button class="btn btn-primary">Learn More</button>
            </div>
          </div>

          <div class="service-card">
            <div class="service-image">
              <img src="https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Custom Cakes">
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
              <button class="btn btn-primary">Learn More</button>
            </div>
          </div>

          <div class="service-card">
            <div class="service-image">
              <img src="https://images.unsplash.com/photo-1572978577765-462b91a7f9e1?q=80&w=2071&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Catering Services">
            </div>
            <div class="service-content">
              <h3>Catering Services</h3>
              <p>Perfect for events, meetings, and gatherings of any size.</p>
              <ul class="service-features">
                <li>Event planning</li>
                <li>Fresh platters</li>
                <li>Hot & cold options</li>
                <li>Setup included</li>
              </ul>
              <button class="btn btn-primary">Learn More</button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
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
              <li>Images by <a href="https://unsplash.com/">Unsplash</a></li>
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
      window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        const heroHeight = document.querySelector('.hero').offsetHeight;
        
        if (window.scrollY > heroHeight - 100) {
          navbar.classList.add('navbar-visible');
        } else {
          navbar.classList.remove('navbar-visible');
        }
      });

      // Mobile menu toggle
      document.getElementById('mobileToggle').addEventListener('click', function() {
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
    </script>
  </body>
</html>