<?php session_start();
include 'db.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer' && $_SESSION['role'] !== 'baker') {
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

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message']) && isset($_SESSION['user_id'])) {
    // Fetch the baker's user_id from the bakers table
    $stmt = $conn->prepare("SELECT user_id FROM bakers WHERE baker_id = ?");
    $stmt->bind_param("i", $_GET['baker_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $baker = $result->fetch_assoc();
    $receiver_id = $baker['user_id']; // Use the baker's user_id as receiver_id

    $message = trim($_POST['message'] ?? '');
    $attachment = null;

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $attachment_name = time() . '_' . basename($_FILES['attachment']['name']);
        $attachment_path = $upload_dir . $attachment_name;
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $attachment_path)) {
            $attachment = $attachment_name;
        }
    }

    if ($message || $attachment) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, attachment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $_SESSION['user_id'], $receiver_id, $message, $attachment);
        $stmt->execute();
    }
    header("Location: bakerinfopage.php?baker_id=" . $_GET['baker_id'] . "&chat=open#chatForm");
    exit;
}



// Fetch messages if chat is open
$messages = [];
if (isset($_GET['chat']) && $_GET['chat'] === 'open' && isset($_SESSION['user_id'])) {
    // Fetch the baker's user_id from the bakers table
    $stmt = $conn->prepare("SELECT user_id FROM bakers WHERE baker_id = ?");
    $stmt->bind_param("i", $baker_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $baker_user = $result->fetch_assoc();
    $baker_user_id = $baker_user['user_id']; // Use the baker's user_id

    // Fetch messages between the logged-in user and the baker
    $stmt = $conn->prepare("
        SELECT message_id, sender_id, receiver_id, message, attachment, sent_at 
        FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY sent_at ASC
    ");
    $stmt->bind_param("iiii", $_SESSION['user_id'], $baker_user_id, $baker_user_id, $_SESSION['user_id']);
    $stmt->execute();
    $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fetch products by baker_id
$stmt = $conn->prepare("SELECT * FROM products WHERE baker_id = ?");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$productResult = $stmt->get_result();
$products = $productResult->fetch_all(MYSQLI_ASSOC);

//fetch reviews for this baker
$stmt = $conn->prepare("
    SELECT r.review_id, r.baker_id, r.user_id, r.rating, r.review_text, r.review_date, 
           u.full_name, u.profile_image
    FROM baker_reviews r
    INNER JOIN users u ON r.user_id = u.user_id
    WHERE r.baker_id = ?
    ORDER BY r.review_date DESC
");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch summary stats
$stmt = $conn->prepare("
    SELECT AVG(rating) as avg_rating, COUNT(*) as review_count
    FROM baker_reviews
    WHERE baker_id = ?
");
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$summary = $stmt->get_result()->fetch_assoc();
$avg_rating = round($summary['avg_rating'], 1) ?: 0;
$review_count = $summary['review_count'] ?: 0;

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id'];
    $rating = (int) $_POST['rating'];
    $review_text = $_POST['review_text'];

    if ($rating >= 1 && $rating <= 5 && !empty($review_text)) {
        $stmt = $conn->prepare("INSERT INTO baker_reviews (baker_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $baker_id, $user_id, $rating, $review_text);
        $stmt->execute();
        header("Location: bakerinfopage.php?baker_id=" . $baker_id . "#reviews");
        exit;
    }
}

// to delete a comment by the user
$user_id = $_SESSION['user_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_comment'])) {
    $review_id = $_POST['review_id'];
    $stmt = $conn->prepare("DELETE FROM baker_reviews WHERE review_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $review_id, $user_id);
    $stmt->execute();
    header("Location: bakerinfopage.php?baker_id=" . $baker_id . "#reviews");
    exit;
}

function timeAgo($datetime)
{
    $time = time() - strtotime($datetime);

    if ($time < 60)
        return 'just now';
    if ($time < 3600)
        return floor($time / 60) . ' minutes ago';
    if ($time < 86400)
        return floor($time / 3600) . ' hours ago';
    if ($time < 2592000)
        return floor($time / 86400) . ' days ago';
    if ($time < 31104000)
        return floor($time / 2592000) . ' months ago';
    return floor($time / 31104000) . ' years ago';
}

function generateStars($rating, $maxStars = 5)
{
    $stars = '';
    for ($i = 1; $i <= $maxStars; $i++) {
        $stars .= ($i <= $rating) ? '★' : '☆';
    }
    return $stars;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($baker['full_name']); ?> | BakeJourney</title>
    <link rel="stylesheet" href="bakerinfopage.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />
</head>

<!-- Sticky Navigation Bar -->
<?php
if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer') {
    include 'custnavbar.php';
} else {
    include 'bakernavbar.php';
}
?>

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
                            <span class="stat-number"><?php echo $review_count ?></span>
                            <span class="stat-label">Reviews</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number"><?= htmlspecialchars($baker['experience']); ?>+</span>
                            <span class="stat-label">Years Experience</span>
                        </div>
                    </div>
                    <div class="rating-section">
                        <div class="stars">
                           <?php echo generateStars(round($avg_rating)); ?>
                        </div>
                        <span class="rating-text"><?= number_format($avg_rating, 1) ?></span>
                    </div>
                </div>
            </div>
            <div class="profile-bio">
                <p><?= htmlspecialchars($baker['bio']); ?></p>
            </div>
            <div class="location">
                📍<?= htmlspecialchars($baker['district']); ?>, <?= htmlspecialchars($baker['state']); ?>
            </div>
            <div class="contact-details">
            <h3>Contact & Business Details</h3>
            <div class="detail-item">
                <span class="detail-icon material-symbols-rounded">phone</span>
                <p class="detail-text"><?= htmlspecialchars($baker['phone'] ?: 'Not provided'); ?></p>
            </div>
            <div class="detail-item">
                <span class="detail-icon material-symbols-rounded">email</span>
                <p class="detail-text"><?= htmlspecialchars($baker['email'] ?: 'Not provided'); ?></p>
            </div>
            <div class="detail-item">
                <span class="detail-icon material-symbols-rounded">access_time</span>
                <p class="detail-text">Order Lead Time:</p>
                <span class="detail-info"><p><?= htmlspecialchars($baker['order_lead_time'] ?: 'Not specified'); ?></p></span>
            </div>
            <div class="detail-item">
                <span class="detail-icon material-symbols-rounded">calendar_today</span>
                <p class="detail-text">Availability:</p>
                <span class="detail-info"><p><?= htmlspecialchars($baker['availability'] ?: 'Not specified'); ?></p></span>
            </div>
            <div class="detail-item">
                <span class="detail-icon material-symbols-rounded">cake</span>
                <p class="detail-text">Custom Orders:</p>
                <span class="detail-info"><p><?= htmlspecialchars($baker['custom_orders']); ?></p></span>
            </div>
        </div>
         <div class="profile-actions">
                <?php if (in_array($baker['custom_orders'], ['Takes custom orders', 'Takes limited custom orders']) && $_SESSION['role'] === 'customer' || $_SESSION['role'] === 'baker' && $_SESSION['user_id'] === $baker_id): ?>
                    <a href="bakerinfopage.php?baker_id=<?= $baker_id ?>&chat=open#chatModal" class="btn btn-primary" id="chatBtn">
                        <?= $_SESSION['role'] === 'baker' ? 'View Messages' : 'Request Custom Order' ?>
                    </a>
                <?php endif; ?>
                <a href="#menu" class="btn btn-secondary">View Menu</a>
            </div>
        </section>

        <!-- Chat Modal -->
            <div id="chatModal" class="chat-modal" style="<?= isset($_GET['chat']) && $_GET['chat'] === 'open' ? 'display: flex;' : '' ?>">
                <div class="chat-container">
                    <div class="chat-header">
                        <img src="<?= !empty($baker['profile_image']) ? 'uploads/' . htmlspecialchars($baker['profile_image']) : 'media/baker.png' ?>"
                             alt="<?= htmlspecialchars($baker['full_name']); ?>" class="baker-avatar">
                        <div class="baker-chat-info">
                            <h4><?= htmlspecialchars($baker['brand_name'] ?: $baker['full_name']) ?></h4>
                            
                        </div>
                        <a href="bakerinfopage.php?baker_id=<?= $baker_id ?>" class="chat-close" style="text-decoration: none;">&times;</a>
                    </div>
                    <div class="chat-messages">
                        <?php if (empty($messages)): ?>
                            <div class="message received">
                                <div>Hi! 👋 Thanks for your interest in my products. How can I help you today?</div>
                                <div class="message-time"><?= date(' H:i') ?></div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($messages as $msg): ?>
                                <div class="message <?= $msg['sender_id'] === $_SESSION['user_id'] ? 'sent' : 'received' ?>">
                                    <div><?= htmlspecialchars($msg['message']) ?></div>
                                    <?php if ($msg['attachment']): ?>
                                        <div><img src="uploads/<?= htmlspecialchars($msg['attachment']) ?>" alt="Attachment" style="max-width: 200px;"></div>
                                    <?php endif; ?>
                                    <div class="message-time"><?= date('H:i', strtotime($msg['sent_at'])) ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <form method="POST" enctype="multipart/form-data" class="chat-input-container" id="chatForm">
                        <input type="hidden" name="baker_id" value="<?= $baker_id ?>">
                        <input type="hidden" name="receiver_id" value="<?= $baker_id ?>">
                        <textarea id="chatInput" name="message" class="chat-input" placeholder="Type a message..." rows="1"></textarea>
                        <div id="imagePreview" class="image-preview"></div>
                        <input type="file" id="attachmentInput" name="attachment" accept="image/*" style="display: none;">
                        
                        <button type="button" class="attach-button" onclick="document.getElementById('attachmentInput').click()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.19 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                            </svg>
                        </button>
                        <button type="submit" name="send_message" class="send-button">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

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
<section class="reviews-section" id="reviews">
    <div class="reviews-header">
        <div class="reviews-title">Customer Reviews</div>
        <div class="reviews-summary">
             <div class="stars">
                        <?php echo generateStars(round($avg_rating)); ?>
                    </div>
            <span><?= number_format($avg_rating, 1) ?> out of 5 stars · <?= $review_count ?> reviews</span>
        </div>
    </div>

    <?php if (empty($reviews)): ?>
        <p class="no-reviews">No reviews yet for this baker.</p>
    <?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="<?= !empty($review['profile_image']) ? 'uploads/' . htmlspecialchars($review['profile_image']) : 'media/baker.png' ?>"
                             alt="<?= htmlspecialchars($review['full_name']) ?>" class="reviewer-avatar">
                        <div>
                            <div class="reviewer-name"><?= htmlspecialchars($review['full_name']) ?></div>
                            <div class="review-date"><?php echo timeAgo($review['review_date']); ?></div>
                        </div>

                         <!-- to delete a comment by the user -->
                   <?php $logged_in_user_id = $_SESSION['user_id'];
                   if ($review['user_id'] == $logged_in_user_id):?>                        
                        <form  method="post" style="margin-top: 12px;">
                          <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                          <button type="submit" name="delete_comment" value="delete_comment" class="delete-btn-modern" onclick="return confirm('Are you sure you want to delete this comment?');">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                              stroke-width="2">
                              <polyline points="3,6 5,6 21,6"></polyline>
                              <path d="M19,6V20a2,2 0 0,1 -2,2H7a2,2 0,0,1 -2,-2V6M8,6V4a2,2 0,0,1 2,-2h4a2,2 0,0,1 2,2V6">
                              </path>
                              <line x1="10" y1="11" x2="10" y2="17"></line>
                              <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>                           
                          </button>
                        </form>
                         <?php endif; ?>



                    </div>
                    <div class="review-stars">
                                <span class="stars"><?php echo generateStars($review['rating']); ?></span>
                            </div>
                </div>
                <p class="review-text"><?= htmlspecialchars($review['review_text'] ?: 'No review text provided.') ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
     <!-- Post comment textbox -->
            <form method="POST" id="review-form">
                <input type="hidden" name="baker_id" value="<?php echo $baker_id; ?>">
                <input type="hidden" name="rating" id="rating-input" value="0">
                <div class="comment-form-container">
                    <div class="comment-form">
                        <div class="comment-input-section">
                            <img src="<?= !empty($user_info['profile_image']) ? 'uploads/' . htmlspecialchars($user_info['profile_image']) : 'media/profile.png' ?>"
                                alt="<?php echo htmlspecialchars($user_info['full_name']); ?>" class="user-avatar">
                            <textarea id="comment" class="comment-textarea" name="review_text"
                                placeholder="Add a comment..." rows="1" required></textarea>
                        </div>
                        <div class="actions-section">
                            <div class="star-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <label>
                                        <input type="radio" name="rating" value="<?php echo $i; ?>" style="display:none;"
                                            required>
                                        <span class="star">★</span>
                                    </label>
                                <?php endfor; ?>    
                            </div>
                            <button type="submit" name="submit_review" class="post-btn">Post</button>
                        </div>
                    </div>
                </div>
            </form>
</section>
    </main>
    <?php include 'globalfooter.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating .star');
            const ratingInput = document.getElementById('rating-input');
            const form = document.getElementById('review-form');
            const commentTextarea = document.getElementById('comment');
            const chatInput = document.getElementById('chatInput');
            const attachmentInput = document.getElementById('attachmentInput');
            const imagePreview = document.getElementById('imagePreview');
            const chatForm = document.querySelector('.chat-input-container');

            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    ratingInput.value = index + 1;
                    stars.forEach((s, i) => {
                        s.style.color = i <= index ? '#f59e0b' : '#d1d5db';
                    });
                });
            });

            form.addEventListener('submit', function(event) {
                const rating = ratingInput.value;
                const reviewText = commentTextarea.value.trim();
                if (!rating || rating === '0') {
                    event.preventDefault();
                    alert('Please select a star rating.');
                    return;
                }
                if (!reviewText) {
                    event.preventDefault();
                    alert('Please enter a review comment.');
                    return;
                }
            });

            // Auto-resize textarea
            if (chatInput) {
                chatInput.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }

            // Auto-open modal if chat is active
            <?php if (isset($_GET['chat']) && $_GET['chat'] === 'open'): ?>
                document.getElementById('chatModal').classList.add('active');
                document.getElementById('chatInput').focus();
                const chatMessages = document.querySelector('.chat-messages');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            <?php endif; ?>
        });

        // Image preview for attachment
            if (attachmentInput && imagePreview) {
                attachmentInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.innerHTML = `<img src="${e.target.result}" alt="Image Preview" style="max-width: 100px; max-height: 100px; margin-top: 5px;">`;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.innerHTML = '';
                    }
                });
            }

            // Clear preview on form submission
            if (chatForm) {
                chatForm.addEventListener('submit', function() {
                    imagePreview.innerHTML = '';
                    attachmentInput.value = '';
                });
            }

    </script>
</body>
</html>