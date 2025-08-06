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

        .customers-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header {
            background: linear-gradient(135deg, #ff6b35, #ffa726);
            color: white;
        }

        .table-header th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .table-row {
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background-color: #fafafa;
        }

        .table-row.expanded {
            background-color: #fff8f5;
        }

        .table-cell {
            padding: 1rem;
            vertical-align: middle;
        }

        .customer-avatar-small {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff6b35, #ffa726);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .customer-name {
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .accordion-content {
            background: #fff8f5;
            border-top: 1px solid #f0f0f0;
            display: none;
        }

        .accordion-content.show {
            display: table-row;
        }

        .accordion-details {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .detail-section {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .detail-section h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #ff6b35;
            margin-bottom: 0.5rem;
        }

        .expand-icon {
            transition: transform 0.3s ease;
            font-size: 1.2rem;
            color: #666;
        }

        .table-row.expanded .expand-icon {
            transform: rotate(90deg);
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
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo">🧁</div>
                <div>
                    <div class="header-title">Customer Management</div>
                    <div class="header-subtitle">Manage your followers and customers</div>
                </div>
            </div>
            <div class="header-actions">
                <a href="baker-home.html" class="btn btn-secondary">← Back to Dashboard</a>
                <a href="#" class="btn btn-primary">Export List</a>
            </div>
        </div>
    </header>

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

        <div class="customers-table">
            <table class="table">
                <thead class="table-header">
                    <tr>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-row" onclick="toggleAccordion(this)">
                        <td class="table-cell">
                            <div class="customer-avatar-small">EM</div>
                        </td>
                        <td class="table-cell">
                            <h3 class="customer-name">Emma Wilson</h3>
                        </td>
                        <td class="table-cell">
                            <span class="status-badge status-customer">Customer</span>
                            <span style="color: #ffa726;">⭐ VIP</span>
                        </td>
                        <td class="table-cell">
                            <span class="expand-icon">▶</span>
                        </td>
                    </tr>
                    <tr class="accordion-content">
                        <td colspan="4">
                            <div class="accordion-details">
                                <div class="detail-section">
                                    <h4>Contact Information</h4>
                                    <div class="detail-item">
                                        <span>Email:</span>
                                        <span class="detail-value">emma.wilson@email.com</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Phone:</span>
                                        <span class="detail-value">+1 (555) 123-4567</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Order History</h4>
                                    <div class="detail-item">
                                        <span>Total Orders:</span>
                                        <span class="detail-value">12</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Total Spent:</span>
                                        <span class="detail-value">$245</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Last Order:</span>
                                        <span class="detail-value">2 days ago</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Membership</h4>
                                    <div class="detail-item">
                                        <span>Member Since:</span>
                                        <span class="detail-value">Jan 2024</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Status:</span>
                                        <span class="detail-value">VIP Customer</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Actions</h4>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn-small btn-message">Message</button>
                                        <button class="btn-small btn-view" onclick="location.href='bakerinfopage.php?user_id=2'">View Profile</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="table-row" onclick="toggleAccordion(this)">
                        <td class="table-cell">
                            <div class="customer-avatar-small">MJ</div>
                        </td>
                        <td class="table-cell">
                            <h3 class="customer-name">Mike Johnson</h3>
                        </td>
                        <td class="table-cell">
                            <span class="status-badge status-customer">Customer</span>
                        </td>
                        <td class="table-cell">
                            <span class="expand-icon">▶</span>
                        </td>
                    </tr>
                    <tr class="accordion-content">
                        <td colspan="4">
                            <div class="accordion-details">
                                <div class="detail-section">
                                    <h4>Contact Information</h4>
                                    <div class="detail-item">
                                        <span>Email:</span>
                                        <span class="detail-value">mike.j@email.com</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Phone:</span>
                                        <span class="detail-value">+1 (555) 987-6543</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Order History</h4>
                                    <div class="detail-item">
                                        <span>Total Orders:</span>
                                        <span class="detail-value">8</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Total Spent:</span>
                                        <span class="detail-value">$156</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Last Order:</span>
                                        <span class="detail-value">1 week ago</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Membership</h4>
                                    <div class="detail-item">
                                        <span>Member Since:</span>
                                        <span class="detail-value">Mar 2024</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Status:</span>
                                        <span class="detail-value">Regular Customer</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Actions</h4>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn-small btn-message">Message</button>
                                        <button class="btn-small btn-view">View Profile</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="table-row" onclick="toggleAccordion(this)">
                        <td class="table-cell">
                            <div class="customer-avatar-small">LP</div>
                        </td>
                        <td class="table-cell">
                            <h3 class="customer-name">Lisa Park</h3>
                        </td>
                        <td class="table-cell">
                            <span class="status-badge status-follower">Follower</span>
                        </td>
                        <td class="table-cell">
                            <span class="expand-icon">▶</span>
                        </td>
                    </tr>
                    <tr class="accordion-content">
                        <td colspan="4">
                            <div class="accordion-details">
                                <div class="detail-section">
                                    <h4>Contact Information</h4>
                                    <div class="detail-item">
                                        <span>Email:</span>
                                        <span class="detail-value">lisa.park@email.com</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Social:</span>
                                        <span class="detail-value">@lisapark_foodie</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Engagement</h4>
                                    <div class="detail-item">
                                        <span>Following Since:</span>
                                        <span class="detail-value">1 month ago</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Engagement Level:</span>
                                        <span class="detail-value">High</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Interests:</span>
                                        <span class="detail-value">Pastries, Cakes</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Potential</h4>
                                    <div class="detail-item">
                                        <span>Conversion Score:</span>
                                        <span class="detail-value">8.5/10</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Recommended Action:</span>
                                        <span class="detail-value">Send Discount Code</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Actions</h4>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn-small btn-message">Message</button>
                                        <button class="btn-small btn-view">View Profile</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="table-row" onclick="toggleAccordion(this)">
                        <td class="table-cell">
                            <div class="customer-avatar-small">JD</div>
                        </td>
                        <td class="table-cell">
                            <h3 class="customer-name">John Davis</h3>
                        </td>
                        <td class="table-cell">
                            <span class="status-badge status-new">New</span>
                            <span class="status-badge status-follower">Follower</span>
                        </td>
                        <td class="table-cell">
                            <span class="expand-icon">▶</span>
                        </td>
                    </tr>
                    <tr class="accordion-content">
                        <td colspan="4">
                            <div class="accordion-details">
                                <div class="detail-section">
                                    <h4>Contact Information</h4>
                                    <div class="detail-item">
                                        <span>Email:</span>
                                        <span class="detail-value">john.davis@email.com</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Source:</span>
                                        <span class="detail-value">Instagram</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>New Member Info</h4>
                                    <div class="detail-item">
                                        <span>Joined:</span>
                                        <span class="detail-value">3 days ago</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>First Interaction:</span>
                                        <span class="detail-value">Liked 5 posts</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Welcome Actions</h4>
                                    <div class="detail-item">
                                        <span>Welcome Message:</span>
                                        <span class="detail-value">Sent ✓</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Welcome Discount:</span>
                                        <span class="detail-value">Pending</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Actions</h4>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn-small btn-message">Send Welcome</button>
                                        <button class="btn-small btn-view">View Profile</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="table-row" onclick="toggleAccordion(this)">
                        <td class="table-cell">
                            <div class="customer-avatar-small">ST</div>
                        </td>
                        <td class="table-cell">
                            <h3 class="customer-name">Sarah Thompson</h3>
                        </td>
                        <td class="table-cell">
                            <span class="status-badge status-customer">Customer</span>
                        </td>
                        <td class="table-cell">
                            <span class="expand-icon">▶</span>
                        </td>
                    </tr>
                    <tr class="accordion-content">
                        <td colspan="4">
                            <div class="accordion-details">
                                <div class="detail-section">
                                    <h4>Contact Information</h4>
                                    <div class="detail-item">
                                        <span>Email:</span>
                                        <span class="detail-value">sarah.t@email.com</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Phone:</span>
                                        <span class="detail-value">+1 (555) 456-7890</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Order History</h4>
                                    <div class="detail-item">
                                        <span>Total Orders:</span>
                                        <span class="detail-value">15</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Total Spent:</span>
                                        <span class="detail-value">$389</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Last Order:</span>
                                        <span class="detail-value">5 days ago</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Membership</h4>
                                    <div class="detail-item">
                                        <span>Member Since:</span>
                                        <span class="detail-value">Dec 2023</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Loyalty Points:</span>
                                        <span class="detail-value">1,250 pts</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Actions</h4>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn-small btn-message">Message</button>
                                        <button class="btn-small btn-view">View Profile</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="table-row" onclick="toggleAccordion(this)">
                        <td class="table-cell">
                            <div class="customer-avatar-small">AM</div>
                        </td>
                        <td class="table-cell">
                            <h3 class="customer-name">Alex Martinez</h3>
                        </td>
                        <td class="table-cell">
                            <span class="status-badge status-follower">Follower</span>
                        </td>
                        <td class="table-cell">
                            <span class="expand-icon">▶</span>
                        </td>
                    </tr>
                    <tr class="accordion-content">
                        <td colspan="4">
                            <div class="accordion-details">
                                <div class="detail-section">
                                    <h4>Contact Information</h4>
                                    <div class="detail-item">
                                        <span>Email:</span>
                                        <span class="detail-value">alex.m@email.com</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Social:</span>
                                        <span class="detail-value">@alexm_bakingfan</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Engagement</h4>
                                    <div class="detail-item">
                                        <span>Following Since:</span>
                                        <span class="detail-value">2 weeks ago</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Engagement Level:</span>
                                        <span class="detail-value">Medium</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Interests:</span>
                                        <span class="detail-value">Bread, Cookies</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Potential</h4>
                                    <div class="detail-item">
                                        <span>Conversion Score:</span>
                                        <span class="detail-value">6.5/10</span>
                                    </div>
                                    <div class="detail-item">
                                        <span>Recommended Action:</span>
                                        <span class="detail-value">Share Recipe</span>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <h4>Actions</h4>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button class="btn-small btn-message">Message</button>
                                        <button class="btn-small btn-view">View Profile</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <script>
            function toggleAccordion(row) {
                const accordionContent = row.nextElementSibling;
                const isExpanded = row.classList.contains('expanded');
                
                // Close all other accordions
                document.querySelectorAll('.table-row.expanded').forEach(expandedRow => {
                    expandedRow.classList.remove('expanded');
                    expandedRow.nextElementSibling.classList.remove('show');
                });
                
                if (!isExpanded) {
                    row.classList.add('expanded');
                    accordionContent.classList.add('show');
                }
            }
        </script>
    </main>
</body>
</html>