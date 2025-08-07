<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
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
  "SELECT *
  FROM users 
  WHERE user_id = ?"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

//fetch order details
$stmt = $conn->prepare(
  "SELECT *
  FROM orders
  WHERE customer_id = ?"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
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
          if (!empty($user['profile_image']) && file_exists('uploads/' . $user['profile_image'])) {
           echo '<img src="uploads/' . htmlspecialchars($user['profile_image']) . '" alt="Profile Image" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
          } else {
          echo $initials;
          }
          ?>
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
                echo "<script>alert('✅ Profile image uploaded successfully!');window.location.href = 'customerprofile.php';</script>";
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
            echo "<script>alert('✅ Profile image removed.'); window.location.href = 'customerprofile.php';</script>";
            exit;
          }
        }
        ?>
        <!-- Cropper.js CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="avatar-cropper.css">

        <h1 class="profile-name"> <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
        <p class="profile-contact"><?php echo htmlspecialchars($_SESSION['email']); ?> <br>
          Joined at <?php echo htmlspecialchars($_SESSION['created_at']); ?>
        </p>
        <p> </p>
        <div class="profile-stats">
          <div class="stat-card">
            <span class="stat-number">12</span>
            <span class="stat-label">Total Orders</span>
          </div>
          <div class="stat-card following" onclick="window.location.href='customerfollowinglist.php'">
            <span class="stat-number">3</span>
            <span class="stat-label">Bakers You Follow</span>
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
        <form method="post" id="updateProfileForm">
          <div class="form-grid">
            <div class="form-group">
              <label for="full_name">Full Name</label>
              <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required
                value="<?php echo htmlspecialchars($user['full_name']); ?>">
              <div id="nameError" class="error"></div>
            </div>

            <div class="form-group">
              <label for="phone">Phone Number</label>
              <div class="phone-input-wrapper">
                <span class="phone-prefix">+91</span>
                <input type="tel" id="phone" name="phone" maxlength="10" value="<?php echo htmlspecialchars($user['phone']); ?>">
              </div>
              <div id="phoneError" class="error"></div>
            </div>
            <div class="form-group">
              <label for="bio">Bio</label>
              <textarea id="bio" name="bio" rows="2"
                placeholder="Tell us a little about yourself"><?php echo htmlspecialchars($user['bio']); ?></textarea>
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
              <div class="address-label-row">
                <label for="address">Address</label>
                <div class="add-more-addresses">
                  <button class="btn-more" type="button">+</button>
                </div>
              </div>
              <textarea id="address" name="address" rows="2"
                placeholder="Enter your full delivery address"><?php echo htmlspecialchars($user['address']); ?></textarea>

            </div>
          </div>
          <button type="submit" name="update" class="btn">Update Profile</button>
        </form>
      </div>
    </div>

    <!-- Recent Orders -->

    <div class="profile-section">
      <h2 class="section-title">Recent Orders</h2>

      <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
          <div class="order-card">
            <div class="order-info">
              <h4><?= htmlspecialchars($order['product_name']); ?> from <?= htmlspecialchars($order['baker_name']); ?></h4>
              <p>Order #<?= htmlspecialchars($order['order_id']); ?> • <?= htmlspecialchars($order['order_date']); ?> • $<?= htmlspecialchars($order['total_price']); ?></p>
            </div>
            <span class="order-status <?= htmlspecialchars($order['status']); ?>"><?= htmlspecialchars($order['status']); ?></span>
          </div>
        <?php endforeach; ?>
        <button class="btn secondary" onclick="window.location.href='customerorders.php'">View All Orders</button>
      <?php else: ?>
        <p style="color: #374151">No recent orders found.</p>
      <?php endif; ?>

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
      <form method="post" id="updatePasswordForm">
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
        <button type="submit" name="changepwd" class="btn">Change Password</button>
      </form>
    </div>

    <!-- Delete Account -->
    <div class="profile-section">
      <h2 class="section-title">Delete Account</h2>
      <p class="warning">This action is irreversible. Please proceed with caution. Once deleted, your account details
        cannot be recovered.</p>
      <form method="POST" id="deleteAccountForm">
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

  <!-- custom Alert -->
  <div class="alert-overlay" id="alertOverlay"></div>
  <div class="custom-alert" id="customAlert">
    <h3 id="alertTitle"></h3>
    <p id="alertMessage"></p>
    <button class="alert-button" onclick="closeAlert()">OK</button>
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
      if (!/^[a-zA-Z\s]+$/.test(name)) {
        document.getElementById("nameError").textContent = "Full name should only contain letters and spaces";
        isValid = false;
      } else {
        document.getElementById("nameError").textContent = "";
      }

      // Phone validation
      if (!/^[5-9][0-9]{9}$/.test(phone)) {
        document.getElementById("phoneError").textContent = "Phone number must start with 5, 6, 7, 8, or 9 and be 10 digits long.";
        isValid = false;
      } else {
        document.getElementById("phoneError").textContent = "";
      }

      if (!isValid) {
        e.preventDefault();
        showAlert("Error", "Please fix all profile errors before submitting the form");
        return false;
      }
      return true;
    };

    document.getElementById("updatePasswordForm").onsubmit = function (e) {
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
        showAlert("Error", "Please fix all password errors before submitting the form");
        return false;
      }
      return true;
    };

    // Delete account confirmation
    document.getElementById('deleteAccountForm').onsubmit = function (e) {
      if (!confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
        e.preventDefault();
      }
    };

    //------------ Alert functions---------------------------------------
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
    //--------------------------------------------------------------------

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
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $updated_name = $_POST['full_name'];
    $updated_phone = $_POST['phone'];
    $updated_bio = $_POST['bio'];
    $updated_state = $_POST['state'];
    $updated_district = $_POST['district'];
    $updated_address = $_POST['address'];

    // Update query
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, bio = ?, state = ?, district = ?, address = ? WHERE user_id = ?");
    $stmt->bind_param("ssssssi", $updated_name, $updated_phone, $updated_bio, $updated_state, $updated_district, $updated_address, $user_id);

    if ($stmt->execute()) {
      // Update session name so it's reflected immediately
      $_SESSION['name'] = $updated_name;
      echo "<script>showAlert('Success!', '✅ Profile updated successfully!', 'success'); window.location.href = 'customerprofile.php';</script>";
    } else {
      echo "<script>showAlert('Error', '❌ Failed to update profile. Please try again.', 'error');</script>";
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
    echo "<script>showAlert('Success!', '✅ Password changed successfully!', 'success');</script>";
    $stmt->close();
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
      // Destroy session and redirect
      session_destroy();
      echo "<script>showAlert('Account deleted successfully!', 'We are sad to see you go :(', 'success'); 
      setTimeout(() => window.location.href = 'index.php', 2000);</script>";

    } else {
      echo "<script>showAlert('Error', '❌ Failed to delete account. Please try again.', 'error');</script>";
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