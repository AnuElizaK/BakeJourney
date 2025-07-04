<?php 
session_start();
include 'db.php'; 

$user_id = $_SESSION['user_id']; 

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, email, phone, city FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Profile | BakeJourney</title>
    <meta name="description" content="Manage your customer profile and orders" />
    <link rel="stylesheet" href="customerprofile.css" />
  </head>

  <body>
    <div class="container">
      <a href="customerdashboard.php" class="back-link">← Back to Home</a>
      
      <h1 class="page-title">Your Profile</h1>
      <div class="customer-data">
        <!-- Profile Header -->
        <div class="profile-header">
         <div class="profile-avatar">
        <?php
           $name = $_SESSION['name'] ;
           $parts = explode(' ', $name);
           $initials = strtoupper($parts[0][0] . ($parts[1][0] ?? ''));
           echo $initials;
        ?>
         </div>

          <h1 class="profile-name"> <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
          <p class="profile-contact"><?php echo htmlspecialchars($_SESSION['email']); ?> • 
                                     <?php echo htmlspecialchars($_SESSION['phone']); ?> <br> 
                                     <?php echo htmlspecialchars($_SESSION['created_at']); ?>
          </p>
          <p> </p>
          <div class="profile-stats">
            <div class="stat-card">
              <span class="stat-number">12</span>
              <span class="stat-label">Total Orders</span>
            </div>
            <div class="stat-card">
              <span class="stat-number">3</span>
              <span class="stat-label">Favorite Bakers</span>
            </div>
            <div class="stat-card">
              <span class="stat-number">$245</span>
              <span class="stat-label">Total Spent</span>
            </div>
          </div>
        </div>

        <!-- Personal Information -->
        <div class="profile-section">
          <h2 class="section-title">Personal Information</h2>
          <form method="post">
            <div class="form-grid">
              <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" id="full_name" placeholder="Enter your full name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
              </div>
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" placeholder="Enter your email address" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
              </div>
              <div class="form-group">
                <div class="phone-label-row">
                  <label for="phone">Phone Number</label>
                  <div class="add-more-phones">
                    <button class="btn-more" type="button">+</button>
                  </div>
                </div>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($user['phone']); ?>">
              </div>
              <div class="form-group">
                <div class="address-label-row">
                  <label for="address">Delivery Address</label>
                  <div class="add-more-addresses">
                    <button class="btn-more" type="button">+</button>
                  </div>
                </div>
                <textarea id="address" name="address" rows="3" placeholder="Enter your full delivery address"><?php echo htmlspecialchars($user['city']); ?></textarea>
              </div>
            </div>
            <button type="submit" class="btn">Update Profile</button>
          </form>
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="profile-section">
        <h2 class="section-title">Recent Orders</h2>
        
        <div class="order-card">
          <div class="order-info">
            <h4>Custom Birthday Cake from Sarah Johnson</h4>
            <p>Order #1234 • December 1, 2024 • $45.00</p>
          </div>
          <span class="order-status completed">Completed</span>
        </div>

        <div class="order-card">
          <div class="order-info">
            <h4>French Pastries from Mike Chen</h4>
            <p>Order #1235 • December 3, 2024 • $18.00</p>
          </div>
          <span class="order-status in-progress">In Progress</span>
        </div>

        <div class="order-card">
          <div class="order-info">
            <h4>Gluten-Free Cookies from Emma Williams</h4>
            <p>Order #1236 • December 5, 2024 • $15.00</p>
          </div>
          <span class="order-status pending">Pending</span>
        </div>

        <button class="btn secondary">View All Orders</button>
      </div>

      <!-- Preferences -->
      <div class="profile-section">
        <h2 class="section-title">Preferences</h2>
        <form>
          <div class="form-grid">
            <div class="form-group">
              <label for="dietary">Dietary Restrictions (Press Ctrl to select multiple)</label>
              <select id="dietary" name="dietary" multiple>
                <option value="">None</option>
                <option value="gluten-free">Gluten-Free</option>
                <option value="vegan">Vegan</option>
                <option value="nut-free">Nut-Free</option>
                <option value="dairy-free">Dairy-Free</option>
              </select>
            </div>
            <div class="form-group">
              <label for="notifications">Email Notifications</label>
              <select id="notifications" name="notifications">
                <option value="all">All notifications</option>
                <option value="orders">Order updates only</option>
                <option value="none">None</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn">Save Preferences</button>
        </form>
      </div>
    </div>

    <script>
      // Form submission handlers
      document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          alert('Profile updated successfully! (This is a demo)');
        });
      });
    </script>
  </body>
</html>