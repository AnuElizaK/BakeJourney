<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarah Johnson - Baker Profile | BakeJourney</title>
    <link rel="stylesheet" href="bakerinfopage.css">
</head>

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
    <!-- Backup Header -->
    <!--
    <header class="header">
      <div class="container">
        <div class="header-content">
          <div class="brand">
            <div class="brand-logo">
              <img src="media/Logo.png" alt="BakeJourney Logo" width="40" height="40">
            </div>
            <div>
              <h1 class="brand-title">BakeJourney</h1>
              <p class="brand-subtitle">View Baker Profile</p>
            </div>
          </div>
          <a href="customerdashboard.php" class="back-btn">
            ← Back to Home
          </a>
        </div>
      </div>
    </header>
    -->

    <!-- Main Content -->
    <main class="container">
        <!-- Profile Section -->
        <section class="profile-section">
            <div class="profile-header">
                <div class="profile-image">
                    <img src="https://images.unsplash.com/photo-1675285458906-26993548039c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Sarah Johnson" class="profile-avatar">
                    <div class="verified-badge">✓</div>
                </div>
                <div class="profile-info">
                    <h1>Sarah Johnson</h1>
                    <div class="profile-specialty">Artisan Breads & Sourdoughs</div>
                    <div class="profile-stats">
                        <div class="stat">
                            <span class="stat-number">127</span>
                            <span class="stat-label">Reviews</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">200+</span>
                            <span class="stat-label">Orders</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">5+</span>
                            <span class="stat-label">Years</span>
                        </div>
                    </div>
                    <div class="rating-section">
                        <div class="stars">
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                        </div>
                        <span class="rating-text">5.0 · 98% satisfaction rate</span>
                    </div>
                </div>
            </div>
            <div class="profile-bio">
                Master of traditional sourdough techniques with 48-hour fermentation process. I create perfect balance of flavor and texture using locally sourced organic flour. Every loaf tells a story of patience, passion, and perfection.
            </div>
            <div class="location">
                📍 Downtown Seattle · Delivers within 10 miles
            </div>
            <div class="profile-actions">
                <a href="#" class="btn btn-primary">Message Baker</a>
                <a href="#" class="btn btn-secondary">View Menu</a>
            </div>
        </section>

        <!-- Gallery Section -->
        <section class="gallery-section">
            <div class="gallery-header">
                <div class="gallery-title">Recent Creations</div>
                <div class="gallery-count">24 items</div>
            </div>
            <div class="image-gallery">
                <div class="gallery-item">
                    <img src="https://plus.unsplash.com/premium_photo-1690214491960-d447e38d0bd0?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Classic Sourdough" class="gallery-image">
                    <div class="gallery-overlay">
                        <div class="gallery-info">
                            <h4>Classic Sourdough</h4>
                            <p>$12.00</p>
                        </div>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1588195538326-c5b1e9f80a1b?q=80&w=1050&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Whole Wheat Loaf" class="gallery-image">
                    <div class="gallery-overlay">
                        <div class="gallery-info">
                            <h4>Whole Wheat Loaf</h4>
                            <p>$10.00</p>
                        </div>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1621303837174-89787a7d4729?q=80&w=736&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Rustic Baguette" class="gallery-image">
                    <div class="gallery-overlay">
                        <div class="gallery-info">
                            <h4>Rustic Baguette</h4>
                            <p>$6.00</p>
                        </div>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1603532648955-039310d9ed75?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Multigrain Bread" class="gallery-image">
                    <div class="gallery-overlay">
                        <div class="gallery-info">
                            <h4>Multigrain Bread</h4>
                            <p>$11.00</p>
                        </div>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Artisan Rolls" class="gallery-image">
                    <div class="gallery-overlay">
                        <div class="gallery-info">
                            <h4>Artisan Rolls</h4>
                            <p>$8.00</p>
                        </div>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1528975604071-b4dc52a2d18c?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjB8fHBhc3RyeXxlbnwwfHwwfHx8MA%3D%3D" alt="Focaccia Bread" class="gallery-image">
                    <div class="gallery-overlay">
                        <div class="gallery-info">
                            <h4>Focaccia Bread</h4>
                            <p>$9.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Reviews Section -->
        <section class="reviews-section">
            <div class="reviews-header">
                <div class="reviews-title">Customer Reviews</div>
                <div class="reviews-summary">
                    <div class="stars">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                    </div>
                    <span>5.0 out of 5 stars · 127 reviews</span>
                </div>
            </div>
            
            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="https://images.unsplash.com/photo-1710777915903-a7d7f159f2c0?q=80&w=2080&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Emily R." class="reviewer-avatar">
                        <div>
                            <div class="reviewer-name">Emily R.</div>
                            <div class="review-date">2 days ago</div>
                        </div>
                    </div>
                    <div class="review-rating">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                    </div>
                </div>
                <p class="review-text">Sarah's sourdough is absolutely incredible! The texture and flavor are unmatched. I've been ordering weekly for months now and every loaf is consistently perfect. The 48-hour fermentation really makes a difference!</p>
            </div>

            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Michael T." class="reviewer-avatar">
                        <div>
                            <div class="reviewer-name">Michael C.</div>
                            <div class="review-date">1 week ago</div>
                        </div>
                    </div>
                    <div class="review-rating">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                    </div>
                </div>
                <p class="review-text">Best bread I've ever had! Sarah's attention to detail and passion really shows in every loaf. The whole wheat bread is my family's favorite. Professional quality with that perfect homemade touch.</p>
            </div>

            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Jennifer L." class="reviewer-avatar">
                        <div>
                            <div class="reviewer-name">Jennifer L.</div>
                            <div class="review-date">2 weeks ago</div>
                        </div>
                    </div>
                    <div class="review-rating">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                    </div>
                </div>
                <p class="review-text">Amazing experience! Sarah is so professional and her bread is restaurant quality. The delivery was prompt and the packaging kept everything fresh. Will definitely order again for our next dinner party!</p>
            </div>
        </section>
    </main>
    <?php include 'globalfooter.php'; ?>
</body>
</html>