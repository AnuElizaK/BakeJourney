<?php
/*
session_start();
include 'db.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php"); // Redirect to login if not authorized
    exit();
}
// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

$ordStmt = $conn->prepare("
  SELECT *
  FROM orders ord
  JOIN bakers b ON ord.baker_id = b.baker_id
  JOIN users u ON ord.user_id = u.user_id
  JOIN order_items o ON ord.order_id = o.order_id
  JOIN products p ON o.product_id = p.product_id
");
$ordStmt->execute();
$pResult = $ordStmt->get_result();
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | BakeJourney</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            position: relative;
        }

        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            padding-top: 80px;
            color: #1f2a38;
            background: linear-gradient(#fff1bb, #ffffff);
            min-height: 100vh;
            padding-top: 120px;
        }

        h1,
        h2 {
            font-family: 'Puanto', Roboto, sans-serif;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding-bottom: 40px;
        }

        /* Page Header */
        .page-title {
            color: #1f2a38;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 1rem;
            margin-bottom: 30px;
        }

        /* Orders Overview */
        .orders-overview {
            margin: 2rem 0;
            border-radius: 15px;
            padding: 2rem;
            background: white;
            border: 2px solid #fee996;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            margin-bottom: 30px;
            text-align: center;
        }

        .overview-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #fef7cd 0%, #fee996 100%);
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            border: 2px solid #fee996;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: #f59e0b;
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #f59e0b;
            display: block;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
        }

        /* Orders Section */
        .orders-section {
            margin: 2rem 0;
            border-radius: 15px;
            padding: 2rem;
            background: white;
            border: 2px solid #fee996;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            color: #1f2a38;
            font-weight: bold;
        }

        .filter-tabs {
            display: flex;
            gap: 1rem;
        }

        .filter-tab {
            padding: 0.5rem 1rem;
            border: 1.5px solid #f59e0b;
            background: transparent;
            color: #f59e0b;
            border-radius: 25px;
            font-family: 'Segoe UI', Roboto, sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-tab:hover {
            background: #fee996;
            color: #d97706;
        }

        .filter-tab.active {
            background: #f59e0b;
            color: white;
        }

        /* Order Card */
        .order-card {
            background: #fef7cd;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #fcd34d;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .order-info {
            flex: 1;
        }

        .order-number {
            font-size: 1.1rem;
            font-weight: bold;
            color: #1f2a38;
            margin-bottom: 0.25rem;
        }

        .order-date {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .order-status {
            display: inline-block;
            padding: 0.25rem 1rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-confirmed {
            background: #99f9b7ff;
            color: #016a30ff;
        }

        .status-preparing {
            background: #fee996;
            color: #d97706;
        }

        .status-ready {
            background: #cce5ff;
            color: #004085;
        }

        .status-delivered {
            background: #e3d3efff;
            color: #3a0c60ff;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .order-details {
            margin: 1rem 0;
        }

        .baker-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .baker-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .baker-name {
            font-weight: 600;
            color: #1f2a38;
        }

        .items-list {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0.5rem;
            border-bottom: 1px solid #d1d5db;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #1f2a38;
        }

        .item-details {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .item-price {
            font-weight: bold;
            color: #f59e0b;
        }

        .order-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid #fcd34d;
        }

        .total-label {
            font-size: 1.1rem;
            font-weight: bold;
            color: #1f2a38;
        }

        .total-amount {
            font-size: 1.2rem;
            font-weight: bold;
            color: #f59e0b;
        }

        .order-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #fcd34d, #f59e0b);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230, 126, 34, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #fcd34d, #f59e0b);
            color: white;

        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .btn-danger {
            background: transparent;
            color: #dc2626;
            border: 1.5px solid #dc2626;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #f87171, #dc2626);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #1f2a38;
        }

        .empty-text {
            margin-bottom: 2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .overview-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .section-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .filter-tabs {
                justify-content: center;
            }

            .order-header {
                flex-direction: column;
                gap: 0.5rem;
            }

            .order-actions {
                flex-direction: column;
            }

            .order-total {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .overview-stats {
                grid-template-columns: 1fr;
            }

            .filter-tabs {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-header,
        .orders-overview,
        .orders-section {
            animation: fadeInUp 0.6s ease forwards;
        }

        .orders-overview {
            animation-delay: 0.1s;
        }

        .orders-section {
            animation-delay: 0.2s;
        }
    </style>
</head>

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<!-- Include the sidebar -->
<?php include 'tracking-sidebar.php'; ?>

<!-- Render the sidebar with order data -->
<?php renderTrackingSidebar($orderData); ?>

<body>

    <!-- Main Content -->
    <main class="container">
        <!-- Page Header -->
        <h1 class="page-title">My Orders</h1>
        <p class="page-subtitle">Track your baking orders and manage your purchases</p>

        <!-- Orders Overview -->
        <section class="orders-overview">
            <div class="overview-stats">
                <div class="stat-card">
                    <span class="stat-number">12</span>
                    <span class="stat-label">Total Orders</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">3</span>
                    <span class="stat-label">Active Orders</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">8</span>
                    <span class="stat-label">Completed</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">$247</span>
                    <span class="stat-label">Total Spent</span>
                </div>
            </div>
        </section>

        <!-- Orders Section -->
        <section class="orders-section">
            <div class="section-header">
                <h2 class="section-title">Recent Orders</h2>
                <div class="filter-tabs">
                    <button class="filter-tab active">All</button>
                    <button class="filter-tab">Active</button>
                    <button class="filter-tab">Completed</button>
                    <button class="filter-tab">Cancelled</button>
                </div>
            </div>

            <!-- Order Card 1 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-info">
                        <div class="order-number">Order #BJ-2024-001</div>
                        <div class="order-date">Placed on January 15, 2024</div>
                        <span class="order-status status-preparing">Preparing</span>
                    </div>
                </div>

                <div class="order-details">
                    <div class="baker-info">
                        <img src="https://images.unsplash.com/photo-1594736797933-d0401ba0ad65?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                            alt="Sarah Johnson" class="baker-avatar">
                        <div>
                            <div class="baker-name">Sarah Johnson</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Artisan Breads & Sourdoughs</div>
                        </div>
                    </div>

                    <div class="items-list">
                        <div class="order-item">
                            <div class="item-info">
                                <div class="item-name">Classic Sourdough Loaf</div>
                                <div class="item-details">Quantity: 2 × $12.00</div>
                            </div>
                            <div class="item-price">$24.00</div>
                        </div>
                        <div class="order-item">
                            <div class="item-info">
                                <div class="item-name">Whole Wheat Bread</div>
                                <div class="item-details">Quantity: 1 × $15.00</div>
                            </div>
                            <div class="item-price">$15.00</div>
                        </div>
                    </div>

                    <div class="order-total">
                        <span class="total-label">Total Amount:</span>
                        <span class="total-amount">$39.00</span>
                    </div>
                </div>

                <div class="order-actions">
                    <a href="#" class="btn btn-primary" onclick="openTrackingSidebar()">Track Order</a>
                    <a href="#" class="btn btn-secondary">Contact Baker</a>
                    <a href="#" class="btn btn-danger">Cancel Order</a>
                </div>
            </div>

            <!-- Order Card 2 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-info">
                        <div class="order-number">Order #BJ-2024-002</div>
                        <div class="order-date">Placed on January 12, 2024</div>
                        <span class="order-status status-ready">Ready for Pickup</span>
                    </div>
                </div>

                <div class="order-details">
                    <div class="baker-info">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                            alt="Mike Chen" class="baker-avatar">
                        <div>
                            <div class="baker-name">Mike Chen</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Pastry Specialist</div>
                        </div>
                    </div>

                    <div class="items-list">
                        <div class="order-item">
                            <div class="item-info">
                                <div class="item-name">Chocolate Croissants</div>
                                <div class="item-details">Quantity: 6 × $3.50</div>
                            </div>
                            <div class="item-price">$21.00</div>
                        </div>
                        <div class="order-item">
                            <div class="item-info">
                                <div class="item-name">Blueberry Muffins</div>
                                <div class="item-details">Quantity: 4 × $2.75</div>
                            </div>
                            <div class="item-price">$11.00</div>
                        </div>
                    </div>

                    <div class="order-total">
                        <span class="total-label">Total Amount:</span>
                        <span class="total-amount">$32.00</span>
                    </div>
                </div>

                <div class="order-actions">
                    <a href="#" class="btn btn-primary">Get Directions</a>
                    <a href="#" class="btn btn-secondary">Contact Baker</a>
                </div>
            </div>

            <!-- Order Card 3 -->
            <div class="order-card">
                <div class="order-header">
                    <div class="order-info">
                        <div class="order-number">Order #BJ-2024-003</div>
                        <div class="order-date">Completed on January 8, 2024</div>
                        <span class="order-status status-delivered">Delivered</span>
                    </div>
                </div>

                <div class="order-details">
                    <div class="baker-info">
                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                            alt="Emma Wilson" class="baker-avatar">
                        <div>
                            <div class="baker-name">Emma Wilson</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Cake Designer</div>
                        </div>
                    </div>

                    <div class="items-list">
                        <div class="order-item">
                            <div class="item-info">
                                <div class="item-name">Custom Birthday Cake</div>
                                <div class="item-details">8-inch vanilla cake with buttercream</div>
                            </div>
                            <div class="item-price">$45.00</div>
                        </div>
                    </div>

                    <div class="order-total">
                        <span class="total-label">Total Amount:</span>
                        <span class="total-amount">$45.00</span>
                    </div>
                </div>

                <div class="order-actions">
                    <a href="#" class="btn btn-primary">Rate & Review</a>
                    <a href="#" class="btn btn-secondary">Reorder</a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'globalfooter.php'; ?>

</body>

</html>