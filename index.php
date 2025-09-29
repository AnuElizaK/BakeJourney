<?php
session_start();
include 'db.php';

// Fetch baker details
$stmt = $conn->prepare("
  SELECT u.user_id, u.full_name, u.profile_image, b.brand_name, b.specialty, b.experience
  FROM users u
  JOIN bakers b ON u.user_id = b.user_id
  Limit 3
");
$stmt->execute();
$result = $stmt->get_result();

// Fetch products details
$productStmt = $conn->prepare("
  SELECT p.product_id, p.name AS product_name,  p.price, p.image AS product_image,
         b.brand_name, u.full_name, p.description 
  FROM products p
  JOIN bakers b ON p.baker_id = b.baker_id
  JOIN users u ON b.user_id = u.user_id
  ORDER BY RAND() 
  LIMIT 3
");
$productStmt->execute();
$productResult = $productStmt->get_result();

// Fetch blog posts
$blogStmt = $conn->prepare("
  SELECT bg.blog_id, bg.blog_title, bg.content, bg.blog_image, bg.category, u.full_name,
  (SELECT COUNT(*) FROM blog_likes bl WHERE bl.blog_id = bg.blog_id) AS like_count,
  (SELECT COUNT(*) FROM blog_comments bc WHERE bc.blog_id = bg.blog_id) AS comment_count
  FROM blog bg
  JOIN users u ON bg.user_id = u.user_id
  ORDER BY RAND()
  LIMIT 3
");
$blogStmt->execute();
$blogResult = $blogStmt->get_result();

$alertMessage = ""; // default no message
$alertType = "";    // success or error

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_feedback'])) {
  // Collect form data safely
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');

  // Basic validation
  if ($name && $email && $subject && $message) {
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
      $alertMessage = "Thank you for your feedback!";
      $alertType = "success";
    } else {
      $alertMessage = "Failed to send feedback. Please try again.";
      $alertType = "error";
    }
    $stmt->close();
  } else {
    $alertMessage = "⚠ Please fill all fields.";
    $alertType = "warning";
  }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BakeJourney | Home Baker's Marketplace</title>
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <meta name="description"
    content="BakeJourney - Handcrafted with love, baked to perfection. Fresh breads, pastries, and custom cakes made daily with the finest ingredients." />
  <meta name="author" content="BakeJourney" />

  <meta property="og:title" content="BakeJourney - The Home Baker's Marketplace" />
  <meta property="og:description"
    content="Experience the warmth of homemade goodness in every bite. Fresh breads, pastries, and custom cakes." />
  <meta property="og:type" content="website" />

  <link rel="stylesheet" href="indexstyles.css">
</head>

<body>
  <!-- Sticky Pre-login Navigation Bar -->
  <nav class="navbar" id="navbar">
    <div class="nav-container">
      <div class="nav-content">
        <div class="nav-brand">
          <a href="index.php"><img src="media/Logo.png" alt="BakeJourney Logo" width="40" height="40"
              style="vertical-align: top;"></a>
          <span><a class="nav-title" href="index.php">BakeJourney</a></span>
        </div>

        <div class="nav-links">
          <a href="#home" class="nav-link">Home</a>
          <a href="#featured" class="nav-link">Menu</a>
          <a href="#about" class="nav-link">About</a>
          <a href="#services" class="nav-link">Services</a>
          <a href="#blog" class="nav-link">Blog</a>
          <a href="#contact" class="nav-link">Contact Us</a>
          <a href="bakersignup.php?role=baker" class="nav-link nav-cta">Join as Baker</a>
          <a href="login.php" class="nav-link nav-cta">Login or Sign Up</a>
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
      <!-- Inline Alert -->
      <div>
        <?php if (!empty($alertMessage)): ?>
          <div class="alert-box alert-<?= htmlspecialchars($alertType) ?>" id="feedbackAlert">
            <?= htmlspecialchars($alertMessage) ?>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
          </div>
        <?php endif; ?>
      </div>

      <div class="hero-icon">
        <img src="media/Logo.png" alt="BakeJourney Logo" width="80" height="80" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="logo-image">
      </div>
      <h1 class="hero-title">
        BakeJourney
        <span class="hero-subtitle">The Home Baker's Marketplace</span>
      </h1>

      <p class="hero-description">
        Handcrafted with love, baked to perfection. Experience the warmth of homemade goodness in every bite.
      </p>

      <div class="hero-buttons">
        <button class="btn btn-primary"
          onclick="document.getElementById('tpbakers').scrollIntoView({behavior: 'smooth'})">Explore</button>
        <button class="btn btn-outline"
          onclick="document.getElementById('featured').scrollIntoView({behavior: 'smooth'})">Order Now</button>
      </div>
    </div>
  </section>

  <!-- Top Bakers Section -->
  <section class="top-bakers" id="tpbakers">
    <div class="container">
      <div class="section-header">
        <h2>Top Rated Bakers</h2>
        <p>Discover our community's most talented homebakers, ranked by customer reviews and ratings.</p>
      </div>

      <div class="bakers-grid">
        <?php while ($baker = $result->fetch_assoc()): ?>
          <div class="baker-card" onclick="window.location.href='login.php'">
            <div class="baker-image">
              <img
                src="<?= !empty($baker['profile_image']) ? 'uploads/' . htmlspecialchars($baker['profile_image']) : 'media/baker.png' ?>"
                alt="Profile Image">

              <div class="ranking-badge">Top</div>
            </div>
            <div class="baker-content">
              <h3><?php echo htmlspecialchars($baker['full_name']); ?></h3>
              <p style="color: #888;"><?php echo htmlspecialchars($baker['brand_name']); ?></p>
              <div class="baker-rating">
                <!-- <div class="stars">
                  <?php
                  $stars = floor($baker['rating']);
                  for ($i = 0; $i < $stars; $i++)
                    echo "<span class=\"star filled\">★</span>";
                  for ($i = $stars; $i < 5; $i++)
                    echo "<span class=\"star\">☆</span>";
                  ?>
                </div> -->
                <!-- <span
                  class="rating-number"><?php echo number_format($baker['rating'], 1); ?>&nbsp;(<?php echo htmlspecialchars($baker['no_of_reviews']); ?>
                  Reviews)</span> -->
              </div>
              <p class="baker-specialty">Specialty: <?php echo htmlspecialchars($baker['specialty']); ?></p>
              <div class="baker-stats">
                <span class="stat"><?php echo htmlspecialchars($baker['experience']); ?>+ Years Experience</span>
                <span class="stat">200+ Orders</span>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
    <div>
      <h4 class="btn-primary more" onclick="window.location.href='login.php'">
        View All Bakers →
      </h4>
    </div>
  </section>

  <!-- Sign Up CTA Section -->
  <section class="sign-up-cta">
    <div class="container">
      <div class="cta-grid">
        <div class="cta-card">
          <div class="cta-icon-baker">
            <img src="media/baker.svg" alt="Baker Icon" width="124" height="124">
          </div>
          <h3 class="baker-cta-title">Want to be Featured in the Spotlight?</h3>
          <p>Join our community of talented home bakers and showcase your delicious creations to food lovers everywhere!
          </p>
          <div class="cta-features">
            <div class="feature">
              <span class="feature-icon">✨</span>
              <span>Get featured on our homepage</span>
            </div>
            <div class="feature">
              <span class="feature-icon">🏆</span>
              <span>Earn ratings, reviews, and rankings</span>
            </div>
            <div class="feature">
              <span class="feature-icon">💰</span>
              <span>Grow your baking business</span>
            </div>
          </div>
          <button class="btn btn-primary btn-large" onclick="window.location.href='bakersignup.php'">
            Join as a Baker →
            </svg>
          </button>
        </div>

        <div class="cta-card">
          <div class="cta-icon-binocular">
            <img src="media/binocular.svg" alt="Looking for Bakers" width="68" height="68">
          </div>
          <h3 class="baker-cta-title">Or Looking for that Perfect Baker?</h3>
          <p>Connect with skilled home bakers who craft treats tailored to your taste, wherever and whenever you need
            them!
          </p>
          <div class="cta-features">
            <div class="feature">
              <span class="feature-icon">👩🏻‍🍳</span>
              <span>Find the right baker for your needs</span>
            </div>
            <div class="feature">
              <span class="feature-icon">🍩</span>
              <span>Discover delicious creations</span>
            </div>
            <div class="feature">
              <span class="feature-icon">🫱🏻‍🫲🏼</span>
              <span>Support small businesses</span>
            </div>
          </div>
          <button class="btn btn-primary btn-large" onclick="window.location.href='customersignup.php'">
            Sign Up Now →
            </svg>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- Featured Products Section -->
  <section class="featured-products" id="featured">
    <div class="container">
      <div class="section-header">
        <h2>Featured Delights</h2>
        <p>Discover our most loved creations, baked fresh with only the finest ingredients.</p>
      </div>

      <div class="products-grid">
        <?php while ($product = $productResult->fetch_assoc()): ?>
          <div class="product-card" onclick="window.location.href='login.php'">
            <div class="product-image">
              <img
                src="<?= !empty($product['product_image']) ? 'uploads/' . htmlspecialchars($product['product_image']) : 'media/pastry.png' ?>"
                alt="<?= htmlspecialchars($product['product_name']) ?>">
              <span class="product-badge" onclick="window.location.href='login.php'">Order Now</span>
            </div>
            <div class="product-content">
              <div class="product-header">
                <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                <span class="product-price">₹<?= htmlspecialchars($product['price']) ?></span>
              </div>
              <p class="description"><?= htmlspecialchars($product['description']) ?></p>
              <p class="creator" onclick="window.location.href='login.php'">By
                <?= htmlspecialchars($product['brand_name'] ?: $product['full_name']) ?>
              </p>

            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
    <div>
      <h4 class="btn-primary more" onclick="window.location.href='login.php'">
        Explore All Products →
      </h4>
    </div>
  </section>

  <!-- About Section -->
  <section class="about" id="about">
    <div class="container">
      <div class="about-content">
        <div class="about-text">
          <h2>What Is BakeJourney?</h2>
          <p>BakeJourney exists to support home bakers who dream big and bake even bigger. Born from the real challenges
            faced by small, home-based bakeries, our platform is designed to be your one-stop digital toolkit and
            simplify day-to-day operations, so bakers can stay focused on the flour-dusted magic in the kitchen.</p>
          <p>Whether it's managing orders, tracking your inventory, or connecting with loyal customers, BakeJourney is
            here to support your journey with style, efficiency, and a sprinkle of sweetness.</p>
          <p>And for customers craving something special? BakeJourney helps them discover talented local bakers, browse
            personalized menus, place custom orders, and support small businesses, all from the comfort of home.</p>
          <p style="font-weight: 500;">Because every journey is sweeter when it’s homemade.</p>

          <div class="values-grid">
            <div class="value-item">
              <div class="value-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5" />
                  <path d="M8.5 8.5v.01" />
                  <path d="M16 15.5v.01" />
                  <path d="M12 12v.01" />
                  <path d="M11 17v.01" />
                  <path d="M7 14v.01" />
                </svg>
              </div>
              <h3>Fresh Ingredients</h3>
              <p>Our bakers source only the finest, locally-sourced ingredients for authentic flavors.</p>
            </div>

            <div class="value-item">
              <div class="value-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M2 18l5-10 5 6 5-6 5 10" stroke-linecap="round" stroke-linejoin="round" />
                  <circle cx="5" cy="5" r="1" />
                  <circle cx="12" cy="3" r="1" />
                  <circle cx="19" cy="5" r="1" />
                  <path d="M2 18h20" stroke-linecap="round" />
                </svg>
              </div>
              <h3>Unrivaled Quality</h3>
              <p>Baking techniques passed down through generations that ensure exceptional taste and texture.</p>
            </div>

            <div class="value-item">
              <div class="value-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 21C12 21 4 13.5 4 8a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 13-10 13z" />
                </svg>


              </div>
              <h3>Made with Love</h3>
              <p>Every item is crafted with care and attention to detail.</p>
            </div>
          </div>
        </div>

        <div class="about-video">
          <video autoplay muted loop>
            <source src="media/donuts.mp4" type="video/mp4">
          </video>
          <div class="experience-badge">
            <div class="experience-number">1000+</div>
            <div class="experience-text">products to choose from,</div>
            <div class="experience-text">made by the best</div>
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

  <section class="blog" id="blog">
    <div class="container">
      <div class="section-header">
        <h2>Community Blog</h2>
        <p>Stay updated with the latest trends, tips, and stories from our baking community.</p>
      </div>

      <div class="blog-grid">
        <?php while ($blog = $blogResult->fetch_assoc()): ?>
          <article class="blog-post" data-category="announcements">
            <div class="post-image">
              <div class="post-image">
                <img
                  src="<?= !empty($blog['blog_image']) ? 'uploads/' . htmlspecialchars($blog['blog_image']) : 'media/pastry.png' ?>"
                  alt="<?= htmlspecialchars($blog['blog_title']) ?>">
              </div>
            </div>
            <div class="post-content">
              <div class="post-meta">
                <span
                  class="category-badge <?= strtolower($blog['category']) ?>"><?= htmlspecialchars($blog['category']) ?></span>
                <span class="post-author">
                  <p class="author" onclick="window.location.href='login.php'" title="Visit the author">
                    By <?= htmlspecialchars($blog['full_name']) ?>
                  </p>
                </span>
              </div>
              <h2 class="post-title"><?= htmlspecialchars($blog['blog_title']) ?></h2>
              <p class="post-excerpt">
                <?php
                $content = strip_tags($blog['content']);
                $words = explode(' ', $content);
                $max_words = 20;
                if (count($words) > $max_words) {
                  $excerpt = implode(' ', array_slice($words, 0, $max_words)) . '...';
                } else {
                  $excerpt = $content;
                }
                echo htmlspecialchars($excerpt);
                ?>
              </p>
              <div class="post-actions">
                <div class="action-group">
                  <button class="action-btn like-btn" onclick="window.location.href='login.php'" title="Log in to like">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="like-count"><?= $blog['like_count'] ?></span>
                  </button>

                  <button class="action-btn comment-btn" onclick="window.location.href='login.php'"
                    title="Log in to comment">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span><?= $blog['comment_count'] ?></span>
                  </button>

                  <button class="action-btn share-btn" onclick="sharePost(this)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" stroke="currentColor" stroke-width="2" />
                      <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" stroke="currentColor" stroke-width="2" />
                      <circle cx="6" cy="12" r="3" fill="currentColor" />
                      <circle cx="18" cy="6" r="3" fill="currentColor" />
                      <circle cx="18" cy="18" r="3" fill="currentColor" />
                    </svg>
                    <span>Share</span>
                  </button>
                </div>
                <button class="read-more-btn" onclick="window.location.href='login.php'" title="Log in to read more">Read
                  More</button>
              </div>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
      <div>
        <h4 class="btn-primary more" onclick="window.location.href='login.php'">
          Discover More →
        </h4>
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
        <div class="contact-form">
          <div class="form-card" id="feedback_card">
            <h3 class="contact-form-title">Send us a Message</h3>
            <form method="POST" id="feedbackForm">
              <div class="form-row">
                <input type="text" placeholder="Your Name" name="name" required>
                <input type="email" placeholder="Email Address" name="email" required>
              </div>
              <input class="form-row" type="text" placeholder="Subject" name="subject" required>
              <textarea class="form-row" placeholder="Your message..." rows="5" name="message" required></textarea>
              <button type="submit" class="btn btn-primary btn-full" name="send_feedback">Send Message</button>
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
            <li><a href="#"><img src="media/facebook.svg" alt="Facebook"
                  style="vertical-align: bottom;">&nbsp;Facebook</a></li>
            <li><a href="#"><img src="media/instagram.svg" alt="Instagram"
                  style="vertical-align: bottom;">&nbsp;Instagram</a></li>
            <li><a href="#"><img src="media/pinterest.svg" alt="Pinterest"
                  style="vertical-align: bottom;">&nbsp;Pinterest</a></li>
            <li><a href="#"><img src="media/x.svg" alt="X (Twitter)" style="vertical-align: bottom;">&nbsp;X</a></li>
            <li><a href="#"><img src="media/linkedin.svg" alt="LinkedIn"
                  style="vertical-align: bottom;">&nbsp;LinkedIn</a></li>
            <li><a href="#"><img src="media/github.svg" alt="GitHub" style="vertical-align: bottom;">&nbsp;GitHub</a>
            </li>
          </ul>
        </div>
        <div class="footer-attributions">
          <h3 class="attributions">Attributions</h3>
          <ul>
            <li>Icons by <a href="https://icons8.com">Icons8</a> & <a href="https://www.flaticon.com/">Flaticon</a></li>
            <li>Images by <a href="https://unsplash.com/">Unsplash</a> & <a href="https://www.pexels.com/">Pexels</a></li>
            <li>Fonts by <a href="https://fonts.google.com/">Google Fonts</a></li>
            <li>Illustrations by <a href="https://storyset.com/">Storyset</a></li>
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
        // Remove highlight from all nav links
        document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('opened'));
        // Add highlight to the clicked link
        this.classList.add('opened');
      });
    });

    // Highlight nav link for section in view on scroll
    const sectionIds = ['home', 'featured', 'about', 'services', 'blog', 'contact'];
    const navLinks = sectionIds.map(id => document.querySelector(`.nav-link[href="#${id}"]`));
    const sections = sectionIds.map(id => document.getElementById(id));

    function highlightCurrentSection() {
      let currentIndex = -1;
      const scrollPos = window.scrollY + 120; // Offset for navbar height
      sections.forEach((section, i) => {
        if (section && section.offsetTop <= scrollPos) {
          currentIndex = i;
        }
      });
      navLinks.forEach((link, i) => {
        if (link) link.classList.toggle('opened', i === currentIndex);
      });
    }
    window.addEventListener('scroll', highlightCurrentSection);
    window.addEventListener('DOMContentLoaded', highlightCurrentSection);

    // Auto-hide alert after 5 seconds
    document.addEventListener("DOMContentLoaded", function () {
      const alertBox = document.getElementById("feedbackAlert");
      if (alertBox) {
        setTimeout(() => {
          alertBox.classList.add("fade-out");
          setTimeout(() => alertBox.remove(), 1000); // remove after fade animation
        }, 5000);
      }
    });


    // Blog post share functionality
    function sharePost(btn) {
      const post = btn.closest('.blog-post');
      const title = post.querySelector('.post-title').textContent;

      if (navigator.share) {
        navigator.share({
          title: title,
          text: 'Check out this blog post from Sweet Spot Bakery',
          url: window.location.href
        });
      } else {
        // Fallback - copy to clipboard
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
          alert('Link copied to clipboard!');
        });
      }
    }

  </script>
</body>

</html>