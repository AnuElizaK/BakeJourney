<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Join as a Baker | BakeJourney</title>
  <meta name="description" content="Join our community of talented homebakers and showcase your delicious creations." />
  <link rel="stylesheet" href="bakersignup.css">
</head>

</head>

<body>
  <div class="overlay"></div>

  <div class="dialog">
    <button class="close-button" onclick="window.location.href='index.php'">×</button>

    <div class="dialog-header">
      <div class="logo-icon">
        <img src="media/LogoOpp.png" alt="BakeJourney Logo" width="40" height="40" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="logo-image">
      </div>
      <h1 class="brand-name">BakeJourney</h1>
      <h1>Join Our Baker Community</h1>
      <p>Your baking journey begins here.</p>
    </div>

    <div class="dialog-content">
      <form method="POST" action="bakersignup.php"  enctype="multipart/form-data">
        <div class="form-row">

          <div class="form-group">
            <label for="fullName">Full Name</label>
            <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
            <div id="nameError" class="error"></div>
          </div>

          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" maxlength="10" required>
            <div id="phoneError" class="error"></div>
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="Enter your email address" required>
          <div id="emailError" class="error"></div>
        </div>

        <div class="form-group">
          <label for="brandName">Brand Name</label>
          <input type="text" id="brand_name" name="brand_name" placeholder="Enter your brand name">
        </div>

        <div class="form-group">
          <label for="brandReg">Proof of Brand Registration</label>
          <input type="file" id="brand_reg" name="brand_proof" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <div class="form-group">
          <label for="location">Location</label>
          <input type="text" id="location" name="city" placeholder="Enter your location" required>
        </div>

        <div class="form-group">
          <label for="identity">Proof of Identity</label>
          <input type="file" id="identity" name="identity_proof" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>

        <div class="form-group">
          <label for="specialty">Baking Specialty</label>
          <select id="specialty" name="specialty">
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
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>
            </div>
              <div id="passwordError" class="error"></div>
          </div>
          <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <div class="password-group">
              <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password"
                required>
              <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                  <circle cx="12" cy="12" r="3" />
                </svg>
              </button>            
            </div>
             <div id="confirmPasswordError" class="error"></div>
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
    <div class="alert-overlay" id="alertOverlay"></div>
        <div class="custom-alert" id="customAlert">
          <h3 id="alertTitle"></h3>
            <p id="alertMessage"></p>
              <button class="alert-button" onclick="closeAlert()">OK</button>
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

//-----------FROM VALIDATION ---------------------
    // Global validation state
  let isValid = {
  name: false,
  phone: false,
  email: false,
  password: false,
  confirmPassword: false
};

// Full Name validation
document.getElementById("full_name").oninput = function() {
  const error = document.getElementById("nameError");
  const value = this.value.trim();

  if (value === "") {
    error.textContent = "";
    isValid.name = false;
  } else if (!/^[a-zA-Z\s]+$/.test(value)) {
    error.textContent = "Full name should only contain letters and spaces";
    isValid.name = false;
  } else {
    error.textContent = "";
    isValid.name = true;
  }
};

// Phone validation
document.getElementById("phone").oninput = function() {
  const error = document.getElementById("phoneError");
  const value = this.value.trim();

  if (value === "") {
    error.textContent = "";
    isValid.phone = false;
  } else if (value.length !== 10 || value.length <10) {
    error.textContent = "Please enter a valid 10-digit phone number";
    isValid.phone = false;
  } else {
    error.textContent = "";
    isValid.phone = true;
  }
};

// Email validation
document.getElementById("email").oninput = function() {
  const error = document.getElementById("emailError");
  const value = this.value.trim();
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (value === "") {
    error.textContent = "Email is required";
    isValid.email = false;
  } else if (!emailRegex.test(value)) {
    error.textContent = "Please enter a valid email address";
    isValid.email = false;
  } else {
    error.textContent = "";
    isValid.email = true;
  }
};

// Password validation
document.getElementById("password").oninput = function() {
  const error = document.getElementById("passwordError");

  const value = this.value;
  
  if (value.length < 8) {
    error.textContent = "Password must be at least 8 characters long";
    isValid.password = false;
  }  else {
    error.textContent = "";
    isValid.password = true;
  }
  validateConfirmPassword();
};

// Confirm Password validation
document.getElementById("confirmPassword").oninput = validateConfirmPassword;

function validateConfirmPassword() {
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirmPassword").value;
  const error = document.getElementById("confirmPasswordError");

  if (confirmPassword === "") {
    error.textContent = "";
    isValid.confirmPassword = false;
  } else if (confirmPassword !== password) {
    error.textContent = "Passwords do not match";
    isValid.confirmPassword = false;
  } else {
    error.textContent = "";
    isValid.confirmPassword = true;
  }
}

// Form submission validation
document.querySelector('form').onsubmit = function(e) {
  // Check if all validations pass
  if (!Object.values(isValid).every(Boolean)) {
    e.preventDefault();
    showAlert("Please fix all errors before submitting the form");
    return false;
  }
  
  // Check terms checkbox
  if (!document.getElementById("terms").checked) {
    e.preventDefault();
    showAlert("Please accept the Terms of Service and Privacy Policy");
    return false;
  }
  
  return true;
};

//Alert functions
function showAlert(title, message, type = 'error') {
  const alertEl = document.getElementById('customAlert');
  const overlayEl = document.getElementById('alertOverlay');
  const titleEl = document.getElementById('alertTitle');
  const messageEl = document.getElementById('alertMessage');

  alertEl.classList.remove('alert-error', 'alert-success');
  alertEl.classList.add(`alert-${type}`);

  titleEl.textContent = title;
  messageEl.textContent = message;

  alertEl.style.display = 'block';
  overlayEl.style.display = 'block';
}

function closeAlert() {
  const alertEl = document.getElementById('customAlert');
  const overlayEl = document.getElementById('alertOverlay');
  
  alertEl.style.display = 'none';
  overlayEl.style.display = 'none';
}

</script>

  <?php

  include 'db.php';

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bcreate'])) {
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
      echo "<script>showAlert('Email already exists. Please log in.'); </script>";
    } else {
      // Insert user
      $stmt = $conn->prepare("INSERT INTO users (full_name,phone, email, password,city, role) VALUES (?,?,?,?,?,?)");
      $stmt->bind_param("ssssss", $full_name, $phone, $email, $hashedPassword, $city, $role);

      if ($stmt->execute()) {
        // Get the user ID of the newly created user
        $user_id = $conn->insert_id;

        // Get additional baker details
        $brand_name = $_POST['brand_name'];
        $specialty = $_POST['specialty'];

        // Handle uploaded files
        $brandRegFile = $_FILES['brand_proof']['name'];
        $identityFile = $_FILES['identity_proof']['name'];

        $uploadDir = "uploads/";
        $brandRegPath = $uploadDir . basename($brandRegFile);
        $identityPath = $uploadDir . basename($identityFile);

        // Create uploads directory if it doesn't exist
        if (!file_exists($uploadDir)) {
          mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file($_FILES['brand_proof']['tmp_name'], $brandRegPath);
        move_uploaded_file($_FILES['identity_proof']['tmp_name'], $identityPath);

        // Insert into bakers table
        $bakerStmt = $conn->prepare("INSERT INTO bakers (user_id, brand_name, brand_proof, identity_proof, specialty) VALUES (?, ?, ?, ?, ?)");
        $bakerStmt->bind_param("issss", $user_id, $brand_name, $brandRegFile, $identityFile, $specialty);
        $bakerStmt->execute();

        // Set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['name'] = $full_name;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['city'] = $city;
        $_SESSION['created_at'] = date('F Y');
        $_SESSION['role'] = $role;
        $_SESSION['brand_name'] = $brand_name;
        $_SESSION['specialty'] = $specialty;


        // Redirect to dashboard
        echo "<script>showAlert('Success!', 'Account created successfully!', 'success'); 
          setTimeout(() => window.location.href = 'bakerdashboard.php', 2000);</script>";
  } else {
       echo "<script>showAlert('Error', 'Error creating account: " . $stmt->error . "', 'error');</script>";
      }
    }
  }
  ?>
</body>

</html>