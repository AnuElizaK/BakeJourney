<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Baker Dashboard | BakeJourney</title>
    <meta name="description" content="Baker dashboard for managing orders, products, and profile" />
    <link rel="stylesheet" href="bakerdashboard.css" />
  </head>

  <body>
    <!-- Header -->
    <header class="header">
      <div class="container">
        <div class="header-content">
          <div class="brand">
            <div class="brand-icon">
              <img src="media/Logo.png" alt="BakeJourney Logo" width="40" height="40">
            </div>
            <div>
              <h1 class="brand-title">BakeJourney</h1>
              <p class="brand-subtitle">Welcome back, Sarah Johnson!</p>
            </div>
          </div>
          
          <div class="header-actions">
            <button class="btn">
              <img src="media/alert.gif" alt="notifications" width="25" height="25">Notifications
              <span class="notification-badge">3</span>
            </button>
            <button class="btn">
              <img src="media/settings.png" alt="settings" width="25" height="25">Settings
            </button>
            <button class="btn" onclick="window.location.href='bakerprofile.php'">
              <img src="media/profile.png" alt="profile" width="25" height="25">Profile
            </button>
            <button class="btn" onclick="window.location.href='signout.php'">
              <img src="media/sign-out.png" alt="sign out" width="25" height="25">Sign Out
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main">
      <div class="container">
        <!-- Quick Stats -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-header">
              <span class="stat-title">Orders This Week</span>
              <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
            </div>
            <div class="stat-value">18</div>
            <div class="stat-change">+12% from last week</div>
          </div>

          <div class="stat-card">
            <div class="stat-header">
              <span class="stat-title">Revenue</span>
              <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/>
              </svg>
            </div>
            <div class="stat-value">$485</div>
            <div class="stat-change">+8% from last week</div>
          </div>

          <div class="stat-card">
            <div class="stat-header">
              <span class="stat-title">Rating</span>
              <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
              </svg>
            </div>
            <div class="stat-value">4.9</div>
            <div class="stat-change">Based on 24 reviews</div>
          </div>

          <div class="stat-card">
            <div class="stat-header">
              <span class="stat-title">Customers</span>
              <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
            </div>
            <div class="stat-value">12</div>
            <div class="stat-change">+3 new this week</div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-card">
          <div class="card-header">
            <h3>Quick Actions</h3>
            <p class="card-description">Manage your bakery with these shortcuts</p>
          </div>
          <div class="card-content">
            <div class="actions-grid">
              <a href="bakerproductmngmt.html" class="action-btn primary">
                <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <line x1="12" y1="5" x2="12" y2="19"/>
                  <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                <span>Add New Product</span>
              </a>
              <a href="#" class="action-btn">
                <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                  <line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/>
                  <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span>View Schedule</span>
              </a>
              <a href="#" class="action-btn">
                <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="22,12 18,12 15,21 9,3 6,12 2,12"/>
                </svg>
                <span>Analytics</span>
              </a>
            </div>
          </div>
        </div>

      <div class="sales-info">
        <!-- Recent Orders -->
        <div class="content-card">
          <div class="card-header">
            <h3>Recent Orders</h3>
            <p class="card-description">Your latest customer orders</p>
          </div>
          <div class="card-content">
            <div class="orders-list">
              <div class="order-item">
                <div class="order-info">
                  <h4>Emma Wilson</h4>
                  <p class="order-details">Chocolate Chip Cookies • 2 dozen</p>
                </div>
                <div class="order-meta">
                  <div class="order-due">Due: Today 2:00 PM</div>
                  <span class="status-badge status-progress">In Progress</span>
                </div>
              </div>

              <div class="order-item">
                <div class="order-info">
                  <h4>Mike Johnson</h4>
                  <p class="order-details">Birthday Cake • 1 cake</p>
                </div>
                <div class="order-meta">
                  <div class="order-due">Due: Tomorrow 10:00 AM</div>
                  <span class="status-badge status-pending">Pending</span>
                </div>
              </div>

              <div class="order-item">
                <div class="order-info">
                  <h4>Lisa Park</h4>
                  <p class="order-details">Sourdough Bread • 3 loaves</p>
                </div>
                <div class="order-meta">
                  <div class="order-due">Due: Today 4:00 PM</div>
                  <span class="status-badge status-ready">Ready</span>
                </div>
              </div>
            </div>
            <div style="margin-top: 34px;">
              <button class="view-btn" style="width: 100%;">View All Orders</button>
            </div>
          </div>
        </div>

        <!-- Profile Status -->
        <div class="content-card">
          <div class="card-header">
            <h3>Profile Status</h3>
            <p class="card-description">Complete your profile to attract more customers</p>
          </div>
          <div class="card-content">
            <div class="profile-items">
              <div class="profile-item">
                <span class="profile-label">Profile Photo</span>
                <span class="profile-badge badge-complete">Complete</span>
              </div>
              <div class="profile-item">
                <span class="profile-label">Bio (Description)</span>
                <span class="profile-badge badge-complete">Complete</span>
              </div>
              <div class="profile-item">
                <span class="profile-label">Product Gallery</span>
                <span class="profile-badge badge-partial">5 photos</span>
              </div>
              <div class="profile-item">
                <span class="profile-label">Business Hours</span>
                <span class="profile-badge badge-incomplete">Incomplete</span>
              </div>
              <div class="profile-item">
                <span class="profile-label">Contact Information</span>
                <span class="profile-badge badge-complete">Complete</span>
              </div>
            </div>
            <div style="margin-top: 24px;">
              <button class="view-btn" style="width: 100%;">Update Profile</button>
            </div>
          </div>
        </div>
      </div>
      </div>
    </main>
  </body>
</html>
