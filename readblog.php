<?php
session_start();
include 'db.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer' && $_SESSION['role'] !== 'baker') {
    header("Location: index.php"); // Redirect to login if not authorized
    exit();
}

// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

$user_id = $_SESSION['user_id'];
if ($user_id) {
    // Get user information
    $user_info_stmt = $conn->prepare("SELECT full_name, profile_image FROM users WHERE user_id = ?");
    $user_info_stmt->bind_param("i", $user_id);
    $user_info_stmt->execute();
    $user_info_result = $user_info_stmt->get_result();
    $user_info = $user_info_result->fetch_assoc();
}

if (!isset($_GET['blog_id'])) {
    echo "Post not found.";
    exit;
}

// Get liked blogs for this user
$like_stmt = $conn->prepare("SELECT blog_id FROM blog_likes WHERE user_id = ?");
$like_stmt->bind_param("i", $user_id);
$like_stmt->execute();
$like_result = $like_stmt->get_result();

$liked_blogs = [];
while ($like_item = $like_result->fetch_assoc()) {
    $liked_blogs[] = $like_item['blog_id'];
}

$blog_id = $_GET['blog_id'];
$sql = "SELECT bg.*, b.*, u.*,
        (SELECT COUNT(*) FROM blog_likes bgl WHERE bgl.blog_id = bg.blog_id) AS like_count
        FROM blog bg
        JOIN bakers b ON bg.user_id = b.baker_id
        JOIN users u ON b.user_id = u.user_id
        WHERE bg.blog_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Blog not found.";
    exit;
}

$blog = $result->fetch_assoc();
$is_liked = in_array($blog['blog_id'], $liked_blogs);

// Fetch all comments for this blog
$comment_stmt = $conn->prepare("
    SELECT c.*, u.*
    FROM blog_comments c
    JOIN users u ON c.user_id = u.user_id
    WHERE c.blog_id = ?
    ORDER BY c.comment_date DESC
");
$comment_stmt->bind_param("i", $blog_id);
$comment_stmt->execute();
$comments = $comment_stmt->get_result();

// Get total like count
$social_stmt = $conn->prepare("
    SELECT COUNT(*) as total_likes
    FROM blog_likes bgl
    WHERE bgl.blog_id = ?
");
$social_stmt->bind_param("i", $blog_id);
$social_stmt->execute();
$social_result = $social_stmt->get_result()->fetch_assoc();
$total_likes = round($social_result['total_likes'], 1);

// Get total likes and comments count
$social_stmt = $conn->prepare("
    SELECT COUNT(*) as total_comments
    FROM blog_comments bc
    WHERE bc.blog_id = ?
");
$social_stmt->bind_param("i", $blog_id);
$social_stmt->execute();
$social_result = $social_stmt->get_result()->fetch_assoc();
$total_comments = $social_result['total_comments'];

// Handle comments submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'])) {
    $user_id = $_SESSION['user_id'];
    $comments = $_POST['comments'];

    if (!empty($comments)) {
        $stmt = $conn->prepare("INSERT INTO blog_comments (blog_id, user_id, comment_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $blog_id, $user_id, $comments);
        $stmt->execute();
        header("Location: readblog.php?blog_id=" . $blog_id . "#comments");
        exit;
    }
}

// Handle like/unlike action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'toggle_like') {
    header('Content-Type: application/json');

    $blog_id = intval($_POST['blog_id']);

    try {
        // Check if user already liked this blog
        $check_stmt = $conn->prepare("SELECT like_id FROM blog_likes WHERE blog_id = ? AND user_id = ?");
        $check_stmt->bind_param("ii", $blog_id, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Unlike - remove the like
            $delete_stmt = $conn->prepare("DELETE FROM blog_likes WHERE blog_id = ? AND user_id = ?");
            $delete_stmt->bind_param("ii", $blog_id, $user_id);
            $delete_stmt->execute();
            $liked = false;
        } else {
            // Like - add the like
            $insert_stmt = $conn->prepare("INSERT INTO blog_likes (blog_id, user_id) VALUES (?, ?)");
            $insert_stmt->bind_param("ii", $blog_id, $user_id);
            $insert_stmt->execute();
            $liked = true;
        }

        // Get updated like count
        $count_stmt = $conn->prepare("SELECT COUNT(*) as like_count FROM blog_likes WHERE blog_id = ?");
        $count_stmt->bind_param("i", $blog_id);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $like_count = $count_result->fetch_assoc()['like_count'];

        echo json_encode([
            'success' => true,
            'liked' => $liked,
            'like_count' => $like_count
        ]);
        exit();

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Database error']);
        exit();
    }
}

// Function to format relative time
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['blog_title']); ?> | BakeJourney</title>
    <link rel="stylesheet" href="readblog.css">

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
    <div class="container">
        <!-- Blog Main Info -->
        <div class="blog-card">
            <div class="blog-header">
                <div class="blog-image-container">
                    <img src="<?= !empty($blog['blog_image']) ? 'uploads/' . htmlspecialchars($blog['blog_image']) : 'media/pastry.png' ?>"
                        alt="<?php echo htmlspecialchars($blog['blog_title']); ?>" class="blog-image">
                    <div class="meta-item">
                        <button class="social-btn like-btn <?= $is_liked ? 'liked' : '' ?>"
                            data-blog-id="<?= $blog['blog_id'] ?>">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <div class="like-count"><?= $blog['like_count'] ?></div>
                        </button>
                    </div>
                </div>

                <div class="blog-info">
                    <h1 class="blog-title"><?php echo htmlspecialchars($blog['blog_title']); ?></h1>
                    <div>
                        <span
                            class="blog-category <?= strtolower($blog['category']) ?>"><?php echo htmlspecialchars('Category • ' . $blog['category']); ?>
                        </span>
                    </div>

                    <div class="blog-meta">
                        <div class="meta-item">
                            <div class="meta-value"><?php echo $total_comments; ?></div>
                            <div class="meta-label">Comments</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value"><?php echo htmlspecialchars(number_format($blog['read_time'], 1) . ' minutes'); ?></div>
                            <div class="meta-label">Read</div>
                        </div>
                    </div>

                    <div class="baker-info">
                        <img src="<?= !empty($blog['profile_image']) ? 'Uploads/' . htmlspecialchars($blog['profile_image']) : 'media/baker.png' ?>"
                            alt="<?php echo htmlspecialchars($blog['full_name']); ?>" class="baker-avatar">
                        <div class="baker-details">
                            <h4>By <a href="bakerinfopage.php?baker_id=<?= $blog['baker_id']; ?>"
                                    style="color: #f59e0b; text-decoration: none;">
                                    <?= htmlspecialchars($blog['full_name']) ?></a></h4>
                            <p>📍 <?php echo htmlspecialchars($blog['district'] ?? 'Location not specified'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="blog-content">
                <?php if ($blog['content']): ?>
                    <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
                <?php else: ?>
                    <p>Content not available yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Blog Comments -->
        <div class="section-card" id="comments">
            <div class="comments-header">
                <h2 class="section-title">Comments</h2>
                <div class="rating">
                    <span class="rating-text">
                        <?php echo $total_likes; ?> Likes • <?php echo $total_comments; ?> Comments
                    </span>
                </div>
            </div>

            <?php if ($comments->num_rows > 0): ?>
                <?php while ($rev = $comments->fetch_assoc()): ?>
                    <div class="comment-item">
                        <div class="comment-header">
                            <div class="commenter-info">
                                <img src="<?= !empty($rev['profile_image']) ? 'uploads/' . htmlspecialchars($rev['profile_image']) : 'media/baker.png' ?>"
                                    alt="<?php echo htmlspecialchars($rev['full_name']); ?>" class="commenter-avatar">
                                <div>
                                    <div class="commenter-name"><?php echo htmlspecialchars($rev['full_name']); ?></div>
                                    <div class="comment-date"><?php echo timeAgo($rev['comment_date']); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="comment-text">
                            <?php echo nl2br(htmlspecialchars($rev['comment_text'])); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No comments yet. Be the first to leave one!</p>
            <?php endif; ?>

            <!-- Post comment textbox -->
            <form method="POST" id="comment-form">
                <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>">
                <input type="hidden" name="rating" id="rating-input" value="0">
                <div class="comment-form-container">
                    <div class="comment-form">
                        <div class="comment-input-section">
                            <img src="<?= !empty($user_info['profile_image']) ? 'uploads/' . htmlspecialchars($user_info['profile_image']) : 'media/profile.png' ?>"
                                alt="<?php echo htmlspecialchars($user_info['full_name']); ?>" class="user-avatar">
                            <textarea id="comment" class="comment-textarea" name="comments"
                                placeholder="Add a comment..." rows="1" required></textarea>
                        </div>
                        <div class="actions-section">
                            <button type="submit" name="submit_comment" class="post-btn">Post</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Chat Modal -->
    <div id="chatModal" class="chat-modal">
        <div class="chat-container">
            <!-- Chat Header -->
            <div class="chat-header">
                <img src="<?= !empty($blog['profile_image']) ? 'uploads/' . htmlspecialchars($blog['profile_image']) : 'media/baker.png' ?>"
                    alt="<?php echo htmlspecialchars($blog['full_name']); ?>" class="baker-avatar">
                <div class="baker-chat-info">
                    <h4 id="bakerName"><a href="bakerinfopage.php?baker_id=<?= $blog['baker_id']; ?>"
                            style="color:white; text-decoration:none;">
                            <?= htmlspecialchars($blog['brand_name'] ?: $blog['full_name']) ?></a></h4>
                    <div class="baker-status" id="bakerStatus">Online • Typically replies within minutes</div>
                </div>
                <button class="chat-close" onclick="closeChatModal()">&times;</button>
            </div>

            <!-- Chat Messages -->
            <div class="chat-messages" id="chatMessages">
                <div class="message received">
                    <div>Hi! 👋 Thanks for your interest in my blog. How can I help you today?</div>
                    <div class="message-time">2:30 PM</div>
                </div>
                <div class="blog-reference">
                    <strong>blog:</strong> <span id="<?php echo $blog['blog_id']; ?>"><?php echo $blog['name']; ?> -
                        ₹<?php echo $blog['price']; ?></span>
                </div>
            </div>

            <!-- Typing Indicator -->
            <div class="typing-indicator" id="typingIndicator">
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="chat-input-container">
                <textarea id="chatInput" class="chat-input" placeholder="Type a message..." rows="1"></textarea>
                <button class="send-button" onclick="sendMessage()" id="sendBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <?php include 'globalfooter.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const commentTextarea = document.getElementById('comment');
            const ratingInput = document.getElementById('rating-input');
            const stars = document.querySelectorAll('.star');
            let currentRating = 0;

            // Auto-resize textarea
            commentTextarea.addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });

            // Star rating functionality
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    currentRating = index + 1;
                    ratingInput.value = currentRating;
                    updateStars();
                });

                star.addEventListener('mouseover', () => {
                    highlightStars(index + 1);
                });
            });

            document.querySelector('.star-rating').addEventListener('mouseleave', () => {
                updateStars();
            });

            function highlightStars(rating) {
                stars.forEach((star, index) => {
                    star.classList.toggle('active', index < rating);
                });
            }

            function updateStars() {
                stars.forEach((star, index) => {
                    star.classList.toggle('active', index < currentRating);
                });
            }

            // Form submission validation
            document.getElementById('comment-form').addEventListener('submit', function (e) {
                const comment = commentTextarea.value.trim();

                if (comment === '') {
                    e.preventDefault();
                    alert('Please write a comment!');
                    return false;
                }
            });

            // Like button functionality
            const likeButton = document.querySelector('.like-btn');
            likeButton.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent triggering other click events
                const blogId = this.dataset.blogId;
                const likeCountSpan = this.querySelector('.like-count');

                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=toggle_like&blog_id=${blogId}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.classList.toggle('liked', data.liked);
                            likeCountSpan.textContent = data.like_count;
                        } else {
                            alert('Error: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while processing your request.');
                    });
            });

            // Chat functionality
            let chatMessages = [];
            let bakerResponses = [
                "Absolutely! I'd be happy to prepare that for you. When would you need it?",
                "That's one of my specialties! I use traditional techniques and organic ingredients.",
                "I can definitely customize that order. What modifications would you like?",
                "Perfect! I typically need 24-48 hours notice for fresh orders. Does that work for you?",
                "Great choice! That's been very popular. I can have it ready by tomorrow afternoon.",
                "I appreciate your interest! Feel free to ask any questions about ingredients or preparation.",
                "Wonderful! I'll make sure it's perfectly fresh for you. Any dietary restrictions I should know about?",
                "Thank you! I take pride in using only the finest ingredients. When would you like to place the order?"
            ];

            function messageModal() {
                const modal = document.getElementById('chatModal');
                modal.classList.add('active');
                setTimeout(() => {
                    document.getElementById('chatInput').focus();
                }, 300);
                setTimeout(() => {
                    document.getElementById('bakerStatus').textContent = 'Online now';
                }, 1000);
            }

            function closeChatModal() {
                const modal = document.getElementById('chatModal');
                modal.classList.remove('active');
            }

            function sendMessage() {
                const input = document.getElementById('chatInput');
                const message = input.value.trim();

                if (!message) return;

                addMessage(message, 'sent');
                input.value = '';
                adjustTextareaHeight(input);

                setTimeout(() => {
                    showTypingIndicator();
                    setTimeout(() => {
                        hideTypingIndicator();
                        const response = bakerResponses[Math.floor(Math.random() * bakerResponses.length)];
                        addMessage(response, 'received');
                    }, 1500 + Math.random() * 2000);
                }, 500);
            }

            function addMessage(text, type) {
                const messagesContainer = document.getElementById('chatMessages');
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${type}`;
                const now = new Date();
                const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                messageDiv.innerHTML = `
                    <div>${text}</div>
                    <div class="message-time">${timeString}</div>
                `;
                messagesContainer.appendChild(messageDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function showTypingIndicator() {
                const indicator = document.getElementById('typingIndicator');
                const messagesContainer = document.getElementById('chatMessages');
                indicator.style.display = 'block';
                messagesContainer.appendChild(indicator);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function hideTypingIndicator() {
                const indicator = document.getElementById('typingIndicator');
                indicator.style.display = 'none';
            }

            function adjustTextareaHeight(textarea) {
                textarea.style.height = 'auto';
                textarea.style.height = Math.min(textarea.scrollHeight, 100) + 'px';
            }

            document.getElementById('chatInput').addEventListener('input', function () {
                adjustTextareaHeight(this);
            });

            document.getElementById('chatInput').addEventListener('keypress', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            document.getElementById('chatModal').addEventListener('click', function (e) {
                if (e.target === this) {
                    closeChatModal();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeChatModal();
                }
            });

            document.querySelector('.chat-container').addEventListener('click', function (e) {
                e.stopPropagation();
            });

            let quantity = 1;

            function changeQuantity(change) {
                quantity += change;
                if (quantity < 1) quantity = 1;
                if (quantity > 10) quantity = 10;
                document.getElementById('quantity').textContent = quantity;
                const basePrice = <?php echo $blog['price']; ?>;
                const totalPrice = basePrice * quantity;
                document.querySelector('.price').textContent = `₹${totalPrice.toFixed(2)}`;
            }

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>