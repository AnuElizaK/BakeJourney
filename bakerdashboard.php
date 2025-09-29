<?php
session_start();
include 'db.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'baker') {
  header("Location: index.php"); // Redirect to login if not authorized
  exit();
}

$baker_id = $_SESSION['user_id'];

// Overall Stats
$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_orders 
    FROM orders 
    WHERE baker_id = ? 
      AND order_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$orders_week = $stmt->get_result()->fetch_assoc()['total_orders'];

$stmt = $conn->prepare("
    SELECT IFNULL(SUM(total_amount), 0) AS total_revenue 
    FROM orders 
    WHERE baker_id = ? AND payment_status = 'success'
");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$revenue = $stmt->get_result()->fetch_assoc()['total_revenue'];

$stmt = $conn->prepare("
    SELECT IFNULL(ROUND(AVG(rating),1), 0) AS avg_rating, COUNT(*) AS total_reviews 
    FROM baker_reviews
    WHERE baker_id = ?
");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$rating_data = $stmt->get_result()->fetch_assoc();
$avg_rating = $rating_data['avg_rating'];
$total_reviews = $rating_data['total_reviews'];

$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT customer_id) AS total_customers
    FROM orders 
    WHERE baker_id = ?
");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$total_customers = $stmt->get_result()->fetch_assoc()['total_customers'];


// Profile Status
// Check Profile Photo
$stmt = $conn->prepare("SELECT profile_image FROM users WHERE user_id = ?");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$profile_pic = $stmt->get_result()->fetch_assoc()['profile_image'];
$has_profile_image = !empty($profile_pic) && $profile_pic !== 'default.jpg';

// Check Bio (Description)
$stmt = $conn->prepare("SELECT bio FROM users WHERE user_id = ?");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$bio = $stmt->get_result()->fetch_assoc()['bio'];
$has_bio = !empty($bio) && strlen(trim($bio)) > 0;

// Check Product Gallery (count products with images)
$stmt = $conn->prepare("SELECT COUNT(*) as photo_count FROM products WHERE baker_id = ? AND image IS NOT NULL AND image != ''");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$product_photo_count = $stmt->get_result()->fetch_assoc()['photo_count'];

// Check Business Information
$stmt = $conn->prepare("SELECT experience, order_lead_time, availability, custom_orders FROM bakers WHERE user_id = ?");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$business_info = $stmt->get_result()->fetch_assoc();
$has_business_info = 
    !empty($business_info['experience']) && strlen(trim($business_info['experience'])) > 0 &&
    !empty($business_info['order_lead_time']) && strlen(trim($business_info['order_lead_time'])) > 0 &&
    !empty($business_info['availability']) && strlen(trim($business_info['availability'])) > 0 &&
    !empty($business_info['custom_orders']) && strlen(trim($business_info['custom_orders'])) > 0;

// Check Contact Information (only phone number as email is mandatory at signup)
$stmt = $conn->prepare("SELECT phone FROM users WHERE user_id = ?");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$phone = $stmt->get_result()->fetch_assoc()['phone'];
$has_contact_info = !empty($phone) && strlen(trim($phone)) > 0;


// Orders preview
$orders_fetch = "
    SELECT o.order_id, o.order_status, o.delivery_date,
           oi.quantity, 
           p.name as prod_name,
           u.full_name as cust_name
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    JOIN users u ON o.customer_id = u.user_id
    WHERE o.baker_id = ?
    ORDER BY o.order_date DESC
    LIMIT 3
";

$stmt = $conn->prepare($orders_fetch);
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$orders_result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Baker Dashboard | BakeJourney</title>
  <meta name="description" content="Baker dashboard for managing orders, products, and profile" />
  <link rel="stylesheet" href="bakerdashboard.css" />
</head>

<?php include 'bakernavbar.php'; ?>

<body>
  <!-- Hero Section -->
  <section class="bhero" id="home">
    <div class="bhero-overlay"></div>
    <div class="bhero-content">
      <div class="bhero-icon">
        <img src="media/Logo.png" alt="BakeJourney Logo" width="40" height="40" class="logo-image">
      </div>

      <h1 class="bhero-title">
        BakeJourney
        <span class="bhero-subtitle">The Home Baker's Marketplace</span>
      </h1>

      <p class="bhero-description">
        Hey there, <?php echo htmlspecialchars($_SESSION['name']); ?>! Welcome to your one-stop digital toolkit for
        all things baking.
      </p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="main">
    <div class="container">
      <!-- Quick Stats -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Orders This Week</span>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
              <line x1="16" y1="2" x2="16" y2="6" />
              <line x1="8" y1="2" x2="8" y2="6" />
              <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
          </div>
          <div class="stat-value"><?= $orders_week ?></div>
          <div class="stat-change">+5% increase</div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Revenue</span>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" />
            </svg>
          </div>
          <div class="stat-value">₹<?= number_format($revenue, 2) ?></div>
          <div class="stat-change">+8% increase</div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Rating</span>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polygon
                points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" />
            </svg>
          </div>
          <div class="stat-value"><?= $avg_rating ?></div>
          <div class="stat-change">Based on <?= $total_reviews ?> reviews</div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Customers</span>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>
          <div class="stat-value"><?= $total_customers ?></div>
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
            <a href="bakerproductmngmt.php" class="action-btn primary">
              <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
              </svg>
              <span class="action-title">Add New Product</span>
            </a>
            <a href="pagenotready.php" class="action-btn">
              <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
              </svg>
              <span class="action-title">View Schedule</span>
            </a>
            <a href="pagenotready.php" class="action-btn">
              <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" />
              </svg>
              <span class="action-title">Analytics</span>
            </a>
          </div>
        </div>
      </div>

      <div class="sales-info">
        <!-- Recent Orders -->
        <?php while ($order = $orders_result->fetch_assoc()): ?>
          <div class="content-card">
            <?php if ($order == null): ?>
              <div id="no-orders-message"
                style="display:none; text-align:center; color:#f59e0b; font-weight:600; margin:32px 0;">
                No recent orders found.
              </div>
            <?php else: ?>
              <div class="card-header">
                <h3>Recent Orders</h3>
                <p class="card-description">Your latest customer orders</p>
              </div>
              <div class="card-content">
                <div class="orders-list">
                  <div class="order-item"
                    onclick="window.location.href='bakerordermngmt.php?order_id=<?= $order['order_id']; ?>'">
                    <div class="order-info">
                      <h4><?= htmlspecialchars($order['cust_name']) ?></h4>
                      <p class="order-details"><?= htmlspecialchars($order['prod_name']) ?> •
                        (<?= htmlspecialchars($order['quantity']) ?>)
                      </p>
                    </div>
                    <div class="order-meta">
                      <div class="order-due">Due → <?= htmlspecialchars($order['delivery_date']) ?></div>
                      <span
                        class="status-badge status-<?= htmlspecialchars($order['order_status']) ?>"><?= htmlspecialchars($order['order_status']) ?></span>
                    </div>
                  </div>
                </div>
                <div style="margin-top: auto; padding-top: 34px;">
                  <button class="view-btn" style="width: 100%;" onclick="window.location.href='bakerordermngmt.php'">View
                    All Orders</button>
                </div>
              </div>
            </div>
          <?php endif; ?>
        <?php endwhile; ?>

        <!-- Profile Status -->
        <div class="content-card">
          <div class="card-header">
            <h3>Profile Status</h3>
            <p class="card-description">Keep your profile updated to attract more customers</p>
          </div>
          <div class="card-content">
            <div class="profile-items">
              <div class="profile-item">
                <span class="profile-label">Profile Photo</span>
                <span class="profile-badge <?= $has_profile_image ? 'badge-complete' : 'badge-incomplete' ?>">
                  <?= $has_profile_image ? 'Complete' : 'Incomplete' ?>
                </span>
              </div>
              <div class="profile-item">
                <span class="profile-label">Bio (Description)</span>
                <span class="profile-badge <?= $has_bio ? 'badge-complete' : 'badge-incomplete' ?>">
                  <?= $has_bio ? 'Complete' : 'Incomplete' ?>
                </span>
              </div>
              <div class="profile-item">
                <span class="profile-label">Product Gallery</span>
                <span class="profile-badge <?= $product_photo_count > 0 ? 'badge-partial' : 'badge-incomplete' ?>">
                  <?= $product_photo_count > 0 ? $product_photo_count . ' photo' . ($product_photo_count > 1 ? 's' : '') : 'Incomplete' ?>
                </span>
              </div>
              <div class="profile-item">
                <span class="profile-label">Business Information</span>
                <span class="profile-badge <?= $has_business_info ? 'badge-complete' : 'badge-incomplete' ?>">
                  <?= $has_business_info ? 'Complete' : 'Incomplete' ?>
                </span>
              </div>
              <div class="profile-item">
                <span class="profile-label">Contact Information</span>
                <span class="profile-badge <?= $has_contact_info ? 'badge-complete' : 'badge-incomplete' ?>">
                  <?= $has_contact_info ? 'Complete' : 'Incomplete' ?>
                </span>
              </div>
            </div>
            <div style="margin-top: auto; padding-top: 24px;">
              <button class="view-btn" style="width: 100%;" onclick="window.location.href='bakerprofile.php'">Update
                Profile</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <?php include 'globalfooter.php'; ?>
</body>

</html>