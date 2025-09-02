<?php session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
  header("Location: index.php"); // Redirect to login if not authorized
  exit();
}

// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");


$stmt = $conn->prepare("
  SELECT *
  FROM users u
  JOIN bakers b ON u.user_id = b.user_id
  
");
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Bakers | BakeJourney</title>
  <meta name="description" content="BakeJourney - The Home Baker's Marketplace" />
  <meta name="author" content="BakeJourney" />
  <link rel="stylesheet" href="bakers.css" />
</head>

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
  <!-- All Bakers Section -->
  <section class="all-bakers" id="bakers">
    <div class="container">
      <div class="section-header">
        <h2>Find Your Perfect Baker</h2>
        <p>Looking for the right baker but don't know where to start? Discover homebakers that get you like no one else.
        </p>
      </div>

      <!-- Baker Search -->
      <div class="filter-section">
        <div class="search-box">
          <input type="search" placeholder="Search bakers by name or specialty..." class="baker-search-input">
        </div>
      </div>

      <div class="bakers-grid">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($baker = $result->fetch_assoc()): ?>
            <div class="baker-card" onclick="window.location.href='bakerinfopage.php?baker_id=<?= $baker['baker_id']; ?>'">
              <div class="baker-image">
                <img
                  src="<?= !empty($baker['profile_image']) ? 'uploads/' . htmlspecialchars($baker['profile_image']) : 'media/baker.png' ?>"
                  alt="<?= htmlspecialchars($baker['full_name']); ?>">
                <div class="ranking-badge">#<?= $baker['baker_id']; ?></div>
              </div>
              <div class="baker-content">
                <h3><?= htmlspecialchars($baker['full_name']); ?></h3>
                <p class="baker-specialty">Specialty: <?= htmlspecialchars($baker['specialty']); ?></p>
                <div class="baker-stats">
                  <span class="stat"><?php echo htmlspecialchars($baker['experience']); ?>+ Years exp.</span>
                  <span class="stat"><?php echo number_format($baker['rating'], 1); ?> Rating</span>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div id="no-bakers-message" style="text-align:center; color:#f59e0b; font-weight:600; margin:32px 0;">
            No bakers found.
          </div>
        <?php endif; ?>

      </div>
      <div id="no-bakers-message"
        style="display:none; text-align:center; color:#f59e0b; font-weight:600; margin:32px 0;">
        No bakers found.
      </div>
    </div>
  </section>

  <?php include 'globalfooter.php'; ?>

  <script>
    // ---Baker Search Function---
    document.querySelector('.baker-search-input').addEventListener('input', function (e) {
      const searchValue = e.target.value.toLowerCase();
      const bakers = document.querySelectorAll('.baker-card');
      const noBakers = document.getElementById('no-bakers-message');
      let visibleCount = 0;

      bakers.forEach(baker => {
        const title = baker.querySelector('.baker-content').textContent.toLowerCase();
        const specialty = baker.querySelector('.baker-specialty').textContent.toLowerCase();
        if (title.includes(searchValue) || specialty.includes(searchValue)) {
          baker.style.display = 'block';
          visibleCount++;
        } else {
          baker.style.display = 'none';
        }
      });
      if (noBakers) {
        noBakers.style.display = visibleCount === 0 ? 'block' : 'none';
      }
    });

  </script>
</body>

</html>