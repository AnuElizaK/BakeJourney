<?php include 'bakernavbar.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Baker Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #fef7ed 0%, #fef3c7 100%);
            min-height: 100vh;
            color: #1f2937;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            background: #fed7aa;
            padding: 0.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-title h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
        }

        .header-title p {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .header-nav {
            display: flex;
            gap: 1rem;
        }

        .nav-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            background: white;
            border-radius: 0.5rem;
            text-decoration: none;
            color: #374151;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .nav-btn:hover {
            background: #f9fafb;
            border-color: #ea580c;
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: #6b7280;
            font-size: 1rem;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
            overflow-x: auto;
        }

        .filter-tab {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .filter-tab.active {
            color: #ea580c;
            border-bottom-color: #ea580c;
        }

        .filter-tab:hover {
            color: #ea580c;
        }

        /* Notification Sections */
        .notifications-container {
            display: grid;
            gap: 2rem;
        }

        .notification-section {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }

        .notification-count {
            background: #ea580c;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Notification Items */
        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            border-radius: 0.75rem;
            background: #f9fafb;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .notification-item:hover {
            background: #f3f4f6;
            transform: translateX(2px);
        }

        .notification-item.unread {
            border-left-color: #ea580c;
            background: #fef7ed;
        }

        .notification-item.review {
            border-left-color: #059669;
        }

        .notification-item.like {
            border-left-color: #dc2626;
        }

        .notification-item.message {
            border-left-color: #eab308;
        }

        .notification-item.milestone {
            border-left-color: #7c3aed;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            color: white;
            flex-shrink: 0;
        }

        .icon-review {
            background: #059669;
        }

        .icon-like {
            background: #dc2626;
        }

        .icon-message {
            background: #eab308;
        }

        .icon-milestone {
            background: #7c3aed;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #111827;
            font-size: 0.875rem;
        }

        .notification-text {
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
        }

        .notification-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .notification-time {
            font-style: italic;
        }

        .notification-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Buttons */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-small {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 0.375rem;
        }

        .btn-primary {
            background: #ea580c;
            color: white;
        }

        .btn-primary:hover {
            background: #dc2626;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .btn-success {
            background: #059669;
            color: white;
        }

        .btn-success:hover {
            background: #047857;
        }

        /* Special Elements */
        .rating-stars {
            color: #eab308;
            margin: 0.5rem 0;
            font-size: 0.875rem;
        }

        .milestone-badge {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin: 0.5rem 0;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .header-nav {
                width: 100%;
                justify-content: center;
            }

            .filter-tabs {
                overflow-x: auto;
                white-space: nowrap;
            }

            .notification-item {
                flex-direction: column;
                gap: 0.75rem;
            }

            .notification-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .notification-actions {
                width: 100%;
            }

            .btn-small {
                flex: 1;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 1rem;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="logo">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2">
                        <path d="M12 2c-1.5 0-3 1-3 3s1.5 3 3 3 3-1 3-3-1.5-3-3-3z"/>
                        <path d="M19 12c0-7-7-7-7-7s-7 0-7 7c0 1.5.5 3 1.5 4L12 22l5.5-6c1-1 1.5-2.5 1.5-4z"/>
                        <circle cx="12" cy="12" r="2"/>
                    </svg>
                </div>
                <div class="header-title">
                    <h1>Notifications</h1>
                    <p>Stay updated on customer interactions and milestones</p>
                </div>
            </div>
            <nav class="header-nav">
                <a href="baker-home.html" class="nav-btn">← Back to Dashboard</a>
                <a href="#" class="nav-btn">Settings</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h2>Notifications</h2>
            <p>Stay connected with your customers and track your achievements</p>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterNotifications('all')">All</button>
            <button class="filter-tab" onclick="filterNotifications('reviews')">Reviews</button>
            <button class="filter-tab" onclick="filterNotifications('likes')">Likes & Follows</button>
            <button class="filter-tab" onclick="filterNotifications('messages')">Messages</button>
            <button class="filter-tab" onclick="filterNotifications('milestones')">Milestones</button>
            <button class="filter-tab" onclick="filterNotifications('unread')">Unread Only</button>
        </div>

        <!-- Notifications Container -->
        <div class="notifications-container">
            <!-- Customer Reviews Section -->
            <div class="notification-section" id="reviews-section">
                <div class="section-header">
                    <h3 class="section-title">Customer Reviews</h3>
                    <span class="notification-count">3</span>
                </div>
                <div class="notifications-list">
                    <div class="notification-item review unread" data-type="review">
                        <div class="notification-icon icon-review">⭐</div>
                        <div class="notification-content">
                            <div class="notification-title">New 5-star review from Sarah Johnson</div>
                            <div class="rating-stars">⭐⭐⭐⭐⭐</div>
                            <div class="notification-text">"Amazing chocolate croissants! The texture was perfect and the chocolate filling was heavenly. Will definitely order again!"</div>
                            <div class="notification-meta">
                                <span class="notification-time">2 hours ago</span>
                                <div class="notification-actions">
                                    <button class="btn btn-small btn-secondary">View Full Review</button>
                                    <button class="btn btn-small btn-success">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="notification-item review" data-type="review">
                        <div class="notification-icon icon-review">⭐</div>
                        <div class="notification-content">
                            <div class="notification-title">New 4-star review from Mike Chen</div>
                            <div class="rating-stars">⭐⭐⭐⭐☆</div>
                            <div class="notification-text">"Great sourdough bread! Love the crust and flavor. Would appreciate more variety in whole grain options."</div>
                            <div class="notification-meta">
                                <span class="notification-time">5 hours ago</span>
                                <div class="notification-actions">
                                    <button class="btn btn-small btn-secondary">View Full Review</button>
                                    <button class="btn btn-small btn-success">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="notification-item review" data-type="review">
                        <div class="notification-icon icon-review">⭐</div>
                        <div class="notification-content">
                            <div class="notification-title">New 5-star review from Emma Davis</div>
                            <div class="rating-stars">⭐⭐⭐⭐⭐</div>
                            <div class="notification-text">"The birthday cake was absolutely stunning and delicious! Everyone at the party loved it. Thank you for making our celebration special!"</div>
                            <div class="notification-meta">
                                <span class="notification-time">1 day ago</span>
                                <div class="notification-actions">
                                    <button class="btn btn-small btn-secondary">View Full Review</button>
                                    <button class="btn btn-small btn-success">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Likes & Follows Section -->
            <div class="notification-section" id="likes-section">
                <div class="section-header">
                    <h3 class="section-title">Likes & Follows</h3>
                    <span class="notification-count">5</span>
                </div>
                <div class="notifications-list">
                    <div class="notification-item like unread" data-type="like">
                        <div class="notification-icon icon-like">❤️</div>
                        <div class="notification-content">
                            <div class="notification-title">Jessica Wilson started following you</div>
                            <div class="notification-text">New follower! Jessica has also liked 3 of your recent products.</div>
                            <div class="notification-meta">
                                <span class="notification-time">1 hour ago</span>
                                <div class="notification-actions">
                                    <button class="btn btn-small btn-secondary">View Profile</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="notification-item like" data-type="like">
                        <div class="notification-icon icon-like">👍</div>
                        <div class="notification-content">
                            <div class="notification-title">15 people liked your "Artisan Sourdough Bread"</div>
                            <div class="notification-text">Your latest product post is getting lots of love from customers!</div>
                            <div class="notification-meta">
                                <span class="notification-time">3 hours ago</span>
                                <div class="notification-actions">
                                    <button class="btn btn-small btn-secondary">View Product</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="notification-item like" data-type="like">
                        <div class="notification-icon icon-like">❤️</div>
                        <div class="notification-content">
                            <div class="notification-title">Robert Taylor and 8 others started following you</div>
                            <div class="notification-text">You've gained 9 new followers today! Your bakery is growing popular.</div>
                            <div class="notification-meta">
                                <span class="notification-time">6 hours ago</span>
                                <div class="notification-actions">
                                    <button class="btn btn-small btn-secondary">View Followers</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Section -->
            <div class="notification-section" id="messages-section">
                <div class="section-header">
                    <h3 class="section-title">Messages</h3>
                    <span class="notification-count">2</span>
                </div>
                <div class="notifications-list">
                    <div class="notification-item message unread" data-type="message">
                        <div class="notification-icon icon-message">💬</div>
                        <div class="notification-content">
                            <div class="notification-title">New message from Lisa Anderson</div>
                            <div class="notification-text">"Hi! I'm planning a wedding for next month and would love to discuss custom cake options. Do you offer consultation services?"</div>
                            <div class="notification-meta">
                                <span class="notification-time">30 minutes ago</span>
                                <div class="notification-actions">
                                    <button class="btn btn-small btn-secondary">View Message</button>
                                    <button class="btn btn-small btn-success">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="notification-item message" data-type="message">
                        <div class="notification-icon icon-message">💬</div>
                        <div class="notification-content">
                            <div class="notification-title">New message from David Brown</div>
                            <div class="notification-text">"Thank you for the amazing birthday cake! Could you share the recipe for the frosting? My family is obsessed with it!"</div>
                            <div class="notification-meta">
                                <span class="notification-time">2 hours ago</span>
                                <div class="notification-actions">
                                    <button class="btn btn-small btn-secondary">View Message</button>
                                    <button class="btn btn-small btn-success">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Milestones & Achievements Section -->
            <div class="notification-section" id="milestones-section">
                <div class="section-header">
                    <h3 class="section-title">Milestones & Achievements</h3>
                    <span class="notification-count">3</span>
                </div>
                <div class="notifications-list">
                    <div class="notification-item milestone unread" data-type="milestone">
                        <div class="notification-icon icon-milestone">🏆</div>
                        <div class="notification-content">
                            <div class="notification-title">Congratulations! You've reached 100 followers!</div>
                            <div class="milestone-badge">🎉 100 Followers Milestone</div>
                            <div class="notification-text">Your bakery community is growing! You now have 100 loyal followers who love your delicious creations.</div>
                            <div class="notification-meta">
                                <span class="notification-time">1 hour ago</span>
                                <div class="notification-actions">
                                    <button class="btn btn-small btn-secondary">View Achievement</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="notification-item milestone" data-type="milestone">
                        <div class="notification-icon icon-milestone">⭐</div>
                        <div class="notification-content">
                            <div class="notification-title">Your average rating improved to 4.8 stars!</div>
                            <div class="milestone-badge">⭐ 4.8 Star Rating</div>
                            <div class="notification-text">Excellent work! Your customer satisfaction has reached a new high with an average rating of 4.8/5 stars.</div>
                            <div class="notification-meta">
                                <span class="notification-time">1 day ago</span>
                                <div class="notification-actions">
                                    <button class="btn btn-small btn-secondary">View Analytics</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="notification-item milestone" data-type="milestone">
                        <div class="notification-icon icon-milestone">🎯</div>
                        <div class="notification-content">
                            <div class="notification-title">Monthly sales goal achieved!</div>
                            <div class="milestone-badge">💰 Sales Target Reached</div>
                            <div class="notification-text">Amazing! You've completed your monthly sales target with a week to spare. Total revenue: $2,450</div>
                            <div class="notification-meta">
                                <span class="notification-time">2 days ago</span>
                                <div class="notification-actions">
                                    <button class="btn btn-small btn-secondary">View Sales Report</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Filter functionality
        function filterNotifications(type) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            // Get all notification items
            const notifications = document.querySelectorAll('.notification-item');
            
            notifications.forEach(notification => {
                if (type === 'all') {
                    notification.style.display = 'flex';
                } else if (type === 'unread') {
                    notification.style.display = notification.classList.contains('unread') ? 'flex' : 'none';
                } else if (type === 'reviews') {
                    notification.style.display = notification.getAttribute('data-type') === 'review' ? 'flex' : 'none';
                } else if (type === 'likes') {
                    notification.style.display = notification.getAttribute('data-type') === 'like' ? 'flex' : 'none';
                } else if (type === 'messages') {
                    notification.style.display = notification.getAttribute('data-type') === 'message' ? 'flex' : 'none';
                } else if (type === 'milestones') {
                    notification.style.display = notification.getAttribute('data-type') === 'milestone' ? 'flex' : 'none';
                }
            });

            // Hide/show sections based on filter
            const sections = document.querySelectorAll('.notification-section');
            sections.forEach(section => {
                const visibleItems = section.querySelectorAll('.notification-item[style*="flex"], .notification-item:not([style])');
                section.style.display = visibleItems.length > 0 ? 'block' : 'none';
            });
        }

        // Mark notification as read when clicked
        document.querySelectorAll('.notification-item').forEach(notification => {
            notification.addEventListener('click', function(e) {
                // Don't mark as read if clicking on buttons
                if (!e.target.classList.contains('btn')) {
                    this.classList.remove('unread');
                }
            });
        });

        // Button click handlers
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                console.log('Button clicked:', this.textContent);
            });
        });
    </script>
</body>
</html>