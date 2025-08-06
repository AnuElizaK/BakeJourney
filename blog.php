<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Blog - Sweet Spot Bakery</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #fef7cd 0%, #fed7aa 100%);
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #1f2937;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .header p {
            color: #6b7280;
            font-size: 1.1rem;
        }

        .filters {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .filter-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 25px;
            background: #f3f4f6;
            color: #374151;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .filter-btn.active,
        .filter-btn:hover {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
        }

        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .blog-post {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .blog-post:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .post-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(45deg, #f59e0b, #f97316);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .post-content {
            padding: 1.5rem;
        }

        .post-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .author-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .baker-badge {
            background: #ecfdf5;
            color: #059669;
        }

        .admin-badge {
            background: #fef3c7;
            color: #d97706;
        }

        .post-date {
            color: #9ca3af;
            font-size: 0.9rem;
        }

        .post-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.8rem;
            line-height: 1.4;
        }

        .post-excerpt {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .post-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .action-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border: none;
            background: none;
            color: #6b7280;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .action-btn:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .action-btn.liked {
            color: #ef4444;
        }

        .read-more-btn {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .read-more-btn:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            transform: translateY(-1px);
        }

        .comments-section {
            padding: 1rem 1.5rem;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: none;
        }

        .comments-section.show {
            display: block;
        }

        .comment {
            padding: 1rem;
            background: white;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 3px solid #d97706;
        }

        .comment-author {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.3rem;
        }

        .comment-text {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .comment-form {
            display: flex;
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .comment-input {
            flex: 1;
            padding: 0.8rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .comment-input:focus {
            border-color: #d97706;
        }

        .comment-submit {
            padding: 0.8rem 1.2rem;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .comment-submit:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .blog-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .post-actions {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍞 Community Blog</h1>
            <p>Discover recipes, stories, and updates from our amazing baker community</p>
        </div>

        <div class="filters">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">All Posts</button>
                <button class="filter-btn" data-filter="recipes">Recipes</button>
                <button class="filter-btn" data-filter="stories">Stories</button>
                <button class="filter-btn" data-filter="tips">Tips & Tricks</button>
                <button class="filter-btn" data-filter="announcements">Announcements</button>
            </div>
        </div>

        <div class="blog-grid">
            <!-- Admin Announcement Post -->
            <article class="blog-post" data-category="announcements">
                <div class="post-image">📢</div>
                <div class="post-content">
                    <div class="post-meta">
                        <span class="author-badge admin-badge">Admin</span>
                        <span class="post-date">March 15, 2024</span>
                    </div>
                    <h2 class="post-title">New Features Coming to Sweet Spot Bakery Platform</h2>
                    <p class="post-excerpt">We're excited to announce some amazing new features coming to our platform this month, including enhanced customer messaging and improved order tracking...</p>
                    <div class="post-actions">
                        <div class="action-group">
                            <button class="action-btn" onclick="toggleLike(this)">
                                <span>❤️</span>
                                <span class="like-count">24</span>
                            </button>
                            <button class="action-btn" onclick="toggleComments(this)">
                                <span>💬</span>
                                <span>8</span>
                            </button>
                            <button class="action-btn" onclick="sharePost(this)">
                                <span>📤</span>
                                <span>Share</span>
                            </button>
                        </div>
                        <button class="read-more-btn">Read More</button>
                    </div>
                </div>
                <div class="comments-section">
                    <div class="comment">
                        <div class="comment-author">Sarah B.</div>
                        <div class="comment-text">So excited for the new messaging feature! This will help me connect better with my customers.</div>
                    </div>
                    <div class="comment">
                        <div class="comment-author">Mike's Artisan Breads</div>
                        <div class="comment-text">Can't wait to try the order tracking improvements!</div>
                    </div>
                    <div class="comment-form">
                        <input type="text" class="comment-input" placeholder="Add a comment...">
                        <button class="comment-submit">Post</button>
                    </div>
                </div>
            </article>

            <!-- Baker Recipe Post -->
            <article class="blog-post" data-category="recipes">
                <div class="post-image">🥖</div>
                <div class="post-content">
                    <div class="post-meta">
                        <span class="author-badge baker-badge">Baker</span>
                        <span class="post-date">March 12, 2024</span>
                    </div>
                    <h2 class="post-title">Perfect Sourdough: A 3-Day Journey</h2>
                    <p class="post-excerpt">After years of perfecting my sourdough recipe, I'm sharing my secrets for achieving that perfect crust and airy crumb. This method takes patience but delivers incredible results...</p>
                    <div class="post-actions">
                        <div class="action-group">
                            <button class="action-btn liked" onclick="toggleLike(this)">
                                <span>❤️</span>
                                <span class="like-count">156</span>
                            </button>
                            <button class="action-btn" onclick="toggleComments(this)">
                                <span>💬</span>
                                <span>23</span>
                            </button>
                            <button class="action-btn" onclick="sharePost(this)">
                                <span>📤</span>
                                <span>Share</span>
                            </button>
                        </div>
                        <button class="read-more-btn">Read More</button>
                    </div>
                </div>
                <div class="comments-section">
                    <div class="comment">
                        <div class="comment-author">Emma K.</div>
                        <div class="comment-text">This recipe changed my baking game! The detail in your instructions is amazing.</div>
                    </div>
                    <div class="comment">
                        <div class="comment-author">Local Food Lover</div>
                        <div class="comment-text">Tried this last weekend and it was incredible. My family couldn't get enough!</div>
                    </div>
                    <div class="comment-form">
                        <input type="text" class="comment-input" placeholder="Add a comment...">
                        <button class="comment-submit">Post</button>
                    </div>
                </div>
            </article>

            <!-- Baker Story Post -->
            <article class="blog-post" data-category="stories">
                <div class="post-image">💝</div>
                <div class="post-content">
                    <div class="post-meta">
                        <span class="author-badge baker-badge">Baker</span>
                        <span class="post-date">March 10, 2024</span>
                    </div>
                    <h2 class="post-title">From Hobby to Business: My Baking Journey</h2>
                    <p class="post-excerpt">Three years ago, I was just baking for friends and family. Today, I'm running a successful home bakery with over 200 regular customers. Here's how it all started...</p>
                    <div class="post-actions">
                        <div class="action-group">
                            <button class="action-btn" onclick="toggleLike(this)">
                                <span>❤️</span>
                                <span class="like-count">89</span>
                            </button>
                            <button class="action-btn" onclick="toggleComments(this)">
                                <span>💬</span>
                                <span>15</span>
                            </button>
                            <button class="action-btn" onclick="sharePost(this)">
                                <span>📤</span>
                                <span>Share</span>
                            </button>
                        </div>
                        <button class="read-more-btn">Read More</button>
                    </div>
                </div>
                <div class="comments-section">
                    <div class="comment">
                        <div class="comment-author">Aspiring Baker</div>
                        <div class="comment-text">Such an inspiration! I'm hoping to start my own bakery journey soon.</div>
                    </div>
                    <div class="comment-form">
                        <input type="text" class="comment-input" placeholder="Add a comment...">
                        <button class="comment-submit">Post</button>
                    </div>
                </div>
            </article>

            <!-- Baker Tips Post -->
            <article class="blog-post" data-category="tips">
                <div class="post-image">💡</div>
                <div class="post-content">
                    <div class="post-meta">
                        <span class="author-badge baker-badge">Baker</span>
                        <span class="post-date">March 8, 2024</span>
                    </div>
                    <h2 class="post-title">5 Essential Tools Every Home Baker Needs</h2>
                    <p class="post-excerpt">You don't need a professional kitchen to create amazing baked goods. Here are the 5 tools that have made the biggest difference in my baking quality and efficiency...</p>
                    <div class="post-actions">
                        <div class="action-group">
                            <button class="action-btn" onclick="toggleLike(this)">
                                <span>❤️</span>
                                <span class="like-count">67</span>
                            </button>
                            <button class="action-btn" onclick="toggleComments(this)">
                                <span>💬</span>
                                <span>12</span>
                            </button>
                            <button class="action-btn" onclick="sharePost(this)">
                                <span>📤</span>
                                <span>Share</span>
                            </button>
                        </div>
                        <button class="read-more-btn">Read More</button>
                    </div>
                </div>
                <div class="comments-section">
                    <div class="comment">
                        <div class="comment-author">New Baker</div>
                        <div class="comment-text">Great list! I just ordered the scale you recommended.</div>
                    </div>
                    <div class="comment-form">
                        <input type="text" class="comment-input" placeholder="Add a comment...">
                        <button class="comment-submit">Post</button>
                    </div>
                </div>
            </article>

            <!-- Admin Resource Post -->
            <article class="blog-post" data-category="announcements">
                <div class="post-image">📚</div>
                <div class="post-content">
                    <div class="post-meta">
                        <span class="author-badge admin-badge">Admin</span>
                        <span class="post-date">March 5, 2024</span>
                    </div>
                    <h2 class="post-title">Free Business Resources for Sweet Spot Bakers</h2>
                    <p class="post-excerpt">We've compiled a comprehensive library of business resources specifically for our baker community. Templates, guides, and tools to help grow your baking business...</p>
                    <div class="post-actions">
                        <div class="action-group">
                            <button class="action-btn" onclick="toggleLike(this)">
                                <span>❤️</span>
                                <span class="like-count">142</span>
                            </button>
                            <button class="action-btn" onclick="toggleComments(this)">
                                <span>💬</span>
                                <span>31</span>
                            </button>
                            <button class="action-btn" onclick="sharePost(this)">
                                <span>📤</span>
                                <span>Share</span>
                            </button>
                        </div>
                        <button class="read-more-btn">Read More</button>
                    </div>
                </div>
                <div class="comments-section">
                    <div class="comment">
                        <div class="comment-author">Professional Baker</div>
                        <div class="comment-text">The pricing guide template is exactly what I needed!</div>
                    </div>
                    <div class="comment">
                        <div class="comment-author">Home Baker Pro</div>
                        <div class="comment-text">Thank you for providing these resources for free. So helpful!</div>
                    </div>
                    <div class="comment-form">
                        <input type="text" class="comment-input" placeholder="Add a comment...">
                        <button class="comment-submit">Post</button>
                    </div>
                </div>
            </article>

            <!-- Baker Recipe Post 2 -->
            <article class="blog-post" data-category="recipes">
                <div class="post-image">🧁</div>
                <div class="post-content">
                    <div class="post-meta">
                        <span class="author-badge baker-badge">Baker</span>
                        <span class="post-date">March 3, 2024</span>
                    </div>
                    <h2 class="post-title">Gluten-Free Cupcakes That Actually Taste Amazing</h2>
                    <p class="post-excerpt">After countless experiments, I've finally perfected gluten-free cupcakes that are moist, fluffy, and indistinguishable from traditional ones. Here's my secret...</p>
                    <div class="post-actions">
                        <div class="action-group">
                            <button class="action-btn" onclick="toggleLike(this)">
                                <span>❤️</span>
                                <span class="like-count">78</span>
                            </button>
                            <button class="action-btn" onclick="toggleComments(this)">
                                <span>💬</span>
                                <span>19</span>
                            </button>
                            <button class="action-btn" onclick="sharePost(this)">
                                <span>📤</span>
                                <span>Share</span>
                            </button>
                        </div>
                        <button class="read-more-btn">Read More</button>
                    </div>
                </div>
                <div class="comments-section">
                    <div class="comment">
                        <div class="comment-author">Celiac Customer</div>
                        <div class="comment-text">Finally! A recipe that doesn't taste like cardboard. Thank you!</div>
                    </div>
                    <div class="comment-form">
                        <input type="text" class="comment-input" placeholder="Add a comment...">
                        <button class="comment-submit">Post</button>
                    </div>
                </div>
            </article>
        </div>
    </div>

    <script>
        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Filter posts
                const filter = btn.dataset.filter;
                document.querySelectorAll('.blog-post').forEach(post => {
                    if (filter === 'all' || post.dataset.category === filter) {
                        post.style.display = 'block';
                    } else {
                        post.style.display = 'none';
                    }
                });
            });
        });

        // Like functionality
        function toggleLike(btn) {
            btn.classList.toggle('liked');
            const countSpan = btn.querySelector('.like-count');
            let count = parseInt(countSpan.textContent);
            if (btn.classList.contains('liked')) {
                count++;
            } else {
                count--;
            }
            countSpan.textContent = count;
        }

        // Comments functionality
        function toggleComments(btn) {
            const post = btn.closest('.blog-post');
            const commentsSection = post.querySelector('.comments-section');
            commentsSection.classList.toggle('show');
        }

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

        // Comment submission
        document.querySelectorAll('.comment-submit').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const form = e.target.closest('.comment-form');
                const input = form.querySelector('.comment-input');
                const text = input.value.trim();
                
                if (text) {
                    const commentsContainer = form.parentElement;
                    const newComment = document.createElement('div');
                    newComment.className = 'comment';
                    newComment.innerHTML = `
                        <div class="comment-author">You</div>
                        <div class="comment-text">${text}</div>
                    `;
                    
                    commentsContainer.insertBefore(newComment, form);
                    input.value = '';
                    
                    // Update comment count
                    const post = commentsContainer.closest('.blog-post');
                    const commentBtn = post.querySelector('.action-btn:nth-child(2) span:last-child');
                    let count = parseInt(commentBtn.textContent);
                    commentBtn.textContent = count + 1;
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
    </script>
</body>
</html>