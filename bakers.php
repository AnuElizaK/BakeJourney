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

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
  <!-- All Bakers Section -->
  <section class="all-bakers" id="bakers">
    <div class="container">
      <div class="section-header">
        <h2>Find Your Perfect Baker</h2>
        <p>Looking for the right baker but don't know where to start? Discover homebakers that get you like no one else,
          right here.</p>
      </div>

      <!-- Baker Search -->
      <div class="filter-section">
        <div class="search-box">
          <input type="search" placeholder="Search bakers by name or specialty..." class="baker-search-input">
        </div>
      </div>

      <div class="bakers-grid">
        <div class="baker-card" onclick="window.location.href='userpage.php'">
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

        <div class="baker-card" onclick="window.location.href='userpage.php'">
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

        <div class="baker-card" onclick="window.location.href='userpage.php'">
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

        <div class="baker-card" onclick="window.location.href='userpage.php'">
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

  <?php include 'globalfooter.php'; ?>

  <script>
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