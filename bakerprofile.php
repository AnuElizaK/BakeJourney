<?php 
session_start();
include 'db.php'; 

$user_id = $_SESSION['user_id']; 

// Fetch user details
$stmt = $conn->prepare(
  "SELECT full_name, email, phone, city, bio, brand_name, specialty 
  FROM users, bakers 
  WHERE users.user_id = bakers.user_id AND users.user_id = ?");
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
    <title>Baker Profile | BakeJourney</title>
    <meta name="description" content="Manage your baker profile and showcase your specialties" />
    <link rel="stylesheet" href="bakerprofile.css" />
  </head>

  <body>
    <div class="container">
      <a href="bakerdashboard.php" class="back-link">← Back to Home</a>

      <h1 class="page-title">Baker Profile</h1>
      <div class="baker-data">
        <!-- Profile Header -->
        <div class="profile-header">
          <div class="profile-avatar">
           <?php
           $name = $_SESSION['name'] ;
           $parts = explode(' ', $name);
           $initials = strtoupper($parts[0][0] . ($parts[1][0] ?? ''));
           echo $initials;
        ?>
            <div class="ranking-badge">#1 Baker</div>
          </div>
          
          <div class="profile-info">
            <h1><?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            <p><?php echo htmlspecialchars($_SESSION['email']); ?><br>
              Created on <?php echo htmlspecialchars($_SESSION['created_at']); ?></p>
            <div class="baker-rating">
              <div class="stars">
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
              </div>
              <span class="rating-number">5.0 (127 reviews)</span>
            </div>
            <p><strong>Specialty:</strong> <?php echo htmlspecialchars($user['specialty']); ?></p>
          </div>

          <div class="profile-stats">
            <div class="stat-card">
              <span class="stat-number">200+</span>
              <span class="stat-label">Orders Completed</span>
            </div>
            <div class="stat-card">
              <span class="stat-number">98%</span>
              <span class="stat-label">Customer Satisfaction</span>
            </div>
          </div>
        </div>

        <!-- Baker Information -->
        <div class="profile-section">
          <h2 class="section-title">Baker Information</h2>
          <form method="post">
            <div class="form-grid">
              <div class="form-group">
                <label for="fullName">Brand Name</label>
                <input type="text" id="brand_name" name="brand_name" value="<?php echo htmlspecialchars($user['brand_name']); ?>">
              </div>
              <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
              </div>
              
              <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
              </div>
               <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>">
              </div>
              <div class="form-group">
                <label for="specialty">Baking Specialty</label>
                <select id="specialty" name="specialty">
                  <option value="breads" <?php echo ($user['specialty'] == 'breads') ? 'selected' : ''; ?>>Artisan Breads & Sourdoughs</option>
                  <option value="cakes" <?php echo ($user['specialty'] == 'cakes') ? 'selected' : ''; ?>>Custom Cakes & Pastries</option>
                  <option value="gluten-free" <?php echo ($user['specialty'] == 'gluten-free') ? 'selected' : ''; ?>>Gluten-Free Treats</option>
                  <option value="desserts" <?php echo ($user['specialty'] == 'desserts') ? 'selected' : ''; ?>>Desserts & Sweets</option>
                  <option value="cookies" <?php echo ($user['specialty'] == 'cookies') ? 'selected' : ''; ?>>Cookies & Biscuits</option>
                  <option value="pies" <?php echo ($user['specialty'] == 'pies') ? 'selected' : ''; ?>>Pies & Tarts</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="form-group">
                <label for="bio">Baker Bio</label>
                <textarea id="bio" name="bio" rows="2" placeholder="Tell us a little about yourself"><?php echo htmlspecialchars($user['bio']);?></textarea>
              </div>
            </div>
            <button type="submit" name="bkupdate" class="btn">Update Profile</button>
          </form>
        </div>
      </div>

      <!-- Business Settings -->
      <div class="profile-section">
        <h2 class="section-title">Business Settings</h2>
        <form>
          <div class="form-grid">
            <div class="form-group">
              <label for="leadTime">Order Lead Time (days)</label>
              <select id="leadTime" name="leadTime">
                <option value="1">1 day</option>
                <option value="2" selected>2-3 days</option>
                <option value="4">4-5 days</option>
                <option value="7">1 week</option>
                <option value="14">2 weeks</option>
                <option value="30">1 month</option>
                <option value="-">More than 1 month</option>
              </select>
            </div>
            <div class="form-group">
              <label for="availability">Availability Status</label>
              <select id="availability" name="availability">
                <option value="available" selected>Available for orders</option>
                <option value="busy">Busy - limited availability</option>
                <option value="unavailable">Temporarily unavailable</option>
              </select>
            </div>
            <div class="form-group">
              <label for="custom">Custom orders</label>
              <select id="custom" name="custom">
                <option value="available" selected>Takes custom orders</option>
                <option value="busy">Takes limited custom orders</option>
                <option value="unavailable">Temporarily unavailable</option>
                <option value="unavailable">Does not take custom orders</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn">Save Settings</button>
        </form>
      </div>

      <!-- Change Password -->
      <div class="profile-section">
        <h2 class="section-title">Change Password</h2>
        <form method="post" onsubmit="return data()">
          <div class="form-grid">
            <div class="form-group password-group">
              <label for="newPassword">New password</label>
              <div class="password-input-wrapper">
                <input type="password" id="newPassword" name="password" placeholder="Enter new password">
                <button type="button" class="password-toggle" onclick="togglePassword('newPassword')">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                </button>
              </div>
            </div>
            <div class="form-group password-group">
              <label for="confirmPassword">Confirm</label>
              <div class="password-input-wrapper">
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
                <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <button type="submit" name="chngpwd" class="btn">Change Password</button>
        </form>
      </div>

      <!-- Delete Account -->
      <div class="profile-section">
        <h2 class="section-title">Delete Account</h2>
        <p class="warning">This action is irreversible. Please proceed with caution. Once deleted, your account details cannot be recovered.</p>
        <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account?');">
          <button type="submit" name="delete_account" class="btn danger">
            <svg class="action-btn" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
          Delete Account
        </button>
        </form>
      </div>
    </div>

    <script>
       //update form validation
      function data() {    
        const phone = document.getElementById('phone').value;
         const pwd = document.getElementById('newPassword').value;
        const conpwd=document.getElementById('confirmPassword').value;

         if (pwd.length < 8) {
        alert("Password should be at least 8 characters long");
        return false;
      }
        if(pwd!==conpwd){
          alert("Passwords do not match")
          return false;
        }
        return true;
  
        if (phone.length !== 10 || isNaN(phone)) {
          alert("Phone number should be a 10-digit number");
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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bkupdate'])) {
    $updated_name = $_POST['full_name'];
    $updated_phone = $_POST['phone'];
    $updated_bio = $_POST['bio'];
    $updated_address = $_POST['city'];
    $updated_brand = $_POST['brand_name'];
    $updated_specialty = $_POST['specialty'];

    // Update query
    $stmt = $conn->prepare("UPDATE users, bakers SET full_name = ?, phone = ?, bio = ?, city = ?, brand_name = ?, specialty = ? WHERE users.user_id = bakers.user_id AND users.user_id = ?");
    $stmt->bind_param("ssssssi", $updated_name, $updated_phone, $updated_bio, $updated_address, $updated_brand, $updated_specialty, $user_id);

    if ($stmt->execute()) {
        // Update session name so it's reflected immediately
        $_SESSION['name'] = $updated_name;
        echo "<script>alert('✅ Profile updated successfully!'); window.location.href = 'bakerprofile.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to update profile. Please try again.');</script>";
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chngpwd'])) {
  $updated_pwd = $_POST['password'];
  // hash the password
   $hashedPassword = password_hash($updated_pwd, PASSWORD_DEFAULT);

  $stmt=$conn->prepare("UPDATE users SET password=? where user_id=?");
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