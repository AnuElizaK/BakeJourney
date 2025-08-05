<?php include 'bakernavbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management - BakeJourney</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fff8f0 0%, #fef3e2 100%);
            min-height: 100vh;
            color: #333;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(255, 107, 53, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(255, 107, 53, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ff6b35, #ffa726);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ff6b35;
        }

        .header-subtitle {
            font-size: 0.9rem;
            color: #666;
            margin-top: 2px;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff6b35, #ffa726);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #ff6b35;
            border: 2px solid #ff6b35;
        }

        .btn-secondary:hover {
            background: #ff6b35;
            color: white;
        }

        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ff6b35;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        .filters-section {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid #f0f0f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #ff6b35;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #f0f0f0;
            background: white;
            border-radius: 20px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn.active {
            background: #ff6b35;
            color: white;
            border-color: #ff6b35;
        }

        .filter-btn:hover:not(.active) {
            border-color: #ff6b35;
            color: #ff6b35;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #ff6b35;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .customers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .customer-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .customer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            border-color: rgba(255, 107, 53, 0.2);
        }

        .customer-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .customer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff6b35, #ffa726);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .customer-info h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .customer-email {
            color: #666;
            font-size: 0.9rem;
        }

        .customer-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-customer {
            background: #e8f5e8;
            color: #2d5a2d;
        }

        .status-follower {
            background: #e3f2fd;
            color: #1565c0;
        }

        .status-new {
            background: #fff3e0;
            color: #ef6c00;
        }

        .customer-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            color: #666;
        }

        .detail-value {
            font-weight: 500;
            color: #333;
        }

        .customer-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-small {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-message {
            background: #ff6b35;
            color: white;
        }

        .btn-message:hover {
            background: #e55a2e;
        }

        .btn-view {
            background: #f0f0f0;
            color: #666;
        }

        .btn-view:hover {
            background: #e0e0e0;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .header-content {
                padding: 0 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .main-content {
                padding: 1rem;
            }

            .filters-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-buttons {
                justify-content: center;
            }

            .customers-grid {
                grid-template-columns: 1fr;
            }

            .customer-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- main content -->
    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Customer Management</h1>
            <p class="page-subtitle">View and manage all your followers and customers in one place</p>
        </div>

        <div class="filters-section">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" class="search-input" placeholder="Search customers by name or email...">
            </div>
            <div class="filter-buttons">
                <button class="filter-btn active">All (47)</button>
                <button class="filter-btn">Customers (28)</button>
                <button class="filter-btn">Followers (19)</button>
                <button class="filter-btn">New (5)</button>
            </div>
        </div>

        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-number">47</div>
                <div class="stat-label">Total Contacts</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">28</div>
                <div class="stat-label">Active Customers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">19</div>
                <div class="stat-label">Followers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">5</div>
                <div class="stat-label">New This Week</div>
            </div>
        </div>

        <div class="customers-grid">
            <div class="customer-card">
                <div class="customer-header">
                    <div class="customer-avatar">EM</div>
                    <div class="customer-info">
                        <h3>Emma Wilson</h3>
                        <div class="customer-email">emma.wilson@email.com</div>
                    </div>
                </div>
                <div class="customer-status">
                    <span class="status-badge status-customer">Customer</span>
                    <span style="color: #ffa726;">⭐ VIP</span>
                </div>
                <div class="customer-details">
                    <div class="detail-item">
                        <span>Orders:</span>
                        <span class="detail-value">12</span>
                    </div>
                    <div class="detail-item">
                        <span>Spent:</span>
                        <span class="detail-value">$245</span>
                    </div>
                    <div class="detail-item">
                        <span>Last Order:</span>
                        <span class="detail-value">2 days ago</span>
                    </div>
                    <div class="detail-item">
                        <span>Member Since:</span>
                        <span class="detail-value">Jan 2024</span>
                    </div>
                </div>
                <div class="customer-actions">
                    <button class="btn-small btn-message">Message</button>
                    <button class="btn-small btn-view">View Profile</button>
                </div>
            </div>

            <div class="customer-card">
                <div class="customer-header">
                    <div class="customer-avatar">MJ</div>
                    <div class="customer-info">
                        <h3>Mike Johnson</h3>
                        <div class="customer-email">mike.j@email.com</div>
                    </div>
                </div>
                <div class="customer-status">
                    <span class="status-badge status-customer">Customer</span>
                </div>
                <div class="customer-details">
                    <div class="detail-item">
                        <span>Orders:</span>
                        <span class="detail-value">8</span>
                    </div>
                    <div class="detail-item">
                        <span>Spent:</span>
                        <span class="detail-value">$156</span>
                    </div>
                    <div class="detail-item">
                        <span>Last Order:</span>
                        <span class="detail-value">1 week ago</span>
                    </div>
                    <div class="detail-item">
                        <span>Member Since:</span>
                        <span class="detail-value">Mar 2024</span>
                    </div>
                </div>
                <div class="customer-actions">
                    <button class="btn-small btn-message">Message</button>
                    <button class="btn-small btn-view">View Profile</button>
                </div>
            </div>

            <div class="customer-card">
                <div class="customer-header">
                    <div class="customer-avatar">LP</div>
                    <div class="customer-info">
                        <h3>Lisa Park</h3>
                        <div class="customer-email">lisa.park@email.com</div>
                    </div>
                </div>
                <div class="customer-status">
                    <span class="status-badge status-follower">Follower</span>
                </div>
                <div class="customer-details">
                    <div class="detail-item">
                        <span>Orders:</span>
                        <span class="detail-value">0</span>
                    </div>
                    <div class="detail-item">
                        <span>Spent:</span>
                        <span class="detail-value">$0</span>
                    </div>
                    <div class="detail-item">
                        <span>Following Since:</span>
                        <span class="detail-value">1 month ago</span>
                    </div>
                    <div class="detail-item">
                        <span>Engagement:</span>
                        <span class="detail-value">High</span>
                    </div>
                </div>
                <div class="customer-actions">
                    <button class="btn-small btn-message">Message</button>
                    <button class="btn-small btn-view">View Profile</button>
                </div>
            </div>

            <div class="customer-card">
                <div class="customer-header">
                    <div class="customer-avatar">JD</div>
                    <div class="customer-info">
                        <h3>John Davis</h3>
                        <div class="customer-email">john.davis@email.com</div>
                    </div>
                </div>
                <div class="customer-status">
                    <span class="status-badge status-new">New</span>
                    <span class="status-badge status-follower">Follower</span>
                </div>
                <div class="customer-details">
                    <div class="detail-item">
                        <span>Orders:</span>
                        <span class="detail-value">0</span>
                    </div>
                    <div class="detail-item">
                        <span>Spent:</span>
                        <span class="detail-value">$0</span>
                    </div>
                    <div class="detail-item">
                        <span>Joined:</span>
                        <span class="detail-value">3 days ago</span>
                    </div>
                    <div class="detail-item">
                        <span>Source:</span>
                        <span class="detail-value">Instagram</span>
                    </div>
                </div>
                <div class="customer-actions">
                    <button class="btn-small btn-message">Message</button>
                    <button class="btn-small btn-view">View Profile</button>
                </div>
            </div>

            <div class="customer-card">
                <div class="customer-header">
                    <div class="customer-avatar">ST</div>
                    <div class="customer-info">
                        <h3>Sarah Thompson</h3>
                        <div class="customer-email">sarah.t@email.com</div>
                    </div>
                </div>
                <div class="customer-status">
                    <span class="status-badge status-customer">Customer</span>
                </div>
                <div class="customer-details">
                    <div class="detail-item">
                        <span>Orders:</span>
                        <span class="detail-value">15</span>
                    </div>
                    <div class="detail-item">
                        <span>Spent:</span>
                        <span class="detail-value">$389</span>
                    </div>
                    <div class="detail-item">
                        <span>Last Order:</span>
                        <span class="detail-value">5 days ago</span>
                    </div>
                    <div class="detail-item">
                        <span>Member Since:</span>
                        <span class="detail-value">Dec 2023</span>
                    </div>
                </div>
                <div class="customer-actions">
                    <button class="btn-small btn-message">Message</button>
                    <button class="btn-small btn-view">View Profile</button>
                </div>
            </div>

            <div class="customer-card">
                <div class="customer-header">
                    <div class="customer-avatar">AM</div>
                    <div class="customer-info">
                        <h3>Alex Martinez</h3>
                        <div class="customer-email">alex.m@email.com</div>
                    </div>
                </div>
                <div class="customer-status">
                    <span class="status-badge status-follower">Follower</span>
                </div>
                <div class="customer-details">
                    <div class="detail-item">
                        <span>Orders:</span>
                        <span class="detail-value">0</span>
                    </div>
                    <div class="detail-item">
                        <span>Spent:</span>
                        <span class="detail-value">$0</span>
                    </div>
                    <div class="detail-item">
                        <span>Following Since:</span>
                        <span class="detail-value">2 weeks ago</span>
                    </div>
                    <div class="detail-item">
                        <span>Engagement:</span>
                        <span class="detail-value">Medium</span>
                    </div>
                </div>
                <div class="customer-actions">
                    <button class="btn-small btn-message">Message</button>
                    <button class="btn-small btn-view">View Profile</button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>