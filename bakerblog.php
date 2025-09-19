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

// Get liked blog for this customer
$like_stmt = $conn->prepare("SELECT blog_id FROM blog_likes WHERE user_id = ?");
$like_stmt->bind_param("i", $user_id);
$like_stmt->execute();
$like_result = $like_stmt->get_result();

$liked_blog = [];
while ($like_item = $like_result->fetch_assoc()) {
    $liked_blog[] = $like_item['blog_id'];
}

$stmt = $conn->prepare("
    SELECT b.*, u.full_name, ba.baker_id,
           (SELECT COUNT(*) FROM blog_likes bl WHERE bl.blog_id = b.blog_id) AS like_count,
           (SELECT COUNT(*) FROM blog_comments bc WHERE bc.blog_id = b.blog_id) AS comment_count
    FROM blog b
    JOIN users u ON b.user_id = u.user_id
    JOIN bakers ba ON u.user_id = ba.user_id
    ORDER BY RAND()
");
$stmt->execute();
$result = $stmt->get_result();

// Like function
if (isset($_POST['action']) && $_POST['action'] === 'toggle_like') {
    header('Content-Type: application/json');

    $blog_id = intval($_POST['blog_id']);

    try {
        // Check if user already liked this blog
        $check_stmt = $conn->prepare("SELECT b_like_id FROM blog_likes WHERE blog_id = ? AND user_id = ?");
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
        $count_stmt = $conn->prepare("SELECT COUNT(*) AS like_count FROM blog_likes WHERE blog_id = ?");
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

// --- Blog Image Remove (AJAX, for cancel/remove only) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_blog_image']) && !isset($_POST['save_changes'])) {
    if (isset($_FILES['blog_image_cropped'])) {
        $imgPath = $_FILES['blog_image_cropped']['tmp_name'];
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }
    }
    echo '<script>alert("✅ Blog image removed."); window.location.href = "bakerblog.php";</script>';
    exit;
}

// Comment submission
if (isset($_POST['action']) && $_POST['action'] === 'add_comment') {
    header('Content-Type: application/json');

    $blog_id = intval($_POST['blog_id']);
    $comment_text = trim($_POST['comment_text']);
    $user_id = $_SESSION['user_id'];

    try {
        if (empty($comment_text)) {
            echo json_encode(['success' => false, 'error' => 'Comment cannot be empty']);
            exit();
        }

        // Get user's full name for comment display
        $user_stmt = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        $user = $user_result->fetch_assoc();
        $full_name = $user['full_name'] ?? 'You';

        // Insert comment into database
        $stmt = $conn->prepare("INSERT INTO blog_comments (blog_id, user_id, comment_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $blog_id, $user_id, $comment_text);
        $stmt->execute();

        // Get updated comment count
        $count_stmt = $conn->prepare("SELECT COUNT(*) AS comment_count FROM blog_comments WHERE blog_id = ?");
        $count_stmt->bind_param("i", $blog_id);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $comment_count = $count_result->fetch_assoc()['comment_count'];

        echo json_encode([
            'success' => true,
            'comment' => [
                'comment_text' => htmlspecialchars($comment_text),
                'author' => htmlspecialchars($full_name),
                'comment_date' => date('Y-m-d H:i:s')
            ],
            'comment_count' => $comment_count
        ]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog | BakeJourney</title>
    <meta name="description" content="BakeJourney - The Home Baker's Marketplace" />
    <link rel="stylesheet" href="bakerblog.css">
    <!-- Cropper.js CSS for blog image upload -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="blog-cropper.css">
    <!-- <link rel="stylesheet" href="edit-blog-cropper.css"> -->
</head>

<!-- Sticky Navigation Bar -->
<?php include 'bakernavbar.php'; ?>

<body>
    <section class="blog" id="blog">
        <div class="container">
            <div class="header">
                <h2>Community Blog</h2>
                <p>Discover recipes, stories, and updates from our amazing baker community</p>
            </div>

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterblogs('all')">All</button>
                <button class="filter-tab" onclick="filterblogs('your')">Your Blogs</button>
            </div>

            <div class="filters">
                <div class="blog-search-box">
                    <input type="search" placeholder="Search or filter posts..." class="blog-search-input">
                    <button onclick="toggleUploadModal()" class="create-blog-btn">+ Create New Post</button>
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">All Posts</button>
                    <button class="filter-btn" data-filter="recipes">Recipes</button>
                    <button class="filter-btn" data-filter="stories">Stories</button>
                    <button class="filter-btn" data-filter="tips">Tips & Tricks</button>
                    <button class="filter-btn" data-filter="announcements">Announcements</button>
                </div>
            </div>

            <div class="blog-grid">
                <?php while ($blog = $result->fetch_assoc()): ?>
                    <?php
                    $is_liked = in_array($blog['blog_id'], $liked_blog);
                    // Fetch comments for this blog post
                    $comment_stmt = $conn->prepare("
                        SELECT bc.comment_text, bc.comment_date, u.full_name
                        FROM blog_comments bc
                        JOIN users u ON bc.user_id = u.user_id
                        WHERE bc.blog_id = ?
                        ORDER BY bc.comment_date DESC
                    ");
                    $comment_stmt->bind_param("i", $blog['blog_id']);
                    $comment_stmt->execute();
                    $comment_result = $comment_stmt->get_result();
                    ?>
                    <article class="blog-post" data-category="<?= htmlspecialchars($blog['category']) ?>">
                        <div class="post-image">
                            <img src="<?= !empty($blog['blog_image']) ? 'uploads/' . htmlspecialchars($blog['blog_image']) : 'media/pastry.png' ?>"
                                alt="<?= htmlspecialchars($blog['blog_title']) ?>">
                        </div>
                        <div class="post-content">
                            <div class="post-meta">
                                <span
                                    class="category-badge <?= strtolower($blog['category']) ?>"><?= htmlspecialchars($blog['category']) ?></span>
                                <span class="author" data-user-id="<?= $blog['user_id'] ?>"
                                    onclick="window.location.href='bakerinfopage.php?baker_id=<?= $blog['baker_id'] ?>'"
                                    title="Visit the author">By <?= htmlspecialchars($blog['full_name']) ?>
                                </span>
                            </div>
                            <h2 class="post-title"><?= htmlspecialchars($blog['blog_title']) ?></h2>
                            <p class="post-date">
                                <?= date('d M Y', strtotime($blog['created_at'])) ?>
                            </p>
                            <p class="post-excerpt">
                                <?php
                                $content = strip_tags($blog['content']);
                                $words = explode(' ', $content);
                                $max_words = 20;
                                if (count($words) > $max_words) {
                                    $excerpt = implode(' ', array_slice($words, 0, $max_words)) . '...';
                                } else {
                                    $excerpt = $content;
                                }
                                echo htmlspecialchars($excerpt);
                                ?>
                            </p>

                            <div class="post-actions">
                                <div class="action-group">
                                    <button class="action-btn like-btn <?= $is_liked ? 'liked' : '' ?>"
                                        onclick="toggleLike(this)" data-blog-id="<?= $blog['blog_id'] ?>">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        <span class="like-count"><?= $blog['like_count'] ?></span>
                                    </button>

                                    <button title="Read more to comment" class="action-btn comment-btn">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <span class="comment-count"><?= $blog['comment_count'] ?></span>
                                    </button>

                                    <button class="action-btn share-btn" onclick="sharePost(this)">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" stroke="currentColor"
                                                stroke-width="2" />
                                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" stroke="currentColor"
                                                stroke-width="2" />
                                            <circle cx="6" cy="12" r="3" fill="currentColor" />
                                            <circle cx="18" cy="6" r="3" fill="currentColor" />
                                            <circle cx="18" cy="18" r="3" fill="currentColor" />
                                        </svg>
                                        <span>Share</span>
                                    </button>
                                </div>
                                <button class="read-more-btn"
                                    onclick="window.location.href='readblog.php?blog_id=<?= $blog['blog_id']; ?>'">Read
                                    More</button>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <div id="no-blogs-message"
                style="display:none; text-align:center; color:#f59e0b; font-weight:600; margin:32px 0;">
                No posts found.
            </div>
        </div>
    </section>

    <!-- Upload Modal -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add New Blog Post</h2>
                <button onclick="toggleUploadModal()" class="close-btn">&times;</button>
            </div>

            <form class="modal-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Blog Title</label>
                    <input type="text" class="form-input" name="blog_title" placeholder="Enter blog title" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select class="form-select" name="category" required>
                        <option value="">Select category</option>
                        <option value="recipes">Recipes</option>
                        <option value="tips">Tips</option>
                        <option value="stories">Stories</option>
                        <option value="announcements">Announcements</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Content</label>
                    <textarea class="form-textarea" name="content" required
                        placeholder="Type your content..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Reading Time (in minutes)</label>
                    <input type="number" class="form-input read-time" name="reading_time" placeholder="How long to finish reading?" required>
                </div>

                <!-- Image upload field handled by blog-cropper.js -->
                <?php if (isset($_SESSION['uploaded_blog_image'])): ?>
                    <div style="margin:10px 0; color:green;">Image uploaded:
                        <?php echo htmlspecialchars($_SESSION['uploaded_blog_image']); ?>
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="button" onclick="toggleUploadModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary" name="create_blog">Create blog</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'globalfooter.php'; ?>

    <script>
        // Modal Functions
        function toggleUploadModal() {
            const modal = document.getElementById('uploadModal');
            modal.classList.toggle('active');
        }

        // Close modals when clicking outside
        window.onclick = function (event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                }
            });
        }

        // TabFilter functionality
        function filterblogs(type) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            // Get all blog posts
            const blogs = document.querySelectorAll('.blog-post');
            const noblogs = document.getElementById('no-blogs-message');
            let visibleCount = 0;

            // Get current user ID from PHP 
            const currentUserId = <?php echo json_encode($user_id); ?>;

            blogs.forEach(blog => {
                const blogUserId = parseInt(blog.querySelector('.author').dataset.userId || 0);
                if (type === 'all') {
                    blog.style.display = 'block';
                    visibleCount++;
                } else if (type === 'your' && blogUserId === currentUserId) {
                    blog.style.display = 'block';
                    visibleCount++;
                } else {
                    blog.style.display = 'none';
                }
            });

            // Show/hide no blogs message
            if (noblogs) {
                noblogs.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }


        // Blog Search
        document.querySelector('.blog-search-input').addEventListener('input', function (e) {
            const searchValue = e.target.value.toLowerCase();
            const blogs = document.querySelectorAll('.blog-post');
            const noblogs = document.getElementById('no-blogs-message');
            let visibleCount = 0;

            blogs.forEach(blog => {
                const title = blog.querySelector('.post-content h2').textContent.toLowerCase();
                const auth = blog.querySelector('.author').textContent.toLowerCase();
                const date = blog.querySelector('.post-date').textContent.toLowerCase();
                const desc = blog.querySelector('.post-excerpt').textContent.toLowerCase();
                if (title.includes(searchValue) || auth.includes(searchValue) || date.includes(searchValue) || desc.includes(searchValue)) {
                    blog.style.display = 'block';
                    visibleCount++;
                } else {
                    blog.style.display = 'none';
                }
            });
            if (noblogs) {
                noblogs.style.display = visibleCount === 0 ? 'block' : 'none';

            }
        });

        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // Filter posts
                const filter = btn.dataset.filter;
                const posts = document.querySelectorAll('.blog-post');
                let visibleCount = 0;
                posts.forEach(post => {
                    if (filter === 'all' || post.dataset.category === filter) {
                        post.style.display = 'block';
                        visibleCount++;
                    } else {
                        post.style.display = 'none';
                    }
                });
                const noblogs = document.getElementById('no-blogs-message');
                if (noblogs) {
                    noblogs.style.display = visibleCount === 0 ? 'block' : 'none';
                }
            });
        });

        // Like functionality
        function toggleLike(btn) {
            const blogId = btn.dataset.blogId;
            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=toggle_like&blog_id=${blogId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btn.classList.toggle('liked', data.liked);
                        const countSpan = btn.querySelector('.like-count');
                        countSpan.textContent = data.like_count;
                    } else {
                        alert('Error: ' + (data.error || 'Failed to update like'));
                    }
                })
                .catch(error => {
                    alert('Error: Failed to connect to server');
                });
        }

        // Comment submission
        document.querySelectorAll('.comment-submit').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const form = e.target.closest('.comment-form');
                const input = form.querySelector('.comment-input');
                const blogId = btn.dataset.blogId;
                const text = input.value.trim();

                if (text) {
                    fetch(window.location.href, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `action=add_comment&blog_id=${blogId}&comment_text=${encodeURIComponent(text)}`
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const commentContainer = form.parentElement;
                                const newComment = document.createElement('div');
                                newComment.className = 'comment';
                                newComment.innerHTML = `
                                <div class="comment-author">${data.comment.author}</div>
                                <div class="comment-text">${data.comment.comment_text}</div>
                            `;
                                commentContainer.insertBefore(newComment, form);
                                input.value = '';

                                // Update comment count
                                const post = commentContainer.closest('.blog-post');
                                const commentBtn = post.querySelector('.comment-count');
                                commentBtn.textContent = data.comment_count;
                            } else {
                                alert('Error: ' + (data.error || 'Failed to post comment'));
                            }
                        })
                        .catch(error => {
                            alert('Error: Failed to connect to server');
                        });
                }
            });
        });

        // Enter key for comment submission
        document.querySelectorAll('.comment-input').forEach(input => {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.target.nextElementSibling.click();
                }
            });
        });

        // Share functionality
        function sharePost(btn) {
            const post = btn.closest('.blog-post');
            const title = post.querySelector('.post-title').textContent;

            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: 'Check out this blog post from Sweet Spot Bakery',
                    url: window.location.href
                });
            } else {
                // Fallback - copy to clipboard
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    alert('Link copied to clipboard!');
                });
            }
        }
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_blog'])) {
        $title = $_POST['blog_title'];
        $category = $_POST['category'];
        $content = $_POST['content'];
        $image = null;
        if (isset($_FILES['blog_image_cropped']) && $_FILES['blog_image_cropped']['error'] == 0) {
            $fileTmp = $_FILES['blog_image_cropped']['tmp_name'];
            $fileType = mime_content_type($fileTmp);
            if ($fileType === 'image/jpeg') {
                $filename = 'blog_' . $user_id . '_' . time() . '.jpg';
                $dest = __DIR__ . '/uploads/' . $filename;
                if (move_uploaded_file($fileTmp, $dest)) {
                    $image = $filename;
                }
            }
        }
        // Proceed with insert
        $stmt = $conn->prepare("INSERT INTO blog (blog_title, category, content, user_id, blog_image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $title, $category, $content, $user_id, $image);

        if ($stmt->execute()) {
            echo "<script>alert('Blog created successfully!');window.location.href = 'bakerblog.php';</script>";
        } else {
            echo "<script>alert('Error in creating blog!');</script>";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_changes'])) {
        $id = $_POST['blog_id'];
        $updated_name = $_POST['blog_title'];
        $updated_category = $_POST['category'];
        $updated_content = $_POST['content'];
        $new_image = null;
        $remove_image = isset($_POST['remove_blog_image']) && $_POST['remove_blog_image'] === '1';

        // Handle new image upload
        if (isset($_FILES['blog_image_cropped']) && $_FILES['blog_image_cropped']['error'] == 0) {
            $fileTmp = $_FILES['blog_image_cropped']['tmp_name'];
            $fileType = mime_content_type($fileTmp);
            if ($fileType === 'image/jpeg') {
                $filename = 'blog_' . $user_id . '_' . time() . '.jpg';
                $dest = __DIR__ . '/uploads/' . $filename;
                if (move_uploaded_file($fileTmp, $dest)) {
                    $new_image = $filename;
                }
            }
        }

        // If remove image or new image, delete old image file
        if ($remove_image || $new_image) {
            $imgStmt = $conn->prepare("SELECT image FROM blog WHERE blog_id = ?");
            $imgStmt->bind_param("i", $id);
            $imgStmt->execute();
            $imgResult = $imgStmt->get_result();
            if ($imgResult->num_rows > 0) {
                $row = $imgResult->fetch_assoc();
                if (!empty($row['image'])) {
                    $imgPath = __DIR__ . '/uploads/' . $row['image'];
                    if (file_exists($imgPath)) {
                        unlink($imgPath);
                    }
                }
            }
        }

        if ($remove_image) {
            // Remove image from DB
            $stmt = $conn->prepare("UPDATE blog SET title=?, category=?, content=?, image=NULL WHERE blog_id=? ");
            $stmt->bind_param("sssi", $updated_name, $updated_category, $updated_content, $id);
        } else if ($new_image) {
            // Update with new image
            $stmt = $conn->prepare("UPDATE blog SET title=?, category=?, content=?, image=? WHERE blog_id=? ");
            $stmt->bind_param("ssssi", $updated_name, $updated_category, $updated_content, $new_image, $id);
        } else {
            // No image change
            $stmt = $conn->prepare("UPDATE blog SET title=?, category=?, content=? WHERE blog_id=? ");
            $stmt->bind_param("sssi", $updated_name, $updated_category, $updated_content, $id);
        }
        if ($stmt->execute()) {
            echo "<script>alert('Blog updated successfully.'); window.location.href = 'bakerblog.php';</script>";
        } else {
            echo "<script>alert('Update failed.');</script>";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_blog'])) {
        $blogId = $_POST['delete_blog_id'];
        // Remove blog image from uploads if exists
        $imgStmt = $conn->prepare("SELECT image FROM blog WHERE blog_id = ?");
        $imgStmt->bind_param("i", $blogId);
        $imgStmt->execute();
        $imgResult = $imgStmt->get_result();
        if ($imgResult->num_rows > 0) {
            $row = $imgResult->fetch_assoc();
            if (!empty($row['image'])) {
                $imgPath = __DIR__ . '/uploads/' . $row['image'];
                if (file_exists($imgPath)) {
                    unlink($imgPath);
                }
            }
        }
        $stmt = $conn->prepare("DELETE FROM blog WHERE blog_id = ?");
        $stmt->bind_param("i", $blogId);

        if ($stmt->execute()) {
            echo "<script>alert('Blog deleted successfully!'); window.location.href = window.location.href;</script>";
        } else {
            echo "<script>alert('Error in deleting blog!');</script>";
        }
    }

    ?>
    <!-- Cropper.js JS for blog image upload -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="blog-cropper.js"></script>
    <script src="edit-cropper.js"></script>
</body>

</html>