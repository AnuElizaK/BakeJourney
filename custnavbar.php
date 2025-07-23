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
      padding: 14px 0;
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
      gap: 32px;
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
      border-radius: 25px;
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
      color:rgb(255, 255, 255);
      background: linear-gradient(135deg, #f59e0b, #d97706);
      box-shadow: 0 6px 16px rgba(217, 119, 6, 0.4);
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
        padding: 20px 24px;
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
        padding: 16px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        width: 100%;
        text-align: center;
      }

      .nav-link:last-child {
        border-bottom: none;
      }

      .nav-cta {
        margin-top: 16px;
        text-align: center;
        border-radius: 25px;
      }

      .nav-brand span {
        display: none;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar" id="navbar">
    <div class="custnav-container">
      <div class="nav-content">
        <div class="nav-brand">
          <img src="media/Logo.png" alt="BakeJourney Logo" width="40" height="40">
          <span class="nav-title">BakeJourney</span>
        </div>

        <div class="nav-links">
          <a href="customerdashboard.php" class="nav-link">Home</a>
          <a href="products.php" class="nav-link">Products</a>
          <a href="bakers.php" class="nav-link">Find Your Baker</a>
          <a href="services.php" class="nav-link">Services</a>
          <a href="contact.php" class="nav-link">Contact Us</a>
          <a href="cart.php" class="nav-link"><img src="media/cart.png" title="Cart"
              alt="Cart" width="30" height="30"></a>
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