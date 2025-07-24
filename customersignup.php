<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up | BakeJouney</title>
  <meta name="description" content="Join our community of talented homebakers and showcase your delicious creations." />
  <link rel="stylesheet" href="customersignup.css">
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
      <h1>Create Your Account</h1>
      <p>Your one-stop location for home-baked goodies.</p>
    </div>

    <div class="dialog-content">
      <form method="POST" action="customersignup.php">

        <div class="form-row">
          <div class="form-group">
            <label for="fullName">Full Name</label>
            <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
            <div id="nameError" class="error"></div>
          </div>
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
            <div id="phoneError" class="error"></div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required>
            <div id="emailError" class="error"></div>
          </div>

          <div class="form-group">
            <label for="state">State</label>
            <select id="state" name="state" required>
              <option value="">Select State</option>
            </select>
          </div>

          <div class="form-group">
            <label for="district">District</label>
            <select id="district" name="district" required>
              <option value="">Select District</option>
            </select>
          </div>
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

        <button type="submit" name="create" class="btn">Create Account</button>
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

  <!-- =============================================================================== -->
  <script>
    //state and district dropdowns
    const stateDistricts = {
      "Andhra Pradesh": ["Anantapur", "Chittoor", "East Godavari", "Guntur", "Krishna", "Kurnool", "Nandyal", "NTR", "Palnadu", "Prakasam", "SPS Nellore", "Srikakulam", "Visakhapatnam", "Vizianagaram", "West Godavari", "YSR Kadapa"],
      "Arunachal Pradesh": ["Anjaw", "Changlang", "Dibang Valley", "East Kameng", "East Siang", "Kamle", "Kra Daadi", "Kurung Kumey", "Lepa Rada", "Lohit", "Longding", "Lower Dibang Valley", "Lower Siang", "Lower Subansiri", "Namsai", "Pakke Kessang", "Papum Pare", "Shi Yomi", "Siang", "Tawang", "Tirap", "Upper Siang", "Upper Subansiri", "West Kameng", "West Siang"],
      "Assam": ["Baksa", "Barpeta", "Biswanath", "Bongaigaon", "Cachar", "Charaideo", "Chirang", "Darrang", "Dhemaji", "Dhubri", "Dibrugarh", "Goalpara", "Golaghat", "Hailakandi", "Hojai", "Jorhat", "Kamrup", "Kamrup Metropolitan", "Karbi Anglong", "Karimganj", "Kokrajhar", "Lakhimpur", "Majuli", "Morigaon", "Nagaon", "Nalbari", "Sivasagar", "Sonitpur", "South Salmara-Mankachar", "Tinsukia", "Udalguri", "West Karbi Anglong"],
      "Bihar": ["Araria", "Arwal", "Aurangabad", "Banka", "Begusarai", "Bhagalpur", "Bhojpur", "Buxar", "Darbhanga", "East Champaran", "Gaya", "Gopalganj", "Jamui", "Jehanabad", "Kaimur", "Katihar", "Khagaria", "Kishanganj", "Lakhisarai", "Madhepura", "Madhubani", "Munger", "Muzaffarpur", "Nalanda", "Nawada", "Patna", "Purnia", "Rohtas", "Saharsa", "Samastipur", "Saran", "Sheikhpura", "Sheohar", "Sitamarhi", "Siwan", "Supaul", "Vaishali", "West Champaran"],
      "Chhattisgarh": ["Balod", "Baloda Bazar", "Balrampur", "Bastar", "Bemetara", "Bijapur", "Bilaspur", "Dantewada", "Dhamtari", "Durg", "Gariaband", "Gaurela-Pendra-Marwahi", "Janjgir-Champa", "Jashpur", "Kabirdham", "Kanker", "Kondagaon", "Korba", "Koriya", "Mahasamund", "Mungeli", "Narayanpur", "Raigarh", "Raipur", "Rajnandgaon", "Sukma", "Surajpur", "Surguja"],
      "Goa": ["North Goa", "South Goa"],
      "Gujarat": ["Ahmedabad", "Amreli", "Anand", "Aravalli", "Banaskantha", "Bharuch", "Bhavnagar", "Botad", "Chhota Udaipur", "Dahod", "Dang", "Devbhoomi Dwarka", "Gandhinagar", "Gir Somnath", "Jamnagar", "Junagadh", "Kheda", "Kutch", "Mahisagar", "Mehsana", "Morbi", "Narmada", "Navsari", "Panchmahal", "Patan", "Porbandar", "Rajkot", "Sabarkantha", "Surat", "Surendranagar", "Tapi", "Vadodara", "Valsad"],
      "Haryana": ["Ambala", "Bhiwani", "Charkhi Dadri", "Faridabad", "Fatehabad", "Gurugram", "Hisar", "Jhajjar", "Jind", "Kaithal", "Karnal", "Kurukshetra", "Mahendragarh", "Nuh", "Palwal", "Panchkula", "Panipat", "Rewari", "Rohtak", "Sirsa", "Sonipat", "Yamunanagar"],
      "Himachal Pradesh": ["Bilaspur", "Chamba", "Hamirpur", "Kangra", "Kinnaur", "Kullu", "Lahaul and Spiti", "Mandi", "Shimla", "Sirmaur", "Solan", "Una"],
      "Jharkhand": ["Bokaro", "Chatra", "Deoghar", "Dhanbad", "Dumka", "East Singhbhum", "Garhwa", "Giridih", "Godda", "Gumla", "Hazaribagh", "Jamtara", "Khunti", "Koderma", "Latehar", "Lohardaga", "Pakur", "Palamu", "Ramgarh", "Ranchi", "Sahebganj", "Seraikela-Kharsawan", "Simdega", "West Singhbhum"],
      "Karnataka": ["Bagalkot", "Ballari", "Belagavi", "Bengaluru Rural", "Bengaluru Urban", "Bidar", "Chamarajanagar", "Chikballapur", "Chikkamagaluru", "Chitradurga", "Dakshina Kannada", "Davanagere", "Dharwad", "Gadag", "Hassan", "Haveri", "Kalaburagi", "Kodagu", "Kolar", "Koppal", "Mandya", "Mysuru", "Raichur", "Ramanagara", "Shivamogga", "Tumakuru", "Udupi", "Uttara Kannada", "Vijayapura", "Yadgir"],
      "Kerala": ["Alappuzha", "Ernakulam", "Idukki", "Kannur", "Kasaragod", "Kollam", "Kottayam", "Kozhikode", "Malappuram", "Palakkad", "Pathanamthitta", "Thiruvananthapuram", "Thrissur", "Wayanad"],
      "Madhya Pradesh": ["Agar Malwa", "Alirajpur", "Anuppur", "Ashoknagar", "Balaghat", "Barwani", "Betul", "Bhind", "Bhopal", "Burhanpur", "Chhatarpur", "Chhindwara", "Damoh", "Datia", "Dewas", "Dhar", "Dindori", "Guna", "Gwalior", "Harda", "Hoshangabad", "Indore", "Jabalpur", "Jhabua", "Katni", "Khandwa", "Khargone", "Mandla", "Mandsaur", "Morena", "Narsinghpur", "Neemuch", "Niwari", "Panna", "Raisen", "Rajgarh", "Ratlam", "Rewa", "Sagar", "Satna", "Sehore", "Seoni", "Shahdol", "Shajapur", "Sheopur", "Shivpuri", "Sidhi", "Singrauli", "Tikamgarh", "Ujjain", "Umaria", "Vidisha"],
      "Maharashtra": ["Ahmednagar", "Akola", "Amravati", "Aurangabad", "Beed", "Bhandara", "Buldhana", "Chandrapur", "Dhule", "Gadchiroli", "Gondia", "Hingoli", "Jalgaon", "Jalna", "Kolhapur", "Latur", "Mumbai City", "Mumbai Suburban", "Nagpur", "Nanded", "Nandurbar", "Nashik", "Osmanabad", "Palghar", "Parbhani", "Pune", "Raigad", "Ratnagiri", "Sangli", "Satara", "Sindhudurg", "Solapur", "Thane", "Wardha", "Washim", "Yavatmal"],
      "Manipur": ["Bishnupur", "Chandel", "Churachandpur", "Imphal East", "Imphal West", "Jiribam", "Kakching", "Kamjong", "Kangpokpi", "Noney", "Pherzawl", "Senapati", "Tamenglong", "Tengnoupal", "Thoubal", "Ukhrul"],
      "Meghalaya": ["East Garo Hills", "East Jaintia Hills", "East Khasi Hills", "North Garo Hills", "Ri-Bhoi", "South Garo Hills", "South West Garo Hills", "South West Khasi Hills", "West Garo Hills", "West Jaintia Hills", "West Khasi Hills", "Eastern West Khasi Hills"],
      "Mizoram": ["Aizawl", "Champhai", "Hnahthial", "Khawzawl", "Kolasib", "Lawngtlai", "Lunglei", "Mamit", "Saiha", "Saitual", "Serchhip"],
      "Nagaland": ["Chümoukedima", "Dimapur", "Kiphire", "Kohima", "Longleng", "Meluri", "Mokokchung", "Mon", "Niuland", "Noklak", "Peren", "Phek", "Shamator", "Tuensang", "Tseminyü", "Wokha", "Zünheboto"],
      "Odisha": ["Angul", "Balangir", "Balasore", "Bargarh", "Bhadrak", "Boudh", "Cuttack", "Debagarh", "Dhenkanal", "Gajapati", "Ganjam", "Jagatsinghpur", "Jajpur", "Jharsuguda", "Kalahandi", "Kandhamal", "Kendrapara", "Kendujhar", "Khordha", "Koraput", "Malkangiri", "Mayurbhanj", "Nabarangpur", "Nayagarh", "Nuapada", "Puri", "Rayagada", "Sambalpur", "Subarnapur", "Sundargarh"],
      "Punjab": ["Amritsar", "Barnala", "Bathinda", "Faridkot", "Fatehgarh Sahib", "Fazilka", "Firozpur", "Gurdaspur", "Hoshiarpur", "Jalandhar", "Kapurthala", "Ludhiana", "Malerkotla", "Mansa", "Moga", "Sri Muktsar Sahib", "Pathankot", "Patiala", "Rupnagar", "S.A.S Nagar", "Sangrur", "S.B.S Nagar", "Tarn Taran"],
      "Rajasthan": ["Ajmer", "Alwar", "Banswara", "Baran", "Barmer", "Bharatpur", "Bhilwara", "Bikaner", "Bundi", "Chittorgarh", "Churu", "Dausa", "Dholpur", "Dungarpur", "Ganganagar", "Hanumangarh", "Jaipur", "Jaisalmer", "Jalore", "Jhalawar", "Jhunjhunu", "Jodhpur", "Karauli", "Kota", "Nagaur", "Pali", "Pratapgarh", "Rajsamand", "Sawai Madhopur", "Sikar", "Sirohi", "Tonk", "Udaipur"],
      "Sikkim": ["Gangtok", "Mangan", "Namchi", "Gyalshing", "Pakyong", "Soreng"],
      "Tamil Nadu": ["Ariyalur", "Chengalpattu", "Chennai", "Coimbatore", "Cuddalore", "Dharmapuri", "Dindigul", "Erode", "Kallakurichi", "Kancheepuram", "Karur", "Krishnagiri", "Madurai", "Mayiladuthurai", "Nagapattinam", "Namakkal", "Nilgiris", "Perambalur", "Pudukkottai", "Ramanathapuram", "Ranipet", "Salem", "Sivaganga", "Tenkasi", "Thanjavur", "Theni", "Thoothukudi", "Tiruchirappalli", "Tirunelveli", "Tirupathur", "Tiruppur", "Tiruvallur", "Tiruvannamalai", "Tiruvarur", "Vellore", "Viluppuram", "Virudhunagar"],
      "Telangana": ["Adilabad", "Bhadradri Kothagudem", "Hyderabad", "Jagtial", "Jangaon", "Jayashankar Bhupalpally", "Jogulamba Gadwal", "Kamareddy", "Karimnagar", "Khammam", "Komaram Bheem", "Mahabubabad", "Mahabubnagar", "Mancherial", "Medak", "Medchal–Malkajgiri", "Mulugu", "Nagarkurnool", "Nalgonda", "Narayanpet", "Nirmal", "Nizamabad", "Peddapalli", "Rajanna Sircilla", "Ranga Reddy", "Sangareddy", "Siddipet", "Suryapet", "Vikarabad", "Wanaparthy", "Warangal", "Hanamkonda", "Yadadri Bhuvanagiri"],
      "Tripura": ["Dhalai", "Gomati", "Khowai", "North Tripura", "Sepahijala", "South Tripura", "Unakoti", "West Tripura"],
      "Uttar Pradesh": ["Agra", "Aligarh", "Ambedkar Nagar", "Amethi", "Amroha", "Auraiya", "Ayodhya", "Azamgarh", "Baghpat", "Bahraich", "Ballia", "Balrampur", "Banda", "Barabanki", "Bareilly", "Basti", "Bhadohi", "Bijnor", "Budaun", "Bulandshahr", "Chandauli", "Chitrakoot", "Deoria", "Etah", "Etawah", "Farrukhabad", "Fatehpur", "Firozabad", "Gautam Buddha Nagar", "Ghaziabad", "Ghazipur", "Gonda", "Gorakhpur", "Hamirpur", "Hapur", "Hardoi", "Hathras", "Jalaun", "Jaunpur", "Jhansi", "Kannauj", "Kanpur Dehat", "Kanpur Nagar", "Kasganj", "Kaushambi", "Kheri", "Kushinagar", "Lalitpur", "Lucknow", "Maharajganj", "Mahoba", "Mainpuri", "Mathura", "Mau", "Meerut", "Mirzapur", "Moradabad", "Muzaffarnagar", "Pilibhit", "Pratapgarh", "Prayagraj", "Raebareli", "Rampur", "Saharanpur", "Sambhal", "Sant Kabir Nagar", "Shahjahanpur", "Shamli", "Shravasti", "Siddharthnagar", "Sitapur", "Sonbhadra", "Sultanpur", "Unnao", "Varanasi"],
      "Uttarakhand": ["Almora", "Bageshwar", "Chamoli", "Champawat", "Dehradun", "Haridwar", "Nainital", "Pauri Garhwal", "Pithoragarh", "Rudraprayag", "Tehri Garhwal", "Udham Singh Nagar", "Uttarkashi"],
      "West Bengal": ["Alipurduar", "Bankura", "Birbhum", "Cooch Behar", "Dakshin Dinajpur", "Darjeeling", "Hooghly", "Howrah", "Jalpaiguri", "Jhargram", "Kalimpong", "Kolkata", "Malda", "Murshidabad", "Nadia", "North 24 Parganas", "Paschim Bardhaman", "Paschim Medinipur", "Purba Bardhaman", "Purba Medinipur", "Purulia", "South 24 Parganas", "Uttar Dinajpur"],
      "Andaman and Nicobar Islands": ["Nicobar", "North and Middle Andaman", "South Andaman"],
      "Chandigarh": ["Chandigarh"],
      "Dadra and Nagar Haveli and Daman and Diu": ["Dadra and Nagar Haveli", "Daman", "Diu"],
      "Delhi": ["Central Delhi", "East Delhi", "New Delhi", "North Delhi", "North East Delhi", "North West Delhi", "Shahdara", "South Delhi", "South East Delhi", "South West Delhi", "West Delhi"],
      "Jammu and Kashmir": ["Anantnag", "Bandipora", "Baramulla", "Budgam", "Doda", "Ganderbal", "Jammu", "Kathua", "Kishtwar", "Kulgam", "Kupwara", "Poonch", "Pulwama", "Rajouri", "Ramban", "Reasi", "Samba", "Shopian", "Srinagar", "Udhampur"],
      "Ladakh": ["Kargil", "Leh"],
      "Lakshadweep": ["Agatti", "Amini", "Andrott", "Bithra", "Chetlat", "Kavaratti", "Kadmat", "Kalpeni", "Kilthan", "Minicoy"],
      "Puducherry": ["Karaikal", "Mahe", "Puducherry", "Yanam"]
    };

    window.onload = function () {
      const stateSelect = document.getElementById("state");
      const districtSelect = document.getElementById("district");

      // Populate states
      for (let state in stateDistricts) {
        let option = document.createElement("option");
        option.value = state;
        option.textContent = state;
        stateSelect.appendChild(option);
      }

      // When a state is selected, populate districts
      stateSelect.onchange = function () {
        const selectedState = this.value;
        districtSelect.innerHTML = `<option value="">Select District</option>`;

        if (stateDistricts[selectedState]) {
          stateDistricts[selectedState].forEach(district => {
            let option = document.createElement("option");
            option.value = district;
            option.textContent = district;
            districtSelect.appendChild(option);
          });
        }
      };
    };

    // Validate form data
    let isValid = {
      name: false,
      phone: false,
      email: false,
      password: false,
      confirmPassword: false
    };

    // Full Name validation
    document.getElementById("full_name").oninput = function () {
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
    document.getElementById("phone").oninput = function () {
      const error = document.getElementById("phoneError");
      const value = this.value.trim();

      if (value === "") {
        error.textContent = "";
        isValid.phone = false;
      } else if (value.length !== 10 || value.length < 10) {
        error.textContent = "Please enter a valid 10-digit phone number";
        isValid.phone = false;
      } else {
        error.textContent = "";
        isValid.phone = true;
      }
    };

    // Email validation
    document.getElementById("email").oninput = function () {
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
    document.getElementById("password").oninput = function () {
      const error = document.getElementById("passwordError");

      const value = this.value;

      if (value.length < 8) {
        error.textContent = "Password must be at least 8 characters long";
        isValid.password = false;
      } else {
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
    document.querySelector('form').onsubmit = function (e) {
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

    // Alert functions
    function showAlert(title, message, type = 'error') {
      const alertEl = document.getElementById('customAlert');
      const overlayEl = document.getElementById('alertOverlay');
      const titleEl = document.getElementById('alertTitle');
      const messageEl = document.getElementById('alertMessage');

      // Remove existing classes
      alertEl.classList.remove('alert-error', 'alert-success');
      // Add new class based on type
      alertEl.classList.add(`alert-${type}`);

      titleEl.textContent = title;
      messageEl.textContent = message;

      alertEl.style.display = 'block';
      overlayEl.style.display = 'block';

      // Add event listener to close alert when clicking outside
      overlayEl.onclick = closeAlert;
    }

    function closeAlert() {
      const alertEl = document.getElementById('customAlert');
      const overlayEl = document.getElementById('alertOverlay');

      alertEl.style.display = 'none';
      overlayEl.style.display = 'none';
    }

    //toggle password visibility
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

  <!-- ////////////////////////////////////////////////////////////////////// -->
  <?php

  include 'db.php';

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $role = 'customer';

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
      $stmt = $conn->prepare("INSERT INTO users (full_name,phone, email, password, state, district, role) VALUES (?,?,?,?,?,?,?)");
      $stmt->bind_param("sssssss", $full_name, $phone, $email, $hashedPassword, $state, $district, $role);

      if ($stmt->execute()) {
        // Set session variables for new users
        $_SESSION['user_id'] = $conn->insert_id; // Get the last inserted user ID
        $_SESSION['name'] = $full_name;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['state'] = $state;
        $_SESSION['district'] = $district;
        $_SESSION['created_at'] = date('F Y');
        $_SESSION['role'] = $role;

        // Redirect to dashboard
        echo "<script>showAlert('Success!', 'Account created successfully!', 'success'); 
          setTimeout(() => window.location.href = 'customerdashboard.php', 2000);</script>";
      } else {
        echo "<script>showAlert('Error', 'Error creating account: " . $stmt->error . "', 'error');</script>";
      }
    }
  }
  ?>
</body>

</html>