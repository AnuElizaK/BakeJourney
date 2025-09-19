<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'baker') {
    header("Location: index.php");
    exit();
}

// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message']) && isset($_SESSION['user_id'])) {
    $receiver_id = (int) $_POST['receiver_id'];
    $message = trim($_POST['message'] ?? '');
    $attachment = null;

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        // Create the uploads directory if it doesn't exist
        if (!is_dir($upload_dir))
            mkdir($upload_dir, 0755, true);
        //give a name to the uploaded file
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
    header("Location: customorder.php?chat_user_id=" . $receiver_id . "&chat=open#chatForm");
    exit;
}

// Fetch all customers who messaged the baker
$stmt = $conn->prepare("
    SELECT DISTINCT u.user_id, u.full_name, u.profile_image, 
           (SELECT message FROM messages m 
            WHERE (m.sender_id = u.user_id AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = u.user_id) 
            ORDER BY m.sent_at DESC LIMIT 1) AS latest_message,
           (SELECT sent_at FROM messages m 
            WHERE (m.sender_id = u.user_id AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = u.user_id) 
            ORDER BY m.sent_at DESC LIMIT 1) AS latest_sent_at
    FROM users u
    JOIN messages m ON u.user_id = m.sender_id OR u.user_id = m.receiver_id
    WHERE (m.receiver_id = ? OR m.sender_id = ?) AND u.user_id != ?
    ORDER BY latest_sent_at DESC
");
$stmt->bind_param("iiiiiii", $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']);
$stmt->execute();
$customers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch messages for the selected customer if chat is open
$messages = [];
$selected_customer = null;
if (isset($_GET['chat']) && $_GET['chat'] === 'open' && isset($_GET['chat_user_id']) && isset($_SESSION['user_id'])) {
    $chat_user_id = (int) $_GET['chat_user_id'];
    $stmt = $conn->prepare("
        SELECT message_id, sender_id, receiver_id, message, attachment, sent_at 
        FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY sent_at ASC
    ");
    $stmt->bind_param("iiii", $_SESSION['user_id'], $chat_user_id, $chat_user_id, $_SESSION['user_id']);
    $stmt->execute();
    $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Fetch selected customer details
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $chat_user_id);
    $stmt->execute();
    $selected_customer = $stmt->get_result()->fetch_assoc();
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | BakeJourney</title>
    <link rel="stylesheet" href="customorder.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />
</head>

<body>
    <?php include 'bakernavbar.php'; ?>
    <main class="container">
        <section class="messages-section">
            <h1>Custom Order Messages</h1>
            <?php if (empty($customers)): ?>
                <p>No messages from customers yet.</p>
            <?php else: ?>
                <div class="accordion">
                    <?php foreach ($customers as $customer): ?>
                        <div class="accordion-item">
                            <button class="accordion-header"
                                onclick="window.location.href='customorder.php?chat_user_id=<?= $customer['user_id'] ?>&chat=open'">
                                <div class="customer-info">
                                    <img src="<?= !empty($customer['profile_image']) ? 'Uploads/' . htmlspecialchars($customer['profile_image']) : 'media/profile.png' ?>"
                                        alt="<?= htmlspecialchars($customer['full_name']) ?>" class="customer-avatar">
                                    <div>
                                        <h4><?= htmlspecialchars($customer['full_name']) ?></h4>
                                        <p class="latest-message">
                                            <?= htmlspecialchars(substr($customer['latest_message'] ?? 'No messages yet', 0, 50)) . (strlen($customer['latest_message'] ?? '') > 50 ? '...' : '') ?>
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="accordion-time"><?= $customer['latest_sent_at'] ? timeAgo($customer['latest_sent_at']) : '' ?></span>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Chat Modal -->
            <?php if (isset($_GET['chat']) && $_GET['chat'] === 'open' && $selected_customer): ?>
                <div id="chatModal" class="chat-modal" style="display: flex;">
                    <div class="chat-container">
                        <div class="chat-header">
                            <img src="<?= !empty($selected_customer['profile_image']) ? 'uploads/' . htmlspecialchars($selected_customer['profile_image']) : 'media/profile.png' ?>"
                                alt="<?= htmlspecialchars($selected_customer['full_name']) ?>" class="baker-avatar">
                            <div class="baker-chat-info">
                               <h4 class="customer-name" onclick="toggleCustomerInfo()"><?= htmlspecialchars($selected_customer['full_name']) ?></h4>
                            </div>
                            <a href="customorder.php" class="chat-close" style="text-decoration: none;">&times;</a>
                        </div>
                        <div class="chat-messages" id="chatMessages">
                            <?php if (empty($messages)): ?>
                                <div class="message received">
                                    <div>Hi! 👋 Thanks for your interest in my products. How can I help you today?</div>
                                    <div class="message-time"><?= date('H:i') ?></div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($messages as $msg): ?>
                                    <div class="message <?= $msg['sender_id'] === $_SESSION['user_id'] ? 'sent' : 'received' ?>">
                                        <div><?= htmlspecialchars($msg['message']) ?></div>
                                        <?php if ($msg['attachment']): ?>
                                            <div><img src="uploads/<?= htmlspecialchars($msg['attachment']) ?>" alt="Attachment"
                                                    style="max-width: 200px;"></div>
                                        <?php endif; ?>
                                        <div class="message-time"><?= date('H:i', strtotime($msg['sent_at'])) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <form method="POST" enctype="multipart/form-data" class="chat-input-container" id="chatForm">
                            <input type="hidden" name="receiver_id" value="<?= $chat_user_id ?>">
                            <input type="hidden" name="baker_id" value="<?= $_SESSION['user_id'] ?>">
                            <textarea id="chatInput" name="message" class="chat-input" placeholder="Type a message..."
                                rows="1"></textarea>

                            <div id="imagePreview" class="image-preview"></div>
                            <input type="file" id="attachmentInput" name="attachment" accept="image/*"
                                style="display: none;">
                            <button type="button" class="attach-button"
                                onclick="document.getElementById('attachmentInput').click()">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path
                                        d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.19 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" />
                                </svg>
                            </button>

                            <button type="submit" name="send_message" class="send-button">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Customer Info Modal -->
<div id="customerInfoModal" class="customer-info-modal" style="display: none;">
    <div class="customer-info-container">
        <div class="customer-info-header">
            <h3>Customer Info</h3>
            <button onclick="toggleCustomerInfo()" class="info-close">&times;</button>
        </div>
        <div class="customer-info-content">
            <img src="<?= !empty($selected_customer['profile_image']) ? 'Uploads/' . htmlspecialchars($selected_customer['profile_image']) : 'media/profile.png' ?>"
                 alt="<?= htmlspecialchars($selected_customer['full_name']) ?>" class="customer-info-avatar">
            <h4><?= htmlspecialchars($selected_customer['full_name']) ?></h4>
            <?php if (!empty($selected_customer['email'])): ?>
                <p><strong>Email:</strong> <?= htmlspecialchars($selected_customer['email']) ?></p>
            <?php endif; ?>
            <?php if (!empty($selected_customer['phone'])): ?>
                <p><strong>Phone:</strong> <?= htmlspecialchars($selected_customer['phone']) ?></p>
            <?php endif; ?>
            <?php if (!empty($selected_customer['bio'])): ?>
                <p><strong>Bio:</strong> <?= htmlspecialchars($selected_customer['bio']) ?></p>
            <?php endif; ?>
            <?php if (!empty($selected_customer['state'])): ?>
                <p><strong>State:</strong> <?= htmlspecialchars($selected_customer['state']) ?></p>
            <?php endif; ?>
            <?php if (!empty($selected_customer['district'])): ?>
                <p><strong>District:</strong> <?= htmlspecialchars($selected_customer['district']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
            <?php endif; ?>
        </section>
    </main>
    <?php include 'globalfooter.php'; ?>

    <script>
         function toggleCustomerInfo() {
        const modal = document.getElementById('customerInfoModal');
        modal.style.display = modal.style.display === 'none' ? 'flex' : 'none';
    }


        document.addEventListener('DOMContentLoaded', function () {
            const chatInput = document.getElementById('chatInput');
            const attachmentInput = document.getElementById('attachmentInput');
            const imagePreview = document.getElementById('imagePreview');
            const chatForm = document.querySelector('.chat-input-container');

            // Auto-resize textarea
            if (chatInput) {
                chatInput.addEventListener('input', function () {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }

            // Image preview for attachment
            if (attachmentInput && imagePreview) {
                attachmentInput.addEventListener('change', function () {
                    const file = this.files[0];
                    imagePreview.innerHTML = ''; // Clear previous preview
                    if (file && file.type.startsWith('image/')) {
                        // Optional: Validate file size (e.g., max 5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            alert('File is too large. Maximum size is 5MB.');
                            this.value = ''; // Clear input for invalid file
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            imagePreview.innerHTML = `<img src="${e.target.result}" alt="Image Preview" style="max-width: 100px; max-height: 100px; margin-top: 5px;">`;
                        };
                        reader.onerror = function () {
                            alert('Error reading file. Please try again.');
                            attachmentInput.value = ''; // Clear input on error
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Clear preview after successful submission (optional)
            if (chatForm) {
                chatForm.addEventListener('submit', function (event) {
                    // Validate that at least a message or file is provided
                    const message = document.getElementById('chatInput').value.trim();
                    if (!message && !attachmentInput.files.length) {
                        event.preventDefault(); // Prevent submission if both are empty
                        alert('Please enter a message or select a file.');
                        return;
                    }
                    // Do NOT clear attachmentInput.value here to ensure file is sent
                    imagePreview.innerHTML = ''; // Clear preview to reset UI
                });
            }

            // Auto-open modal and scroll to bottom
            <?php if (isset($_GET['chat']) && $_GET['chat'] === 'open'): ?>
                document.getElementById('chatModal').classList.add('active');
                document.getElementById('chatInput').focus();
                const chatMessages = document.querySelector('.chat-messages');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            <?php endif; ?>
        });



    </script>

</body>

</html>