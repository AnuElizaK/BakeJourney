<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
     <title>Login | BakeJourney</title>
    <meta name="description" content="Sign in to your BakeJourney account" />
    <link rel="stylesheet" href="login.css" />
    </head>

   <body>
    <div class="overlay"></div>
    <div class="dialog">
      <button class="close-button" onclick="window.location.href='index.php'">×</button>
      
      <div class="dialog-header">
        <div class="logo-icon">
          <img src="media/LogoOpp.png" alt="BakeJourney Logo" width="40" height="40" class="logo-image">
        </div>
        <h1 class="brand-name">BakeJourney</h1>
        <p>Welcome Back!</p>
        <p>Sign in to your account.</p>
      </div>

      <div class="dialog-content">
        <form method="POST" action="login.php">
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <div class="password-group">
              <input type="password" id="password" name="password" placeholder="Enter your password" required>
              <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="form-options">
            <a href="#" class="forgot-password">Forgot password?</a>
          </div>

          <button type="submit" name="signin" class="btn">Sign In</button>
        </form>

        <div class="signup-link">
          New to BakeJourney? <a href="customersignup.php">Create an account.</a><br>
          If you are a baker, <a href="bakersignup.php">join us here.</a>
        </div>
      </div>
    </div>
    
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
    <script>
    
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

      // Force reload if loaded from back/forward cache
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
 });
 
    </script>

<!-- ----------- PHP Code for Login Functionality ------------- -->
<?php
session_start();
include 'db.php'; // connect to DB

// Check if user is already logged in
if (isset($_SESSION['email']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'baker') {
        header("Location: bakerdashboard.php");
        exit();
    } else if ($_SESSION['role'] === 'customer') {
        header("Location: customerdashboard.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signin'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Fetch user
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      // Set session variables for already existing users
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['name'] = $user['full_name'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['phone'] = $user['phone'];
      $_SESSION['state'] = $user['state'];
      $_SESSION['district'] = $user['district'];
      $_SESSION['created_at'] = date('F Y');
      $_SESSION['role'] = $user['role'];
      $_SESSION['brand_name'] = $user['brand_name'];
      $_SESSION['specialty'] = $user['specialty'];
      $_SESSION['address'] = $user['address'];
      $_SESSION['profile_image'] = $user['profile_image'];
      // Redirect based on role
      if ($user['role'] === 'baker') {
        header("Location: bakerdashboard.php");
      } else {
        header("Location: customerdashboard.php");
      }
      exit();
    } else {
      echo "<script>alert('Incorrect password.')</script>";
    }
  } else {
     echo "<script>alert('No account found with that email.')</script>";
  }
}
?>

  </body>
</html>
</html>   