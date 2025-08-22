<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include 'db.php';

$user_id = $_SESSION['user_id'] ?? null; // Avoids warning

if ($user_id) {
  // Get cart count for this customer
  $cart_count_stmt = $conn->prepare("SELECT COUNT(*) as cart_count FROM cart WHERE user_id = ?");
  $cart_count_stmt->bind_param("i", $user_id);
  $cart_count_stmt->execute();
  $cart_count_result = $cart_count_stmt->get_result();
  $cart_data = $cart_count_result->fetch_assoc();
  $cart_count = $cart_data['cart_count'];
} else {
  $cart_count = 0; // No session user
}

if ($user_id) {
  // Get user information
  $user_info_stmt = $conn->prepare("SELECT full_name, profile_image FROM users WHERE user_id = ?");
  $user_info_stmt->bind_param("i", $user_id);
  $user_info_stmt->execute();
  $user_info_result = $user_info_stmt->get_result();
  $user_info = $user_info_result->fetch_assoc();
}

$has_cart_items = $cart_count > 0;
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .custnav-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: rgba(255, 255, 255, 0.769);
      backdrop-filter: blur(12px);
      z-index: 1000;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .nav-content {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 18px 0;
    }

    .nav-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 1.75rem;
      font-weight: bold;
      color: #1f2a38;
      text-decoration: none;
    }

    .nav-title {
      font-family: 'Puanto', Roboto, sans-serif;
      text-decoration: none;
      color: #1f2a38;
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 23px;
    }

    .nav-link {
      color: #374151;
      text-decoration: none;
      font-weight: 500;
      padding: 8px 0;
      position: relative;
      transition: all 0.3s ease;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: #f59e0b;
      transition: width 0.3s ease;
    }

    .nav-link:hover::after {
      width: 100%;
    }

    .nav-link.opened {
      color: #f59e0b;
    }

    .nav-link.opened::after {
      width: 100%;
    }

    .nav-cta {
      background: linear-gradient(135deg, #fcd34d, #f59e0b);
      color: white;
      padding: 10px 20px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
    }

    .nav-cta:hover {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(217, 119, 6, 0.4);
    }

    .nav-cta::after {
      display: none;
    }

    .nav-cta.opened {
      color: rgb(255, 255, 255);
      background: linear-gradient(135deg, #f59e0b, #d97706);
      box-shadow: 0 6px 16px rgba(217, 119, 6, 0.4);
    }

    /* Cart Icon Container with Notification Dot */
    .cart-container {
      position: relative;
      display: inline-block;
    }

    .cart-notification-dot {
      position: absolute;
      top: -5px;
      right: -5px;
      width: 12px;
      height: 12px;
      background-color: #ff4444;
      border-radius: 50%;
      border: 2px solid white;
      display: none;
      /* Hidden by default */
      animation: pulse 2s infinite;
    }

    /* Show dot when cart has items */
    .cart-container.has-items .cart-notification-dot {
      display: block;
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.1);
      }

      100% {
        transform: scale(1);
      }
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      background: transparent;
      border-radius: 50%;
      object-fit: cover;
      transition: all 0.3s ease;
    }

    .user-avatar:hover {
      cursor: pointer;
      transform: scale(1.1);
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.25);
    }

    /* Mobile Menu Toggle */
    .nav-mobile-toggle {
      display: none;
      flex-direction: column;
      cursor: pointer;
      padding: 8px;
      gap: 4px;
    }

    .nav-mobile-toggle span {
      width: 24px;
      height: 3px;
      background: #374151;
      border-radius: 2px;
      transition: all 0.3s ease;
    }

    .nav-mobile-toggle.active span:nth-child(1) {
      transform: rotate(45deg) translate(6px, 6px);
    }

    .nav-mobile-toggle.active span:nth-child(2) {
      opacity: 0;
    }

    .nav-mobile-toggle.active span:nth-child(3) {
      transform: rotate(-45deg) translate(6px, -6px);
    }

    /* Mobile Navigation */
    @media (max-width: 768px) {
      body {
        padding-top: 70px;
      }

      .custnav-container {
        margin-top: 0;
      }

      .nav-mobile-toggle {
        display: flex;
      }

      .nav-links {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(12px);
        flex-direction: column;
        gap: 0;
        padding: 10px 24px;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        transform: translateY(-10px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      }

      .nav-links.nav-links-active {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
      }

      .nav-link {
        padding: 14px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        width: 100%;
        text-align: center;
      }

      .nav-link:last-child {
        border-bottom: none;
      }

      .nav-cta {
        width: 50%;
        margin-top: 16px;
        text-align: center;
        border-radius: 50px;
      }

      .nav-brand span {
        display: none;
      }

      /* Mobile cart notification adjustments */
      .cart-notification-dot {
        top: -3px;
        right: -3px;
        width: 10px;
        height: 10px;
      }
    }

    @media (min-width: 769px) and (max-width: 1030px) {
      body {
        padding-top: 78px;
      }

      .nav-content {
        padding: 19px 0;
      }

      .custnav-container {
        margin-top: 0;
      }

      .nav-mobile-toggle {
        display: none;
      }

      .nav-logo {
        width: 30px;
        height: 30px;
      }

      .nav-brand {
        gap: 10px;
        font-size: 1rem;
      }

      .nav-links {
        display: flex;
        align-items: center;
        gap: 15px;
      }

      .nav-link {
        color: #374151;
        text-decoration: none;
        font-size: 0.7rem;
        font-weight: 500;
        padding: 8px 0;
        position: relative;
        transition: all 0.3s ease;
      }

      .nav-link .cart-icon {
        width: 20px;
        height: 20px;
      }

      .nav-cta {
        background: linear-gradient(135deg, #fcd34d, #f59e0b);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
      }

      .nav-brand span {
        display: inline;
      }

      /* Tablet cart notification adjustments */
      .cart-notification-dot {
        top: -4px;
        right: -4px;
        width: 10px;
        height: 10px;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar" id="navbar">
    <div class="custnav-container">
      <div class="nav-content">
        <div class="nav-brand">
          <img class="nav-logo" src="media/Logo.png" alt="BakeJourney Logo" width="40" height="40">
          <span class="nav-title">BakeJourney</span>
        </div>

        <div class="nav-links">
          <a href="customerdashboard.php" class="nav-link">Home</a>
          <a href="products.php" class="nav-link">Products</a>
          <a href="bakers.php" class="nav-link">Find Your Baker</a>
          <a href="services.php" class="nav-link">Services</a>
          <a href="contact.php" class="nav-link">Contact Us</a>
          <a href="customerorders.php" class="nav-link">Orders</a>

          <!-- Cart link with notification dot -->
          <div class="cart-container <?php echo $has_cart_items ? 'has-items' : ''; ?>">
            <a href="cart.php" class="nav-link">
              <img class="cart-icon" src="media/cart.png" title="Cart" alt="Cart" width="30" height="30">
              <div class="cart-notification-dot"></div>
            </a>
          </div>

          <!-- User avatar and profile link -->
          <img onclick="window.location.href='customerprofile.php'"
            src="<?= !empty($user_info['profile_image']) ? 'uploads/' . htmlspecialchars($user_info['profile_image']) : 'media/profile.png' ?>"
            alt="<?php echo htmlspecialchars($user_info['full_name']); ?>" 
            title="Visit Your Profile"class="user-avatar">
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

  <script>

    // Highlight active nav link based on current page
    document.addEventListener('DOMContentLoaded', function () {
      const navLinks = document.querySelectorAll('.nav-link[href]');
      const currentPage = window.location.pathname.split('/').pop();
      navLinks.forEach(link => {
        const linkPage = link.getAttribute('href').split('?')[0];
        if (linkPage === currentPage) {
          link.classList.add('opened');
        }
      });
    });

    // Navbar scroll behavior
    window.addEventListener('scroll', function () {
      const navbar = document.getElementById('navbar');
      const hero = document.querySelector('.hero');
      const heroHeight = hero ? hero.offsetHeight : 0;

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
  </script>
</body>

</html>