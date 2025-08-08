<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'baker') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// Get baker_id
$baker_stmt = $conn->prepare("SELECT baker_id FROM bakers WHERE user_id = ?");
$baker_stmt->bind_param("i", $user_id);
$baker_stmt->execute();
$baker_result = $baker_stmt->get_result();
$baker = $baker_result->fetch_assoc();
$baker_id = $baker['baker_id'];

// === Handle form submission for order status update ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['order_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['order_status'];

    // Security: Make sure the order belongs to this baker
    $check_stmt = $conn->prepare("
        SELECT o.order_id FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN products p ON oi.product_id = p.product_id
        WHERE o.order_id = ? AND p.baker_id = ?
        LIMIT 1
    ");
    $check_stmt->bind_param("ii", $order_id, $baker_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $update_stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
        $update_stmt->bind_param("si", $new_status, $order_id);
        if ($update_stmt->execute()) {
            header("Location: bakerordermngmt.php?msg=Order updated successfully");
            exit();
        } else {
            header("Location: bakerordermngmt.php?error=Failed to update order");
            exit();
        }
    } else {
        header("Location: bakerordermngmt.php?error=Unauthorized order");
        exit();
    }
}

// Get all orders for this baker with totals
$orders_query = "SELECT 
   o.*,
    u.full_name as customer_name,
    u.phone as customer_phone,
    SUM(oi.total_price) as total_amount
FROM orders o
JOIN users u ON o.customer_id = u.user_id
JOIN order_items oi ON o.order_id = oi.order_id
JOIN products p ON oi.product_id = p.product_id
WHERE p.baker_id = $baker_id
GROUP BY o.order_id
ORDER BY o.order_date DESC";

$orders_result = mysqli_query($conn, $orders_query);

// Function to get order items
function getOrderItems($conn, $order_id)
{
    $stmt = $conn->prepare("SELECT * FROM order_items oi 
                            JOIN products p ON oi.product_id = p.product_id 
                            WHERE oi.order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Function to format time elapsed
function time_elapsed_string($datetime)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->d > 0)
        return $diff->d . " day(s) ago";
    if ($diff->h > 0)
        return $diff->h . " hour(s) ago";
    if ($diff->i > 0)
        return $diff->i . " minute(s) ago";
    return "Just now";
}

// Count orders for stats
$pending_count = $confirmed_count = $delivered_count = $total_count = 0;
$orders_array = [];

while ($order = mysqli_fetch_assoc($orders_result)) {
    $orders_array[] = $order;
    $total_count++;

    switch ($order['order_status']) {
        case 'pending':
            $pending_count++;
            break;
        case 'confirmed':
        case 'shipped':
            $confirmed_count++;
            break;
        case 'delivered':
            $delivered_count++;
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - Baker Dashboard</title>
    <link rel="stylesheet" href="bakerordermngmt.css">
</head>

<body>
    <?php include 'bakernavbar.php'; ?>



    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h2>Order Management</h2>
            <p>Track and manage all your orders in one place</p>
        </div>


        <!-- Success/Error Messages -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Pending Requests</h3>
                <div class="number"><?php echo $pending_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>In Progress</h3>
                <div class="number"><?php echo $confirmed_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>Completed</h3>
                <div class="number"><?php echo $delivered_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Orders</h3>
                <div class="number"><?php echo $total_count; ?></div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="showTab('pending')">Pending Requests</button>
            <button class="tab" onclick="showTab('ongoing')">Ongoing Orders</button>
            <button class="tab" onclick="showTab('completed')">Past Orders</button>
        </div>



        <!-- Pending Orders -->
        <div id="pending" class="orders-section active">
            <div class="orders-grid">
                <?php
                $found_pending = false;
                foreach ($orders_array as $order):
                    if ($order['order_status'] != 'pending')
                        continue;
                    $found_pending = true;

                    // Get order items
                    $items_result = getOrderItems($conn, $order['order_id']);
                    $items = [];
                    while ($item = $items_result->fetch_assoc()) {
                        $items[] = $item['name'] . ' (' . $item['quantity'] . ')';
                    }
                    ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h4><?php echo htmlspecialchars($order['customer_name']); ?></h4>
                                <p>Order #<?php echo $order['order_id']; ?> •
                                    <?php echo time_elapsed_string($order['order_date']); ?>
                                </p>
                            </div>
                            <span class="order-status status-pending">Pending</span>
                        </div>
                        <div class="order-items">
                            <?php foreach ($items as $item): ?>
                                <span class="order-item"><?php echo htmlspecialchars($item); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Delivery Date</span>
                                <span
                                    class="meta-value"><?php echo date('M j, Y g:i A', strtotime($order['delivery_date'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total Amount</span>
                                <span class="meta-value">$<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Delivery Address</span>
                                <span class="meta-value"><?php echo htmlspecialchars($order['delivery_address']); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Contact</span>
                                <span class="meta-value"><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                            </div>
                        </div>
                        <div class="order-actions">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <input type="hidden" name="order_status" value="confirmed">
                                <button type="submit" class="btn btn-success">Accept Order</button>
                            </form>

                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <input type="hidden" name="order_status" value="cancelled">
                                <button type="submit" class="btn btn-danger">Decline</button>
                            </form>

                            <button class="btn btn-secondary">Message Customer</button>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (!$found_pending): ?>
                    <div class="no-orders">
                        <h3>No Pending Orders</h3>
                        <p>You currently have no pending orders.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Ongoing Orders -->
        <div id="ongoing" class="orders-section">
            <div class="orders-grid">
                <?php
                $found_ongoing = false;
                foreach ($orders_array as $order):
                    if (!in_array($order['order_status'], ['confirmed', 'shipped']))
                        continue;
                    $found_ongoing = true;

                    // Get order items
                    $items_result = getOrderItems($conn, $order['order_id']);
                    $items = [];
                    while ($item = $items_result->fetch_assoc()) {
                        $items[] = $item['name'] . ' (' . $item['quantity'] . ')';
                    }
                    ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h4><?php echo htmlspecialchars($order['customer_name']); ?></h4>
                                <p>Order #<?php echo $order['order_id']; ?> •
                                    <?php echo time_elapsed_string($order['order_date']); ?>
                                </p>
                            </div>
                            <span class="order-status status-<?php echo $order['order_status']; ?>">
                                <?php echo $order['order_status'] == 'confirmed' ? 'In Progress' : 'Ready for Pickup'; ?>
                            </span>
                        </div>
                        <div class="order-items">
                            <?php foreach ($items as $item): ?>
                                <span class="order-item"><?php echo htmlspecialchars($item); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Delivery Date</span>
                                <span
                                    class="meta-value"><?php echo date('M j, Y g:i A', strtotime($order['delivery_date'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total Amount</span>
                                <span class="meta-value">$<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Contact</span>
                                <span class="meta-value"><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                            </div>
                        </div>
                        <div class="order-actions">
                            <?php if ($order['order_status'] == 'confirmed'): ?>
                                <form method="POST" style="display:inline;">
                                 <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <input type="hidden" name="order_status" value="shipped">
                                    <button type="submit" class="btn btn-primary">Mark as Ready</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <input type="hidden" name="order_status" value="delivered">
                                    <button type="submit" class="btn btn-success">Mark as Delivered</button>
                                </form>
                            <?php endif; ?>
                            <button class="btn btn-secondary">Message Customer</button>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (!$found_ongoing): ?>
                    <div class="no-orders">
                        <h3>No Ongoing Orders</h3>
                        <p>You currently have no orders in progress.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Past Orders -->
        <div id="completed" class="orders-section">
            <div class="orders-grid">
                <?php
                $found_completed = false;
                foreach ($orders_array as $order):
                    if ($order['order_status'] != 'delivered')
                        continue;
                    $found_completed = true;

                    // Get order items
                    $items_result = getOrderItems($conn, $order['order_id']);
                    $items = [];
                    while ($item = $items_result->fetch_assoc()) {
                        $items[] = $item['name'] . ' (' . $item['quantity'] . ')';
                    }
                    ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h4><?php echo htmlspecialchars($order['customer_name']); ?></h4>
                                <p>Order #<?php echo $order['order_id']; ?> • Completed
                                    <?php echo time_elapsed_string($order['order_date']); ?>
                                </p>
                            </div>
                            <span class="order-status status-completed">Completed</span>
                        </div>
                        <div class="order-items">
                            <?php foreach ($items as $item): ?>
                                <span class="order-item"><?php echo htmlspecialchars($item); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Delivered</span>
                                <span
                                    class="meta-value"><?php echo date('M j, Y g:i A', strtotime($order['delivery_date'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total Amount</span>
                                <span class="meta-value">$<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Payment</span>
                                <span class="meta-value"><?php echo ucfirst($order['payment_status']); ?></span>
                            </div>
                        </div>
                        <div class="order-actions">
                            <button class="btn btn-secondary">View Details</button>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (!$found_completed): ?>
                    <div class="no-orders">
                        <h3>No Completed Orders</h3>
                        <p>You haven't completed any orders yet.</p>
                    </div>
                <?php endif; ?>
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

        function updateOrder(orderId, newStatus) {
            if (confirm('Are you sure you want to update this order?')) {
                window.location.href = 'bakerordermngmt.php?id=' + orderId + '&status=' + newStatus;
            }
        }
    </script>
</body>

</html>