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

if (!isset($_GET['baker_id'])) {
    echo "Baker not found.";
    exit;
}
$baker_id = $_GET['baker_id'];
$sql = "SELECT *
        FROM bakers 
        JOIN users ON bakers.user_id = users.user_id 
        WHERE bakers.baker_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Baker not found.";
    exit;
}

$baker = $result->fetch_assoc();

// Fetch products by user_id (baker_id)
$stmt = $conn->prepare("SELECT * FROM products WHERE baker_id = ?");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$productResult = $stmt->get_result();
$products = $productResult->fetch_all(MYSQLI_ASSOC);



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($baker['full_name']); ?> - Baker Profile | BakeJourney</title>
    <link rel="stylesheet" href="bakerinfopage.css">
</head>

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
    <!-- Main Content -->
    <main class="container">
        <!-- Profile Section -->
        <section class="profile-section">
            <div class="profile-header">
                <div class="profile-image">
                    <img src="<?= !empty($baker['profile_image']) ? 'uploads/' . htmlspecialchars($baker['profile_image']) : 'media/baker.png' ?>"
                        alt="<?= htmlspecialchars($baker['full_name']); ?>"
                        style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);">
                    <div class="verified-badge">✓</div>
                </div>
                <div class="profile-info">
                    <h1><?= htmlspecialchars($baker['full_name']); ?></h1>
                    <div class="profile-brand"><?= htmlspecialchars($baker['brand_name']); ?></div>
                    <div class="profile-specialty"><?= htmlspecialchars($baker['specialty']); ?></div>
                    <div class="profile-stats">
                        <div class="stat">
                            <span class="stat-number"><?= htmlspecialchars($baker['no_of_reviews']); ?></span>
                            <span class="stat-label">Reviews</span>
                        </div>
                        <!-- <div class="stat">
                            <span class="stat-number"><?= htmlspecialchars($baker['orders']); ?></span>
                            <span class="stat-label">Orders</span>
                        </div> -->
                        <div class="stat">
                            <span class="stat-number"><?= htmlspecialchars($baker['experience']); ?></span>
                            <span class="stat-label">Years</span>
                        </div>
                    </div>
                    <div class="rating-section">
                        <div class="stars">
                            <?php
                            $stars = floor($baker['rating']);
                            for ($i = 0; $i < $stars; $i++)
                                echo "<span class=\"star filled\">★</span>";
                            for ($i = $stars; $i < 5; $i++)
                                echo "<span class=\"star\">☆</span>";
                            ?>
                        </div>
                        <span class="rating-text"><?= number_format($baker['rating'], 1); ?> · 98% satisfaction rate</span>
                    </div>
                </div>
            </div>
            <div class="profile-bio">
                <p><?= htmlspecialchars($baker['bio']); ?></p>
            </div>
            <div class="location">
                📍<?= htmlspecialchars($baker['district']); ?>, <?= htmlspecialchars($baker['state']); ?>
            </div>
            <div class="profile-actions">
                <a href="#" class="btn btn-primary">Message Baker</a>
                <a href="#menu" class="btn btn-secondary">View Menu</a>
            </div>
        </section>

        <!-- Gallery Section -->
        <section class="gallery-section" id="menu">
            <?php if (!empty($products)): ?>
                <div class="gallery-header">
                    <div class="gallery-title">Recent Creations</div>
                    <div class="gallery-count"><?= count($products); ?> items</div>
                </div>
                <div class="image-gallery">
                    <?php foreach ($products as $product): ?>
                        <div class="gallery-item" onclick="window.location.href='productinfopage.php?product_id=<?= $product['product_id']; ?>'">
                            <img src=<?= !empty($product['image']) ? 'uploads/' . htmlspecialchars($product['image']) : "https://plus.unsplash.com/premium_photo-1690214491960-d447e38d0bd0?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" ?> alt="<?= htmlspecialchars($product['name']); ?>" class="gallery-image">
                            <div class="gallery-overlay" >
                                <div class="gallery-info">
                                    <h4 class="item-name"><?= htmlspecialchars($product['name']); ?></h4>
                                    <p class="item-price">₹<?= htmlspecialchars($product['price']); ?></p>
                                    <p class="item-descritpion"><?= htmlspecialchars($product['description']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>This baker has not added any products yet.</p>
            <?php endif; ?>
        </section>


        <!-- Reviews Section -->
        <section class="reviews-section">
            <div class="reviews-header">
                <div class="reviews-title">Customer Reviews</div>
                <div class="reviews-summary">
                    <div class="stars">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                    </div>
                    <span>5.0 out of 5 stars · 127 reviews</span>
                </div>
            </div>

            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="https://images.unsplash.com/photo-1710777915903-a7d7f159f2c0?q=80&w=2080&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            alt="Emily R." class="reviewer-avatar">
                        <div>
                            <div class="reviewer-name">Emily R.</div>
                            <div class="review-date">2 days ago</div>
                        </div>
                    </div>
                    <div class="review-rating">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                    </div>
                </div>
                <p class="review-text">Sarah's sourdough is absolutely incredible! The texture and flavor are unmatched.
                    I've been ordering weekly for months now and every loaf is consistently perfect. The 48-hour
                    fermentation really makes a difference!</p>
            </div>

            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80"
                            alt="Michael T." class="reviewer-avatar">
                        <div>
                            <div class="reviewer-name">Michael C.</div>
                            <div class="review-date">1 week ago</div>
                        </div>
                    </div>
                    <div class="review-rating">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                    </div>
                </div>
                <p class="review-text">Best bread I've ever had! Sarah's attention to detail and passion really shows in
                    every loaf. The whole wheat bread is my family's favorite. Professional quality with that perfect
                    homemade touch.</p>
            </div>

            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80"
                            alt="Jennifer L." class="reviewer-avatar">
                        <div>
                            <div class="reviewer-name">Jennifer L.</div>
                            <div class="review-date">2 weeks ago</div>
                        </div>
                    </div>
                    <div class="review-rating">
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                        <span class="star">★</span>
                    </div>
                </div>
                <p class="review-text">Amazing experience! Sarah is so professional and her bread is restaurant quality.
                    The delivery was prompt and the packaging kept everything fresh. Will definitely order again for our
                    next dinner party!</p>
            </div>
        </section>
    </main>
    <?php include 'globalfooter.php'; ?>
</body>

</html>