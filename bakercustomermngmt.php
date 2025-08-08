<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management - BakeJourney</title>
    <link rel="stylesheet" href="bakercustomermngmt.css">
</head>
<?php include 'bakernavbar.php';?>
<body>
   
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