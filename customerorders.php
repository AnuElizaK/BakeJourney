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

// Group orders by order_id and fetch items
$query = "
    SELECT o.*, oi.*, p.name, p.image, b.brand_name, b.baker_id
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    JOIN bakers b ON p.baker_id = b.baker_id
    WHERE o.customer_id = ?
    ORDER BY o.order_date DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
$total_orders = 0;
$active_orders = 0;
$completed_orders = 0;
$total_spent = 0;

while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'order_id' => $order_id,
            'order_date' => $row['order_date'],
            'order_status' => $row['order_status'],
            'payment_status' => $row['payment_status'],
            'delivery_address' => $row['delivery_address'],
            'delivery_date' => $row['delivery_date'],
            'items' => [],
            'original_total' => 0,
            'payable_total' => 0,
            'can_pay' => false,
            'has_accepted_items' => false,
            'all_items_accepted' => true
        ];
        $total_orders++;

        // Count active/completed orders
        if (in_array($row['order_status'], ['pending', 'confirmed', 'shipped'])) {
            $active_orders++;
        } elseif ($row['order_status'] === 'delivered') {
            $completed_orders++;
        }
    }

    $orders[$order_id]['items'][] = $row;

    // Calculate totals
    $item_total = $row['unit_price'] * $row['quantity'];
    $orders[$order_id]['original_total'] += $item_total;

    if ($row['baker_status'] === 'accepted') {
        $orders[$order_id]['payable_total'] += $item_total;
        $orders[$order_id]['has_accepted_items'] = true;

        // Add to total spent if payment is successful
        if ($row['payment_status'] === 'success') {
            $total_spent += $item_total;
        }
    } else {
        $orders[$order_id]['all_items_accepted'] = false;
    }
}

// Determine if orders can be paid
foreach ($orders as &$order) {
    $order['can_pay'] = $order['has_accepted_items'] && $order['payment_status'] !== 'success';
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
<?php renderTrackingSidebar(); ?>

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
                    <span class="stat-number"><?= $total_orders ?></span>
                    <span class="stat-label">Total Orders</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?= $active_orders ?></span>
                    <span class="stat-label">Active Orders</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number"><?= $completed_orders ?></span>
                    <span class="stat-label">Completed</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">₹<?= number_format($total_spent, 0) ?></span>
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

            <?php if (empty($orders)): ?>
                <div class="no-orders">
                    <p>🛍️ You have no orders yet. Start shopping now!</p>
                    <a href="products.php" class="btn btn-primary">Shop Now</a>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <div class="order-number">Order ORBKET<?= $order['order_id']; ?></div>
                                <div class="order-date">Placed on <?= date('d M Y', strtotime($order['order_date'])); ?></div>
                            </div>
                            <span class="order-status status-<?= $order['order_status']; ?>">
                                <?= ucfirst($order['order_status']); ?>
                            </span>
                        </div>

                        <div class="order-details">
                            <div class="items-list">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="order-item">
                                        <div class="item-info">
                                            <div class="item-name"><?= htmlspecialchars($item['name']); ?></div>
                                            <div class="item-details">
                                                Quantity: <?= $item['quantity']; ?> × ₹<?= number_format($item['unit_price'], 2); ?>
                                            </div>
                                            <div class="baker-name">by: <?= htmlspecialchars($item['brand_name']); ?></div>
                                        </div>
                                        <div class="item-price">
                                            <div>₹<?= number_format($item['total_price'], 2); ?></div>
                                            <span class="baker-status status-<?= $item['baker_status']; ?>">
                                                <?php
                                                $status = $item['baker_status'];
                                                if ($status == 'accepted')
                                                    echo '✅ Accepted';
                                                elseif ($status == 'pending')
                                                    echo '⏳ Pending';
                                                else
                                                    echo '❌ Rejected';
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Pricing Information -->
                            <div class="pricing-info">
                                <div class="price-breakdown">
                                    <span>Original Total:</span>
                                    <span>₹<?= number_format($order['original_total'], 2) ?></span>
                                </div>

                                <?php if ($order['original_total'] != $order['payable_total']): ?>
                                    <div class="rejected-notice">
                                        Some items were rejected by bakers and removed from total
                                    </div>
                                <?php endif; ?>

                                <div class="price-breakdown payable-amount">
                                    <span>Payable Amount:</span>
                                    <span>
                                        <?php if ($order['payment_status'] === 'success'): ?>
                                            ₹<?= number_format($order['payable_total'], 2) ?>
                                        <?php else: ?>
                                            ₹0.00
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Order Actions -->
                            <div class="order-actions">
                                <?php
                                $order_time = strtotime($order['order_date']);
                                $within_24hrs = (time() - $order_time) < 86400;
                                $status = $order['order_status'];
                                $paid = $order['payment_status'] === 'success';
                                $can_pay = $order['can_pay'];
                                ?>

                                <!-- Pay Now button if there are accepted items and not paid -->
                                <?php if (!$paid && $can_pay && $order['payable_total'] > 0): ?>
                                    <form method="POST" action="payments.php" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                        <input type="hidden" name="amount" value="<?= $order['payable_total'] ?>">
                                        <button type="submit" name="action" value="pay" class="btn btn-primary">
                                            💰 Pay ₹<?= number_format($order['payable_total'], 2) ?>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- Cancel button if within 24 hours and not paid/shipped/delivered -->
                                <?php if (!$paid && $within_24hrs && !in_array($status, ['shipped', 'delivered', 'cancelled'])): ?>
                                    <form method="POST" action="payments.php" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                        <button type="submit" name="action" value="cancel" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to cancel this order?')">
                                            ❌ Cancel Order
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- Track Order button if paid -->
                                <?php if ($paid && $status !== 'cancelled'): ?>
                                    <a href="?order_id=<?= $order['order_id'] ?>" class="btn btn-primary"
                                        onclick="event.preventDefault(); openTrackingSidebar(); loadOrderTracking(<?= $order['order_id'] ?>)">
                                        📦 Track Order
                                    </a>
                                <?php endif; ?>

                                <!-- Show payment status -->
                                <?php if ($paid): ?>
                                    <span style="color: #28a745; font-weight: bold; padding: 8px 16px;">
                                        ✅ Payment Successful
                                    </span>
                                <?php elseif ($order['payment_status'] === 'failed'): ?>
                                    <span style="color: #dc3545; font-weight: bold; padding: 8px 16px;">
                                        ❌ Payment Failed
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <script>
        function showTab(tab) {
            // Remove active class from all tabs
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));

            // Add active class to clicked tab
            event.target.classList.add('active');

            // Filter orders based on tab
            const orders = document.querySelectorAll('.order-card');
            orders.forEach(order => {
                const status = order.querySelector('.order-status').textContent.toLowerCase().trim();

                switch (tab) {
                    case 'active':
                        order.style.display = ['pending', 'confirmed', 'shipped'].includes(status) ? 'block' : 'none';
                        break;
                    case 'completed':
                        order.style.display = status === 'delivered' ? 'block' : 'none';
                        break;
                    case 'cancelled':
                        order.style.display = status === 'cancelled' ? 'block' : 'none';
                        break;
                    default:
                        order.style.display = 'block';
                }
            });
        }

        function loadOrderTracking(orderId) {
            // Redirect to current page with order_id parameter for tracking sidebar
            window.location.href = `?order_id=${orderId}`;
        }

        // Auto-open tracking sidebar if order_id is in URL
        <?php if (isset($_GET['order_id'])): ?>
            document.addEventListener('DOMContentLoaded', function () {
                openTrackingSidebar();
            });
        <?php endif; ?>
    </script>

    <?php include 'globalfooter.php'; ?>
</body>

</html>