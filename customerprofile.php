<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, email, phone, city, bio FROM users WHERE user_id = ?");
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

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
  <div class="container">
    <h1 class="page-title">Your Profile</h1>
    <div class="customer-data">
      <!-- Profile Header -->
      <div class="profile-header">
        <div class="profile-avatar">
          <?php
          $name = $_SESSION['name'];
          $parts = explode(' ', $name);
          $initials = strtoupper($parts[0][0] . ($parts[1][0] ?? ''));
          echo $initials;
          ?>
        </div>

        <h1 class="profile-name"> <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
        <p class="profile-contact"><?php echo htmlspecialchars($_SESSION['email']); ?> <br>
          Joined on <?php echo htmlspecialchars($_SESSION['created_at']); ?>
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
        <form method="post" onsubmit="return data()">
          <div class="form-grid">
            <div class="form-group">
              <label for="fullName">Full Name</label>
              <input type="text" id="full_name" name="full_name" placeholder="Enter your full name"
                value="<?php echo htmlspecialchars($user['full_name']); ?>">
            </div>

            <div class="form-group">
              <div class="phone-label-row">
                <label for="phone">Phone Number</label>
                <div class="add-more-phones">
                  <button class="btn-more" type="button">+</button>
                </div>
              </div>
              <input type="tel" id="phone" name="phone" placeholder="Enter your phone number"
                value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>
            <div class="form-group">
              <label for="bio">Bio</label>
              <textarea id="bio" name="bio" rows="3"
                placeholder="Tell us a little about yourself"><?php echo htmlspecialchars($user['bio']); ?></textarea>
            </div>
            <div class="form-group">
              <div class="address-label-row">
                <label for="address">Delivery Address</label>
                <div class="add-more-addresses">
                  <button class="btn-more" type="button">+</button>
                </div>
              </div>
              <textarea id="address" name="city" rows="3"
                placeholder="Enter your full delivery address"><?php echo htmlspecialchars($user['city']); ?></textarea>
            </div>
          </div>
          <button type="submit" name="update" class="btn">Update Profile</button>
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

    <!-- Change Password -->
    <div class="profile-section">
      <h2 class="section-title">Change Password</h2>
      <form method="post" onsubmit="return pwddata()">
        <div class="form-grid">
          <div class="form-group password-group">
            <label for="newPassword">New password</label>
            <div class="password-input-wrapper">
              <input type="password" id="newPassword" name="password" placeholder="Enter new password">
              <button type="button" class="password-toggle" onclick="togglePassword('newPassword')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
            </div>
          </div>
          <div class="form-group password-group">
            <label for="confirmPassword">Confirm</label>
            <div class="password-input-wrapper">
              <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
              <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
            </div>
          </div>

        </div>
        <button type="submit" name="changepwd" class="btn">Change Password</button>
      </form>
    </div>

    <!-- Delete Account -->
    <div class="profile-section">
      <h2 class="section-title">Delete Account</h2>
      <p class="warning">This action is irreversible. Please proceed with caution. Once deleted, your account details
        cannot be recovered.</p>
      <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account?');">
        <button type="submit" name="delete_account" class="btn danger">
          <svg class="action-btn" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
          Delete Account
        </button>
      </form>
    </div>
  </div>

  <?php include 'globalfooter.php'; ?>

  <script>
    //Update form validation
    function data() {
      const phone = document.getElementById('phone').value;
      if (phone.length !== 10 || isNaN(phone)) {
        alert("Please enter a valid 10-digit phone number");
        return false;
      }
      return true;
    }
    //Password update form validation
    function pwddata() {

      const pwd = document.getElementById('newPassword').value;
      const conpwd = document.getElementById('confirmPassword').value;

      if (pwd.length < 8) {
        alert("Password should be at least 8 characters long");
        return false;
      }
      if (pwd !== conpwd) {
        alert("Passwords do not match")
        return false;
      }
      return true;
    }

    function togglePassword(inputId) {
      const input = document.getElementById(inputId);
      const button = input.nextElementSibling;
      const icon = button.querySelector('svg');

      if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
      } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
      }
    }
  </script>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $updated_name = $_POST['full_name'];
    $updated_phone = $_POST['phone'];
    $updated_bio = $_POST['bio'];
    $updated_address = $_POST['city'];

    // Update query
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, bio = ?, city = ? WHERE user_id = ?");
    $stmt->bind_param("sissi", $updated_name, $updated_phone, $updated_bio, $updated_address, $user_id);

    if ($stmt->execute()) {
      // Update session name so it's reflected immediately
      $_SESSION['name'] = $updated_name;
      echo "<script>alert('✅ Profile updated successfully!'); window.location.href = 'customerprofile.php';</script>";
    } else {
      echo "<script>alert('❌ Failed to update profile. Please try again.');</script>";
    }
    $stmt->close();
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['changepwd'])) {
    $updated_pwd = $_POST['password'];
    // hash the password
    $hashedPassword = password_hash($updated_pwd, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password=? where user_id=?");
    $stmt->bind_param("si", $hashedPassword, $user_id);
    $stmt->execute();
    echo "<script>alert('✅ Password changed successfully!');</script>";
    $stmt->close();
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
      // Destroy session and redirect
      session_destroy();
      echo "<script>alert('✅ Your account has been deleted.'); window.location.href = 'index.php';</script>";
      exit();
    } else {
      echo "<script>alert('❌ Failed to delete account. Please try again.');</script>";
    }
    $stmt->close();
    $conn->close();

  }
  ?>
</body>

</html>