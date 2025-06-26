<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Join as a Baker | BakeJouney</title>
    <meta name="description" content="Join our community of talented homebakers and showcase your delicious creations." />
    <link rel="stylesheet" href="bakersignup.css">
   </head>

   </head>

  <body>
    <div class="overlay"></div>
    
    <div class="dialog">
      <button class="close-button" onclick="window.history.back()">×</button>
      
      <div class="dialog-header">
        <div class="logo-icon">
          <img src="media/LogoOpp.png" alt="BakeJourney Logo" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="logo-image">
        </div>
        <h1 class="brand-name">BakeJourney</h1>
        <h1>Join Our Baker Community</h1>
        <p>Your baking journey begins here.</p>
      </div>

      <div class="dialog-content">
        <form method="POST" action="bakersignup.php"  onsubmit="return data()" >
          <div class="form-row">
            <div class="form-group">
              <label for="fullName">Full Name</label>
              <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required>
          </div>

          <div class="form-group">
              <label for="brandName">Brand Name</label>
              <input type="text" id="brandName" name="brandName" placeholder="Enter your brand name">
          </div>

          <div class="form-group">
              <label for="brandReg">Proof of Brand Registration</label>
              <input type="file" id="brandReg" name="brandReg" accept=".pdf,.jpg,.jpeg,.png" >
          </div>

          <div class="form-group">
              <label for="location">Location</label>
              <input type="text" id="location" name="city" placeholder="Enter your location" required>
          </div>

          <div class="form-group">
              <label for="identity">Proof of Identity</label>
              <input type="file" id="identity" name="identity" accept=".pdf,.jpg,.jpeg,.png" >
          </div>

          <div class="form-group">
            <label for="specialty">Baking Specialty</label>
            <select id="specialty" name="specialty" >
              <option value="">Select your specialty</option>
              <option value="breads">Artisan Breads & Sourdoughs</option>
              <option value="cakes">Custom Cakes & Pastries</option>
              <option value="gluten-free">Gluten-Free Treats</option>
              <option value="desserts">Desserts & Sweets</option>
              <option value="cookies">Cookies & Biscuits</option>
              <option value="pies">Pies & Tarts</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="password">Password</label>
              <div class="password-group">
                <input type="password" id="password" name="password" placeholder="Create password" required>
                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                </button>
              </div>
            </div>
            <div class="form-group">
              <label for="confirmPassword">Confirm Password</label>
              <div class="password-group">
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required>
                <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div class="checkbox-group">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
          </div>

          <button type="submit" class="btn" name="bcreate">Create Baker Account</button>
        </form>

        <div class="login-link">
          Already have an account? <a href="login.php">Log in.</a>
        </div>
      </div>
    </div>
<!-- ================================================================================================================================= -->
    <script>
      // Toggle password visibility
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
 // Validate form data
      function data(){
        var ph=document.getElementById("phone").value;
        var pass=document.getElementById("password").value;
        var conpass=document.getElementById("confirmPassword").value;
        var terms=document.getElementById("terms").checked;
        if(ph.length<10 || isNaN(ph)){
          alert("Phone number should be a 10-digit number");
          return false;
        }
        if(pass.length<8){
          alert("Password should be at least 8 characters long");
          return false;
        }
        if(pass !== conpass){
          alert("Passwords do not match");
          return false;
        }
        if(!terms){
          alert("Please agree to the terms and conditions");
          return false;
        }
        return true;
      }
      // Close dialog when clicking overlay
      document.querySelector('.overlay').addEventListener('click', function() {
        window.history.back();
      });
    </script>

<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bcreate']))
{
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $city = $_POST['city'];
    $role = 'baker';

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Email already exists. Please log in.'); window.location.href = 'login.php';</script>";
    } else {
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (full_name,phone, email, password,city, role) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $full_name,$phone, $email, $hashedPassword,$city, $role);

        if ($stmt->execute()) {
            echo "<script>alert('🎉 Account created successfully!'); window.location.href = 'bakerdashboard.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>
  </body>
</html>