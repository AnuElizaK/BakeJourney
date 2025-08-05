<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'baker') {
  header("Location: index.php"); // Redirect to login if not authorized
  exit();
}

// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare(
  "SELECT full_name, email, phone, district, state, bio, brand_name, specialty, rating, no_of_reviews, address, profile_image,
  experience, order_lead_time, availability, custom_orders
  FROM users, bakers
  WHERE users.user_id = bakers.user_id AND users.user_id = ?"
);
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

<?php include 'bakernavbar.php'; ?>

<body>
  <div class="container">
    <h1 class="page-title">Baker Profile</h1>
    <div class="baker-data">
      <!-- Profile Header -->
      <div class="profile-header">
        <div class="profile-avatar" style="position:relative;">
          <?php
          $name = $_SESSION['name'];
          $parts = explode(' ', $name);
          $initials = strtoupper($parts[0][0] . ($parts[1][0] ?? ''));
          if (!empty($user['profile_image']) && file_exists('uploads/' . $user['profile_image'])) {
            echo '<img src="uploads/' . htmlspecialchars($user['profile_image']) . '" alt="Profile Image" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
          } else {
            echo $initials;
          }
          ?>
          <div class="ranking-badge">Top Baker</div>
        </div>

        <!-- Edit button and modal handled by JS -->
        <?php
        // Handle AJAX upload (from JS/cropper)
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_profile_image'])) {
          if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $fileTmp = $_FILES['profile_image']['tmp_name'];
            $fileType = mime_content_type($fileTmp);
            if ($fileType === 'image/jpeg') {
              $filename = 'profile_' . $user_id . '.jpg';
              $dest = __DIR__ . '/uploads/' . $filename;

              if (move_uploaded_file($fileTmp, $dest)) {
                $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE user_id = ?");
                $stmt->bind_param("si", $filename, $user_id);
                $stmt->execute();
                $stmt->close();
                echo "<script>alert('✅ Profile image uploaded successfully!'); window.location.href = 'bakerprofile.php';</script>";
                exit;
              } else {
                echo "<script>alert('❌ Failed to upload image.');</script>";
              }
            } else {
              echo "<script>alert('❌ Only JPEG images are allowed.');</script>";
            }
          } else {
            echo "<script>alert('❌ Please select a JPEG image to upload.');</script>";
          }
        }
        // Handle AJAX remove
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_profile_image'])) {
          $profileImgPath = __DIR__ . '/uploads/profile_' . $user_id . '.jpg';
          if (file_exists($profileImgPath)) {
            unlink($profileImgPath);
            $stmt = $conn->prepare("UPDATE users SET profile_image = NULL WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
            echo "<script>alert('✅ Profile image removed.'); window.location.href = 'bakerprofile.php';</script>";
            exit;
          }
        }
        ?>
        <!-- Cropper.js CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="avatar-cropper.css">

        <div class="profile-info">
          <h1><?php echo htmlspecialchars($_SESSION['name']); ?></h1>
          <p><?php echo htmlspecialchars($_SESSION['email']); ?><br>
            Joined at <?php echo htmlspecialchars($_SESSION['created_at']); ?></p>
          <div class="baker-rating">
            <div class="stars">
              <?php
              $stars = floor($user['rating']);
              for ($i = 0; $i < $stars; $i++)
                echo "<span class=\"star filled\">★</span>";
              for ($i = $stars; $i < 5; $i++)
                echo "<span class=\"star\">☆</span>";
              ?>
            </div>
            <span
              class="rating-number"><?php echo number_format($user['rating'], 1); ?>&nbsp;(<?php echo htmlspecialchars($user['no_of_reviews']); ?>
              Reviews)</span>
          </div>
          <p><strong>Specialty:</strong> <?php echo htmlspecialchars($user['specialty']); ?></p>
        </div>

        <div class="profile-stats">
          <div class="stat-card">
            <span class="stat-number">200+</span>
            <span class="stat-label">Orders Completed</span>
          </div>
          <div class="stat-card">
            <span class="stat-number"><?php echo number_format($user['rating'], 1); ?></span>
            <span class="stat-label">Customer rating</span>
          </div>
          <div class="stat-card">
            <span class="stat-number"><?php echo htmlspecialchars($user['no_of_reviews']); ?>+</span>
            <span class="stat-label">Customer Reviews</span>
          </div>
          <div class="stat-card">
            <span class="stat-number"><?php echo htmlspecialchars($user['no_of_reviews']); ?>+</span>
            <span class="stat-label">Followers</span>
          </div>
        </div>
      </div>

      <!-- Baker Information -->
      <div class="profile-section">
        <h2 class="section-title">Baker Information</h2>
        <form method="post" id="updateProfileForm">
          <div class="form-grid">
            <div class="form-group">
              <label for="fullName">Brand Name</label>
              <input type="text" id="brand_name" name="brand_name"
                value="<?php echo htmlspecialchars($user['brand_name']); ?>">
            </div>
            <div class="form-group">
              <label for="full_name">Full Name</label>
              <input type="text" id="full_name" name="full_name"
                value="<?php echo htmlspecialchars($user['full_name']); ?>">
              <div id="nameError" class="error"></div>
            </div>

            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" maxlength="10"
                value="<?php echo htmlspecialchars($user['phone']); ?>">
              <div id="phoneError" class="error"></div>
            </div>

            <div class="form-group">
              <label for="state">State</label>
              <select id="state" name="state" onchange="updateDistricts()" required>
                <option value="">Select your state</option>
                <?php
                $states = [
                  "Andhra Pradesh",
                  "Arunachal Pradesh",
                  "Assam",
                  "Bihar",
                  "Chhattisgarh",
                  "Goa",
                  "Gujarat",
                  "Haryana",
                  "Himachal Pradesh",
                  "Jharkhand",
                  "Karnataka",
                  "Kerala",
                  "Madhya Pradesh",
                  "Maharashtra",
                  "Manipur",
                  "Meghalaya",
                  "Mizoram",
                  "Nagaland",
                  "Odisha",
                  "Punjab",
                  "Rajasthan",
                  "Sikkim",
                  "Tamil Nadu",
                  "Telangana",
                  "Tripura",
                  "Uttar Pradesh",
                  "Uttarakhand",
                  "West Bengal",
                  "Andaman and Nicobar Islands",
                  "Chandigarh",
                  "Dadra and Nagar Haveli and Daman and Diu",
                  "Delhi",
                  "Jammu and Kashmir",
                  "Ladakh",
                  "Lakshadweep",
                  "Puducherry"
                ];
                foreach ($states as $state) {
                  $selected = ($state === $selectedState) ? "selected" : "";
                  echo "<option value=\"$state\" $selected>$state</option>";
                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <label for="district">District</label>
              <select id="district" name="district" required>
                <option value="">Select your district</option>
              </select>
            </div>

            <script>
              const stateDistrict = {
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

              const selectedState = "<?php echo htmlspecialchars($user['state']) ?? ''; ?>";
              const selectedDistrict = "<?php echo htmlspecialchars($user['district']) ?? ''; ?>";

              function updateDistricts() {
                const stateSelect = document.getElementById("state");
                const districtSelect = document.getElementById("district");
                const selectedState = stateSelect.value;

                // Clear current options
                districtSelect.innerHTML = '<option value="">Select your district</option>';

                // Add new options
                if (stateDistrict[selectedState]) {
                  stateDistrict[selectedState].forEach(district => {
                    const option = document.createElement("option");
                    option.value = district;
                    option.textContent = district;

                    // Pre-select saved district
                    if (district === selectedDistrict) {
                      option.selected = true;
                    }
                    districtSelect.appendChild(option);
                  });
                }
              }
              // Set state dropdown and update districts on page load
              window.onload = function () {
                const stateSelect = document.getElementById("state");
                if (selectedState) {
                  stateSelect.value = selectedState;
                  updateDistricts(); // Will also select district
                }
              };
            </script>

            <div class="form-group">
              <label for="address">Address</label>
              <textarea id="address" name="address" rows="2"
                placeholder="Enter your address"><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>

            <div class="form-group">
              <label for="specialty">Baking Specialty</label>
              <select id="specialty" name="specialty">
                <option value="Artisan Breads & Sourdoughs" <?php echo ($user['specialty'] == 'Artisan Breads & Sourdoughs') ? 'selected' : ''; ?>>
                  Artisan Breads & Sourdoughs</option>
                <option value="Custom Cakes & Pastries" <?php echo ($user['specialty'] == 'Custom Cakes & Pastries') ? 'selected' : ''; ?>>
                  Custom Cakes & Pastries</option>
                <option value="Gluten-Free Treats" <?php echo ($user['specialty'] == 'Gluten-Free Treats') ? 'selected' : ''; ?>>
                  Gluten-Free Treats</option>
                <option value="Desserts & Sweets" <?php echo ($user['specialty'] == 'Desserts & Sweets') ? 'selected' : ''; ?>>
                  Desserts & Sweets</option>
                <option value="Cookies & Biscuits" <?php echo ($user['specialty'] == 'Cookies & Biscuits') ? 'selected' : ''; ?>>
                  Cookies & Biscuits</option>
                <option value="Pies & Tarts" <?php echo ($user['specialty'] == 'Pies & Tarts') ? 'selected' : ''; ?>>
                  Pies & Tarts
                </option>
              </select>
            </div>
            <div class="form-group">
              <label for="bio">Baker Bio</label>
              <textarea id="bio" name="bio" rows="2"
                placeholder="Tell us a little about yourself"><?php echo htmlspecialchars($user['bio']); ?></textarea>
            </div>
          </div>
          <button type="submit" name="bkupdate" class="btn">Update Profile</button>
        </form>
      </div>
    </div>

    <!-- Business Settings -->

    <div class="profile-section">
      <h2 class="section-title">Business Settings</h2>
      <form method="POST">
        <div class="form-grid">
          <div class="form-group">
            <label for="experience">Experience</label>
            <select id="experience" name="experience">
              <option value="">Select experience level</option>
              <option value="Less than 1 year" <?php echo ($user['experience'] == 'Less than 1 year') ? 'selected' : ''; ?>>Less than 1 year</option>
              <option value="1 year" <?php echo ($user['experience'] == '1 year') ? 'selected' : ''; ?>>1 year</option>
              <option value="2-3 years" <?php echo ($user['experience'] == '2-3 years') ? 'selected' : ''; ?>>2-3 years
              </option>
              <option value="4-5 years" <?php echo ($user['experience'] == '4-5 years') ? 'selected' : ''; ?>>4-5 years
              </option>
              <option value="6-7 years" <?php echo ($user['experience'] == '6-7 years') ? 'selected' : ''; ?>>6-7 years
              </option>
              <option value="7+ years" <?php echo ($user['experience'] == '7+ years') ? 'selected' : ''; ?>>More than 7
                years</option>
            </select>
          </div>
          <div class="form-group">
            <label for="orderLeadTime">Order Lead Time (days)</label>
            <select id="orderLeadTime" name="order_lead_time">
              <option value="">Select order lead time</option>
              <option value="1 day" <?php echo ($user['order_lead_time'] == '1 day') ? 'selected' : ''; ?>>1 day</option>
              <option value="2-3 days" <?php echo ($user['order_lead_time'] == '2-3 days') ? 'selected' : ''; ?>>2-3 days
              </option>
              <option value="4-5 days" <?php echo ($user['order_lead_time'] == '4-5 days') ? 'selected' : ''; ?>>4-5 days
              </option>
              <option value="1 week" <?php echo ($user['order_lead_time'] == '1 week') ? 'selected' : ''; ?>>1 week
              </option>
              <option value="2 weeks" <?php echo ($user['order_lead_time'] == '2 weeks') ? 'selected' : ''; ?>>2 weeks
              </option>
              <option value="1 month" <?php echo ($user['order_lead_time'] == '1 month') ? 'selected' : ''; ?>>1 month
              </option>
              <option value="2 months" <?php echo ($user['order_lead_time'] == '2 months') ? 'selected' : ''; ?>>2 months
              </option>
              <option value="3 months" <?php echo ($user['order_lead_time'] == '3 months') ? 'selected' : ''; ?>>3 months
              </option>
              <option value="More than 3 months" <?php echo ($user['order_lead_time'] == 'More than 3 months') ? 'selected' : ''; ?>>More than 3 months</option>
            </select>
          </div>
          <div class="form-group">
            <label for="availability">Availability Status</label>
            <select id="availability" name="availability">
              <option value="">Select availability status</option>
              <option value="Available for orders" <?php echo ($user['availability'] == 'Available for orders') ? 'selected' : ''; ?>>Available for orders</option>
              <option value="Busy - limited availability" <?php echo ($user['availability'] == 'Busy - limited availability') ? 'selected' : ''; ?>>Busy - limited availability</option>
              <option value="Temporarily unavailable" <?php echo ($user['availability'] == 'Temporarily unavailable') ? 'selected' : ''; ?>>Temporarily unavailable</option>
            </select>
          </div>
          <div class="form-group">
            <label for="custom">Custom orders</label>
            <select id="custom" name="custom_orders">
              <option value="">Select custom order status</option>
              <option value="Takes custom orders" <?php echo ($user['custom_orders'] == 'Takes custom orders') ? 'selected' : ''; ?>>Takes custom orders</option>
              <option value="Takes limited custom orders" <?php echo ($user['custom_orders'] == 'Takes limited custom orders') ? 'selected' : ''; ?>>Takes limited custom orders</option>
              <option value="Temporarily unavailable" <?php echo ($user['custom_orders'] == 'Temporarily unavailable') ? 'selected' : ''; ?>>Temporarily unavailable</option>
              <option value="Does not take custom orders" <?php echo ($user['custom_orders'] == 'Does not take custom orders') ? 'selected' : ''; ?>>Does not take custom orders</option>
            </select>
          </div>
        </div>
        <button type="submit" class="btn" name="businessSettings">Save Settings</button>
      </form>
    </div>

    <!-- Change Password -->
    <div class="profile-section">
      <h2 class="section-title">Change Password</h2>
      <form method="post" id="validatePasswordForm">
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
            <div id="passwordError" class="error"></div>
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
            <div id="confirmPasswordError" class="error"></div>
          </div>
        </div>
        <button type="submit" name="chngpwd" class="btn">Change Password</button>
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
    // -------- FORM SUBMIT VALIDATION --------
    document.getElementById("updateProfileForm").onsubmit = function (e) {
      const nameInput = document.getElementById("full_name");
      const phoneInput = document.getElementById("phone");
      const name = nameInput.value.trim();
      const phone = phoneInput.value.trim();

      let isValid = true;

      // Name validation
      if (!/^[a-zA-Z\s]+$/.test(name) || name === "") {
        document.getElementById("nameError").textContent = "Full name should only contain letters and spaces";
        isValid = false;
      } else {
        document.getElementById("nameError").textContent = "";
      }

      // Phone validation
      if (!/^[7-9][0-9]{9}$/.test(phone)) {
        document.getElementById("phoneError").textContent = "Phone number must start with 7, 8, or 9 and be 10 digits long.";
        isValid = false;
      } else {
        document.getElementById("phoneError").textContent = "";
      }

      if (!isValid) {
        e.preventDefault();
        alert("Error: Please fix all profile errors before submitting the form");
        return false;
      }
      return true;
    };

    document.getElementById("validatePasswordForm").onsubmit = function (e) {
      const pwd = document.getElementById("newPassword").value;
      const confirmPwd = document.getElementById("confirmPassword").value;
      let isValid = true;

      // Password validation
      if (pwd.length < 8) {
        document.getElementById("passwordError").textContent = "Password must be at least 8 characters";
        isValid = false;
      } else {
        document.getElementById("passwordError").textContent = "";
      }

      // Confirm password validation
      if (confirmPwd !== pwd) {
        document.getElementById("confirmPasswordError").textContent = "Passwords do not match";
        isValid = false;
      } else {
        document.getElementById("confirmPasswordError").textContent = "";
      }

      if (!isValid) {
        e.preventDefault();
        alert("Error: Please fix all password errors before submitting the form");
        return false;
      }
      return true;
    };

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
  </script>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bkupdate'])) {
    $updated_name = $_POST['full_name'];
    $updated_phone = $_POST['phone'];
    $updated_bio = $_POST['bio'];
    $updated_state = $_POST['state'];
    $updated_district = $_POST['district'];
    $updated_brand = $_POST['brand_name'];
    $updated_specialty = $_POST['specialty'];
    $updated_address = $_POST['address'];

    // Update query
    $stmt = $conn->prepare("UPDATE users, bakers SET full_name = ?, phone = ?, bio = ?, district = ?, state = ?, brand_name = ?, specialty = ?, address = ? WHERE users.user_id = bakers.user_id AND users.user_id = ?");
    $stmt->bind_param("ssssssssi", $updated_name, $updated_phone, $updated_bio, $updated_district, $updated_state, $updated_brand, $updated_specialty, $updated_address, $user_id);

    if ($stmt->execute()) {
      // Update session name so it's reflected immediately
      $_SESSION['name'] = $updated_name;
      echo "<script>alert('✅ Profile updated successfully!'); window.location.href = 'bakerprofile.php';</script>";
    } else {
      echo "<script>alert('❌ Failed to update profile. Please try again.');</script>";
    }
    $stmt->close();
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['businessSettings'])) {
    $updated_experience = $_POST['experience'];
    $updated_order_lead_time = $_POST['order_lead_time'];
    $updated_availability = $_POST['availability'];
    $updated_custom_orders = $_POST['custom_orders'];

    // Update query
    $stmt = $conn->prepare("UPDATE bakers SET experience = ?, order_lead_time = ?, availability = ?, custom_orders = ? WHERE user_id = ?");
    $stmt->bind_param("ssssi", $updated_experience, $updated_order_lead_time, $updated_availability, $updated_custom_orders, $user_id);

    if ($stmt->execute()) {
      echo "<script>alert('✅ Business settings updated successfully!'); window.location.href = 'bakerprofile.php';</script>";
    } else {
      echo "<script>alert('❌ Failed to update business settings. Please try again.');</script>";
    }
    $stmt->close();
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chngpwd'])) {
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
      echo "<script>alert('✅ Your account has been deleted. We are sad to see you go.'); window.location.href = 'index.php';</script>";
      exit();
    } else {
      echo "<script>alert('❌ Failed to delete account. Please try again.');</script>";
    }
    $stmt->close();
    $conn->close();
  }
  ?>

</body>
<!-- Cropper.js JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js" crossorigin="anonymous"
  referrerpolicy="no-referrer"></script>
<script src="avatar-cropper.js"></script>

</html>