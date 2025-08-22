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

if (!isset($_GET['product_id'])) {
    echo "Product not found.";
    exit;
}

$product_id = $_GET['product_id'];
$sql = "SELECT p.*, b.*, u.*
        FROM products p 
        JOIN bakers b ON p.baker_id = b.baker_id
        JOIN users u ON b.user_id = u.user_id 
        WHERE p.product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();

// Fetch all reviews for this product
$review_stmt = $conn->prepare("
    SELECT r.*, u.*
    FROM reviews r
    JOIN users u ON r.customer_id = u.user_id
    WHERE r.product_id = ?
    ORDER BY r.review_date DESC
");
$review_stmt->bind_param("i", $product_id);
$review_stmt->execute();
$reviews = $review_stmt->get_result();

// Get average rating + count
$rating_stmt = $conn->prepare("
    SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews
    FROM reviews
    WHERE product_id = ?
");
$rating_stmt->bind_param("i", $product_id);
$rating_stmt->execute();
$rating_result = $rating_stmt->get_result()->fetch_assoc();
$avg_rating = round($rating_result['avg_rating'], 1);
$total_reviews = $rating_result['total_reviews'];

// Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    $customer_id = $_SESSION['user_id'];  // must be logged in
    $rating = (int) $_POST['rating'];
    $comments = $_POST['comments'];

    if ($rating >= 1 && $rating <= 5 && !empty($comments)) {
        $stmt = $conn->prepare("INSERT INTO reviews (product_id, customer_id, rating, comments) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $product_id, $customer_id, $rating, $comments);
        $stmt->execute();
        header("Location: productinfopage.php?product_id=".$product_id."#reviews"); // refresh to avoid resubmission
        exit;
    }
}

// Function to generate star HTML
function generateStars($rating, $maxStars = 5) {
    $stars = '';
    for ($i = 1; $i <= $maxStars; $i++) {
        $stars .= ($i <= $rating) ? '★' : '☆';
    }
    return $stars;
}

// Function to format relative time
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'just now';
    if ($time < 3600) return floor($time/60) . ' minutes ago';
    if ($time < 86400) return floor($time/3600) . ' hours ago';
    if ($time < 2592000) return floor($time/86400) . ' days ago';
    if ($time < 31104000) return floor($time/2592000) . ' months ago';
    return floor($time/31104000) . ' years ago';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - BakeJourney</title>
    <link rel="stylesheet" href="productinfopage.css">
</head>

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
    <div class="container">
        <!-- Product Main Info -->
        <div class="product-card">
            <div class="product-header">
                <img src="<?= !empty($product['image']) ? 'uploads/' . htmlspecialchars($product['image']) : 'media/pastry.png' ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">

                <div class="product-info">
                    <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <div class="product-category"><?php echo htmlspecialchars('Category • ' . $product['category']); ?></div>

                    <div class="product-meta">
                        <div class="meta-item">
                            <div class="meta-value">127</div>
                            <div class="meta-label">Reviews</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value">2.5</div>
                            <div class="meta-label">Hours Prep</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-value"><?php echo htmlspecialchars($product['weight']); ?></div>
                            <div class="meta-label">Weight</div>
                        </div>
                    </div>

                    <div class="rating">
                        <div class="stars">★★★★★</div>
                        <span class="rating-text">5.0 • 98% satisfaction rate</span>
                    </div>

                    <div class="baker-info">
                        <img src="<?= !empty($product['profile_image']) ? 'uploads/' . htmlspecialchars($product['profile_image']) : 'media/baker.png' ?>"
                            alt="<?php echo htmlspecialchars($product['full_name']); ?>" class="baker-avatar">
                        <div class="baker-details">
                            <h4>Made by <a href="bakerinfopage.php?baker_id=<?= $product['baker_id']; ?>"
                                    style="color:orange; text-decoration:none;">
                                    <?= htmlspecialchars($product['brand_name'] ?: $product['full_name']) ?></a></h4>
                            <p>📍 <?php echo htmlspecialchars($product['district'] ?? 'Location not specified'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-actions">
                <div class="price">₹<?php echo htmlspecialchars($product['price']); ?></div>
                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                </form>
                <button onclick="messageModal()" class="btn btn-secondary">Message Baker</button>
            </div>
        </div>

        <!-- Product Description -->
        <div class="section-card">
            <h2 class="section-title">Product Description</h2>
            <div class="product-description">
                <?php if ($product['description']): ?>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <?php else: ?>
                    <p>No description available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Ingredients -->
        <!-- <div class="section-card">
            <h2 class="section-title">Ingredients</h2>
            <div class="ingredients-grid">
                <div class="ingredient-item">
                    <div class="ingredient-name">Organic Bread Flour</div>
                    <div class="ingredient-amount">500g</div>
                </div>
                <div class="ingredient-item">
                    <div class="ingredient-name">Sourdough Starter</div>
                    <div class="ingredient-amount">100g</div>
                </div>
                <div class="ingredient-item">
                    <div class="ingredient-name">Filtered Water</div>
                    <div class="ingredient-amount">350ml</div>
                </div>
                <div class="ingredient-item">
                    <div class="ingredient-name">Sea Salt</div>
                    <div class="ingredient-amount">10g</div>
                </div>
                <div class="ingredient-item">
                    <div class="ingredient-name">Olive Oil</div>
                    <div class="ingredient-amount">15ml</div>
                </div>
            </div>
        </div> -->

<!-- Customer Reviews -->
<div class="section-card" id="reviews">
    <div class="reviews-header">
        <h2 class="section-title">Customer Reviews</h2>
        <div class="rating">
            <div class="stars">
                <?php echo generateStars(round($avg_rating)); ?>
            </div>
            <span class="rating-text">
                <?php echo $avg_rating; ?> out of 5 stars • <?php echo $total_reviews; ?> reviews
            </span>
        </div>
    </div>

    <?php if ($reviews->num_rows > 0): ?>
        <?php while ($rev = $reviews->fetch_assoc()): ?>
            <div class="review-item">
                <div class="review-header">
                    <div class="reviewer-info">
                        <img src="<?= !empty($rev['profile_image']) ? 'uploads/' . htmlspecialchars($rev['profile_image']) : 'media/baker.png' ?>"
                             alt="<?php echo htmlspecialchars($rev['full_name']); ?>" class="reviewer-avatar">
                        <div>
                            <div class="reviewer-name"><?php echo htmlspecialchars($rev['full_name']); ?></div>
                            <div class="review-date"><?php echo timeAgo($rev['review_date']); ?></div>
                        </div>
                    </div>
                    <div class="review-stars">
                        
                             <span class="stars"><?php echo generateStars($rev['rating']); ?></span> 
                       
                    </div>
                </div>
                <div class="review-text">
                    <?php echo nl2br(htmlspecialchars($rev['comments'])); ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No reviews yet. Be the first to leave one!</p>
    <?php endif; ?>

    <!-- Post comment textbox -->
    
        <form method="POST" id="review-form">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <input type="hidden" name="rating" id="rating-input" value="0">
            <div class="comment-form-container">
                <div class="comment-form">
                    <div class="comment-input-section">
                        <img src="<?= !empty($product['profile_image']) ? 'uploads/' . htmlspecialchars($product['profile_image']) : 'media/baker.png' ?>"
                             alt="<?php echo htmlspecialchars($product['full_name']); ?>" class="user-avatar">
                        <textarea id="comment" class="comment-textarea" name="comments"
                                  placeholder="Add a comment..." rows="1" required></textarea>
                    </div>
                    
                    <div class="actions-section">
                        <div class="star-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label>
                                    <input type="radio" name="rating" value="<?php echo $i; ?>" style="display:none;" required>
                                    <span class="star">★</span>
                                </label>
                            <?php endfor; ?>
                        </div>
                        <button type="submit"  name="submit_review" class="post-btn">Post</button>
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
                <img src="<?= !empty($product['profile_image']) ? 'uploads/' . htmlspecialchars($product['profile_image']) : 'https://images.unsplash.com/photo-1675285458906-26993548039c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D' ?>"
                    alt="<?php echo htmlspecialchars($product['full_name']); ?>" class="baker-avatar">
                <div class="baker-chat-info">
                    <h4 id="bakerName"><a href="bakerinfopage.php?baker_id=<?= $product['baker_id']; ?>"
                            style="color:white; text-decoration:none;">
                            <?= htmlspecialchars($product['brand_name'] ?: $product['full_name']) ?></a></h4>
                    <div class="baker-status" id="bakerStatus">Online • Typically replies within minutes</div>
                </div>
                <button class="chat-close" onclick="closeChatModal()">&times;</button>
            </div>

            <!-- Chat Messages -->
            <div class="chat-messages" id="chatMessages">
                <div class="message received">
                    <div>Hi! 👋 Thanks for your interest in my products. How can I help you today?</div>
                    <div class="message-time">2:30 PM</div>
                </div>
                <div class="product-reference">
                    <strong>Product:</strong> <span
                        id="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?> -
                        ₹<?php echo $product['price']; ?></span>
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


    <script>
      document.addEventListener('DOMContentLoaded', function() {
    const commentTextarea = document.getElementById('comment');
    const ratingInput = document.getElementById('rating-input');
    const stars = document.querySelectorAll('.star');
    let currentRating = 0;

 // Auto-resize textarea
    commentTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        updatePostButton();
    });

    // Star rating functionality
    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            currentRating = index + 1;
            ratingInput.value = currentRating;
            updateStars();
            updatePostButton();
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
    document.getElementById('review-form').addEventListener('submit', function(e) {
        const comment = commentTextarea.value.trim();
        
        if (currentRating === 0) {
            e.preventDefault();
            alert('Please select a rating!');
            return false;
        }
        
        if (comment === '') {
            e.preventDefault();
            alert('Please write a comment!');
            return false;
        }
        
       
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

            // Focus on input
            setTimeout(() => {
                document.getElementById('chatInput').focus();
            }, 300);

            // Simulate baker coming online
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

            // Add user message
            addMessage(message, 'sent');
            input.value = '';
            adjustTextareaHeight(input);

            // Show typing indicator and simulate baker response
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

        // Event listeners
        document.getElementById('chatInput').addEventListener('input', function () {
            adjustTextareaHeight(this);
        });

        document.getElementById('chatInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Close modal when clicking outside
        document.getElementById('chatModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeChatModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeChatModal();
            }
        });

        // Prevent modal from closing when clicking inside chat container
        document.querySelector('.chat-container').addEventListener('click', function (e) {
            e.stopPropagation();
        });


        let quantity = 1;

        function changeQuantity(change) {
            quantity += change;
            if (quantity < 1) quantity = 1;
            if (quantity > 10) quantity = 10;

            document.getElementById('quantity').textContent = quantity;

            // Update price
            const basePrice = 40;
            const totalPrice = basePrice * quantity;
            document.querySelector('.price').textContent = `₹${totalPrice.toFixed(2)}`;
        }

        // Add smooth scrolling for better UX
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
    </script>
</body>

</html>