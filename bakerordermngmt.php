<?php include 'bakernavbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - Baker Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
        }

        .stat-card h3 {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .tab {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .tab.active {
            color: #ea580c;
            border-bottom-color: #ea580c;
        }

        .tab:hover {
            color: #ea580c;
        }

        /* Order Cards */
        .orders-section {
            display: none;
        }

        .orders-section.active {
            display: block;
        }

        .orders-grid {
            display: grid;
            gap: 1.5rem;
        }

        .order-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f3f4f6;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .order-info h4 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .order-info p {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .order-status {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-accepted {
            background: #dcfce7;
            color: #166534;
        }

        .status-in-progress {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-completed {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .status-rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .order-details {
            margin-bottom: 1rem;
        }

        .order-items {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .order-item {
            background: #f9fafb;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: #374151;
        }

        .order-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 0.5rem;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 0.75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .meta-value {
            font-size: 0.875rem;
            color: #111827;
            font-weight: 500;
        }

        .order-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

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

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        /* Custom Order Special Styling */
        .custom-order {
            border-left: 4px solid #7c3aed;
        }

        .custom-order-note {
            background: #f3e8ff;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 3px solid #7c3aed;
        }

        .custom-order-note h5 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #7c3aed;
            margin-bottom: 0.5rem;
        }

        .custom-order-note p {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.5;
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

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .tabs {
                overflow-x: auto;
                white-space: nowrap;
            }

            .order-header {
                flex-direction: column;
                gap: 0.75rem;
            }

            .order-actions {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .order-meta {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
   

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h2>Order Management</h2>
            <p>Track and manage all your orders in one place</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Pending Requests</h3>
                <div class="number">8</div>
            </div>
            <div class="stat-card">
                <h3>In Progress</h3>
                <div class="number">5</div>
            </div>
            <div class="stat-card">
                <h3>Completed Today</h3>
                <div class="number">12</div>
            </div>
            <div class="stat-card">
                <h3>Custom Orders</h3>
                <div class="number">3</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="showTab('pending')">Pending Requests</button>
            <button class="tab" onclick="showTab('ongoing')">Ongoing Orders</button>
            <button class="tab" onclick="showTab('completed')">Past Orders</button>
            <button class="tab" onclick="showTab('custom')">Custom Orders</button>
        </div>

        <!-- Pending Requests -->
        <div id="pending" class="orders-section active">
            <div class="orders-grid">
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h4>Emma Wilson</h4>
                            <p>Order #ORD-2024-001 • 2 hours ago</p>
                        </div>
                        <span class="order-status status-pending">Pending</span>
                    </div>
                    <div class="order-items">
                        <span class="order-item">Chocolate Chip Cookies (2 dozen)</span>
                        <span class="order-item">Vanilla Cupcakes (12 pieces)</span>
                    </div>
                    <div class="order-meta">
                        <div class="meta-item">
                            <span class="meta-label">Delivery Date</span>
                            <span class="meta-value">Tomorrow, 2:00 PM</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Total Amount</span>
                            <span class="meta-value">$45.00</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Delivery Address</span>
                            <span class="meta-value">123 Oak Street, Downtown</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Contact</span>
                            <span class="meta-value">(555) 123-4567</span>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn btn-success">Accept Order</button>
                        <button class="btn btn-danger">Decline</button>
                        <button class="btn btn-secondary">Message Customer</button>
                    </div>
                </div>

                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h4>Mike Johnson</h4>
                            <p>Order #ORD-2024-002 • 4 hours ago</p>
                        </div>
                        <span class="order-status status-pending">Pending</span>
                    </div>
                    <div class="order-items">
                        <span class="order-item">Birthday Cake (8 inch)</span>
                        <span class="order-item">Chocolate Frosting</span>
                    </div>
                    <div class="order-meta">
                        <div class="meta-item">
                            <span class="meta-label">Delivery Date</span>
                            <span class="meta-value">Dec 15, 10:00 AM</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Total Amount</span>
                            <span class="meta-value">$35.00</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Delivery Address</span>
                            <span class="meta-value">456 Pine Ave, Suburbs</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Contact</span>
                            <span class="meta-value">(555) 987-6543</span>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn btn-success">Accept Order</button>
                        <button class="btn btn-danger">Decline</button>
                        <button class="btn btn-secondary">Message Customer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ongoing Orders -->
        <div id="ongoing" class="orders-section">
            <div class="orders-grid">
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h4>Sarah Davis</h4>
                            <p>Order #ORD-2024-003 • Accepted yesterday</p>
                        </div>
                        <span class="order-status status-in-progress">In Progress</span>
                    </div>
                    <div class="order-items">
                        <span class="order-item">Sourdough Bread (3 loaves)</span>
                        <span class="order-item">Dinner Rolls (24 pieces)</span>
                    </div>
                    <div class="order-meta">
                        <div class="meta-item">
                            <span class="meta-label">Delivery Date</span>
                            <span class="meta-value">Today, 4:00 PM</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Total Amount</span>
                            <span class="meta-value">$28.00</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Progress</span>
                            <span class="meta-value">Baking in oven</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Contact</span>
                            <span class="meta-value">(555) 456-7890</span>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn btn-primary">Mark as Ready</button>
                        <button class="btn btn-secondary">Update Progress</button>
                        <button class="btn btn-secondary">Message Customer</button>
                    </div>
                </div>

                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h4>Alex Thompson</h4>
                            <p>Order #ORD-2024-004 • Accepted 2 days ago</p>
                        </div>
                        <span class="order-status status-accepted">Ready for Pickup</span>
                    </div>
                    <div class="order-items">
                        <span class="order-item">Apple Pie (9 inch)</span>
                        <span class="order-item">Cinnamon Rolls (8 pieces)</span>
                    </div>
                    <div class="order-meta">
                        <div class="meta-item">
                            <span class="meta-label">Pickup Time</span>
                            <span class="meta-value">Today, 6:00 PM</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Total Amount</span>
                            <span class="meta-value">$42.00</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Status</span>
                            <span class="meta-value">Ready for pickup</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Contact</span>
                            <span class="meta-value">(555) 234-5678</span>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn btn-success">Mark as Delivered</button>
                        <button class="btn btn-secondary">Message Customer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Past Orders -->
        <div id="completed" class="orders-section">
            <div class="orders-grid">
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h4>Jennifer Lee</h4>
                            <p>Order #ORD-2024-005 • Completed 3 days ago</p>
                        </div>
                        <span class="order-status status-completed">Completed</span>
                    </div>
                    <div class="order-items">
                        <span class="order-item">Wedding Cupcakes (48 pieces)</span>
                        <span class="order-item">Vanilla & Chocolate Mix</span>
                    </div>
                    <div class="order-meta">
                        <div class="meta-item">
                            <span class="meta-label">Delivered</span>
                            <span class="meta-value">Dec 10, 2:00 PM</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Total Amount</span>
                            <span class="meta-value">$120.00</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Rating</span>
                            <span class="meta-value">⭐⭐⭐⭐⭐ (5.0)</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Payment</span>
                            <span class="meta-value">Paid</span>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn btn-secondary">View Review</button>
                        <button class="btn btn-secondary">Reorder Request</button>
                    </div>
                </div>

                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h4>Robert Chen</h4>
                            <p>Order #ORD-2024-006 • Completed 1 week ago</p>
                        </div>
                        <span class="order-status status-completed">Completed</span>
                    </div>
                    <div class="order-items">
                        <span class="order-item">Chocolate Brownies (16 pieces)</span>
                        <span class="order-item">Walnut Topping</span>
                    </div>
                    <div class="order-meta">
                        <div class="meta-item">
                            <span class="meta-label">Delivered</span>
                            <span class="meta-value">Dec 6, 3:30 PM</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Total Amount</span>
                            <span class="meta-value">$32.00</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Rating</span>
                            <span class="meta-value">⭐⭐⭐⭐ (4.0)</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Payment</span>
                            <span class="meta-value">Paid</span>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn btn-secondary">View Review</button>
                        <button class="btn btn-secondary">Reorder Request</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Orders -->
        <div id="custom" class="orders-section">
            <div class="orders-grid">
                <div class="order-card custom-order">
                    <div class="order-header">
                        <div class="order-info">
                            <h4>Maria Rodriguez</h4>
                            <p>Custom Order #CUST-2024-001 • 6 hours ago</p>
                        </div>
                        <span class="order-status status-pending">Pending</span>
                    </div>
                    <div class="custom-order-note">
                        <h5>Custom Request Details</h5>
                        <p>"Hi! I need a 3-tier wedding cake for 80 guests. The theme is rustic with buttercream flowers and naked cake style. Colors should be blush pink and sage green. The cake should be vanilla sponge with strawberry filling. Delivery needed on December 20th at 3 PM to Golden Gate Park pavilion."</p>
                    </div>
                    <div class="order-meta">
                        <div class="meta-item">
                            <span class="meta-label">Event Date</span>
                            <span class="meta-value">December 20, 3:00 PM</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Guest Count</span>
                            <span class="meta-value">80 people</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Budget Range</span>
                            <span class="meta-value">$200 - $350</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Contact</span>
                            <span class="meta-value">(555) 678-9012</span>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn btn-success">Accept & Quote</button>
                        <button class="btn btn-danger">Decline</button>
                        <button class="btn btn-secondary">Request More Details</button>
                    </div>
                </div>

                <div class="order-card custom-order">
                    <div class="order-header">
                        <div class="order-info">
                            <h4>David Kim</h4>
                            <p>Custom Order #CUST-2024-002 • 1 day ago</p>
                        </div>
                        <span class="order-status status-in-progress">In Progress</span>
                    </div>
                    <div class="custom-order-note">
                        <h5>Custom Request Details</h5>
                        <p>"Looking for gluten-free birthday cookies shaped like dinosaurs for my 6-year-old's party. Need about 20 cookies, decorated with green and blue icing. Also need them to be nut-free due to allergies. Party is this weekend."</p>
                    </div>
                    <div class="order-meta">
                        <div class="meta-item">
                            <span class="meta-label">Event Date</span>
                            <span class="meta-value">This Saturday, 2:00 PM</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Quantity</span>
                            <span class="meta-value">20 cookies</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Quoted Price</span>
                            <span class="meta-value">$55.00</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Special Requirements</span>
                            <span class="meta-value">Gluten-free, Nut-free</span>
                        </div>
                    </div>
                    <div class="order-actions">
                        <button class="btn btn-primary">Update Progress</button>
                        <button class="btn btn-secondary">Message Customer</button>
                        <button class="btn btn-secondary">Upload Photos</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function showTab(tabName) {
            // Hide all sections
            const sections = document.querySelectorAll('.orders-section');
            sections.forEach(section => section.classList.remove('active'));
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected section
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }
    </script>
</body>
</html>