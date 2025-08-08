<?php
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

$customer_id = $_SESSION['user_id'];

// Fetch customer orders
$stmt = $conn->prepare(" SELECT *
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    JOIN bakers b ON p.baker_id = b.baker_id
    WHERE o.customer_id = ?
    ORDER BY o.order_date DESC
");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$orders_result = $stmt->get_result();

// Group results by order_id
$orders = [];
while ($row = $orders_result->fetch_assoc()) {
    $orders[$row['order_id']]['details'] = $row;
    $orders[$row['order_id']]['items'][] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | BakeJourney</title>
    <link rel="stylesheet" href="customerorders.css">
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
                    <button class="filter-tab" onclick="showTab('active')">Active</button>
                    <button class="filter-tab" onclick="showTab('completed')">Completed</button>
                    <button class="filter-tab" onclick="showTab('cancelled')">Cancelled</button>
                </div>
            </div>

            <!-- Order Card 1 -->
            <?php if (empty($orders)): ?>
                <div class="no-orders">
                    <p>You have no orders yet. Start shopping now!</p>
                    <a href="products.php" class="btn btn-primary">Shop Now</a>
                </div>

            <?php else: ?>
                <?php foreach ($orders as $order_id => $order):
                    $order_info = $order['details'];
                    $items = $order['items'];

                    $can_pay = true;
                    foreach ($items as $item) {
                        if ($item['baker_status'] !== 'accepted') {
                            $can_pay = false;
                            break;
                        }
                    }

                    $order_datetime = new DateTime($order_info['order_date']);
                    $now = new DateTime();
                    $interval = $order_datetime->diff($now);
                    $within_24hrs = $interval->days == 0 && $interval->h < 24;

                    $total_amount = array_sum(array_column($items, 'total_price'));
                    ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <div class="order-number">Order ORBKET<?= $order_id ?></div>
                                <div class="order-date">Placed on <?= date('F j, Y', strtotime($order_info['order_date'])) ?>
                                </div>
                                <span
                                    class="order-status status-<?= $order_info['order_status'] ?>"><?= ucfirst($order_info['order_status']) ?></span>
                            </div>
                        </div>

                        <?php foreach ($items as $item): ?>
                            <div class="order-details">
                                <div class="baker-info">
                                    <img src="https://images.unsplash.com/photo-1594736797933-d0401ba0ad65?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                                        alt="<?php echo $item['brand_name']; ?>" class="baker-avatar">
                                    <div>
                                        <div class="baker-name"><?php echo $item['brand_name']; ?></div>
                                        
                                    </div>
                                </div>
                                <div class="items-list">
                                    <div class="order-item">
                                        <div class="item-info">
                                            <div class="item-name"><?php echo $item['name']; ?></div>
                                            <div class="item-details">Quantity: <?php echo $item['quantity']; ?> × $<?php echo $item['price']; ?></div>
                                        </div>
                                        <div class="item-price">$<?php echo $item['total_price']; ?></div>
                                    </div>

                                    <div class="order-total">
                                        <span class="total-label">Total Amount:</span>
                                        <span class="total-amount">$<?php echo $total_amount; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="order-actions">
                                        <?php if ($order_info['order_status'] === 'pending' && $can_pay): ?>
                    <form action="payment.php" method="POST" style="display:inline;">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>">
                        <input type="hidden" name="amount" value="<?= $total_amount ?>">
                        <button type="submit" name="action" value="pay" class="btn btn-primary">Pay ₹<?= $total_amount ?></button>
                        <button type="submit" name="action" value="cancel" class="btn btn-secondary">Cancel</button>
                    </form>
                <?php endif; ?>

                <?php if ($order_info['order_status'] !== 'cancelled' && $order_info['order_status'] !== 'delivered'): ?>
                    <form name="cancel_order" method="POST" style="display:inline;">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>">
                        <?php if ($within_24hrs): ?>
                            <button type="submit" name="cancel_confirm" class="btn btn-danger">Cancel Order</button>
                        <?php else: ?>
                            <button type="submit" disabled title="Can't cancel after 24 hours">Cancel (Expired)</button>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>

                <?php if ($order_info['payment_status'] === 'success' && $order_info['order_status'] !== 'cancelled'): ?>
                    <form  method="GET" style="display:inline;">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>">
                        <button onclick="openTrackingSidebar()" type="submit" class="btn btn-primary">Track Order</button>
                    </form>
                <?php endif; ?>


                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>


        </section>
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
    <?php include 'globalfooter.php'; ?>

    <?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_confirm'])) {
    $order_id = $_POST['order_id'];

    // Check time limit (24 hours)
    $stmt = $conn->prepare("SELECT order_date FROM orders WHERE order_id = ? AND customer_id = ?");
    $stmt->bind_param("ii", $order_id, $customer_id);
    $stmt->execute();
    $stmt->bind_result($order_date);
    $stmt->fetch();
    $stmt->close();

    if ($order_date) {
        $order_time = strtotime($order_date);
        $now = time();

        if (($now - $order_time) <= 86400) {
            // ✅ Update order status to 'cancelled'
            $stmt = $conn->prepare("UPDATE orders SET order_status = 'cancelled' WHERE order_id = ? AND customer_id = ?");
            $stmt->bind_param("ii", $order_id, $customer_id);
            $stmt->execute();

            // ✅ Delete order items
            $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
        }
    }
}

// PAYMENT FORM HANDLER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['pay', 'cancel'])) {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];

    // Make sure all baker_status = 'accepted'
    $stmt = $conn->prepare("SELECT COUNT(*) FROM order_items WHERE order_id = ? AND baker_status != 'accepted'");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($unaccepted_count);
    $stmt->fetch();
    $stmt->close();

    if ($unaccepted_count == 0) {
        $status = $action === 'pay' ? 'success' : 'failed';
        $order_status = $status === 'success' ? 'confirmed' : 'pending';
        // Update payment_status
        $stmt = $conn->prepare("UPDATE orders SET payment_status = ?, order_status = ? WHERE order_id = ? AND customer_id = ?");
        $stmt->bind_param("ssii", $status, $order_status, $order_id, $customer_id);
        $stmt->execute();

        if ($status === 'success') {
            // Redirect to track page or reload
            header("Location: customerorders.php?paid=1");
            exit;
        }
    }
}










    //  Handle cancellation
    // if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    //     $order_id = $_POST['order_id'];

    //      Check if cancel is within 24 hrs
    //     $stmt = $conn->prepare("SELECT order_date, order_status FROM orders WHERE order_id = ? AND customer_id = ?");
    //     $stmt->bind_param("ii", $order_id, $customer_id);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $order = $result->fetch_assoc();

    //     if ($order && in_array($order['order_status'], ['pending', 'confirmed'])) {
    //         $order_date = new DateTime($order['order_date']);
    //         $now = new DateTime();
    //         $interval = $now->diff($order_date);

    //         if ($interval->days < 1) {
    //              Delete order (cascade deletes items)
    //             $stmt = $conn->prepare("UPDATE orders SET order_status = 'cancelled' WHERE order_id = ?");
    //             $stmt->bind_param("i", $order_id);
    //             $stmt->execute();
    //         }
    //     }
    // }
    ?>

</body>

</html>