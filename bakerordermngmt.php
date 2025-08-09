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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_item_id']) && isset($_POST['baker_status'])) {
    $order_item_id = intval($_POST['order_item_id']);
    $new_status = $_POST['baker_status'];

    // Security: Make sure the order item belongs to this baker
    $check_stmt = $conn->prepare("
        SELECT oi.order_item_id FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        WHERE oi.order_item_id = ? AND p.baker_id = ?
        LIMIT 1
    ");
    $check_stmt->bind_param("ii", $order_item_id, $baker_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $update_stmt = $conn->prepare("UPDATE order_items SET baker_status = ? WHERE order_item_id = ?");
        $update_stmt->bind_param("si", $new_status, $order_item_id);
        if ($update_stmt->execute()) {
            $message = ($new_status == 'accepted') ? 'Order accepted successfully!' : 
                      (($new_status == 'rejected') ? 'Order rejected successfully!' : 'Order updated successfully!');
            header("Location: bakerordermngmt.php?msg=" . urlencode($message));
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

// Get all orders for this baker with order items
$orders_query = "
    SELECT DISTINCT
        o.order_id,
        o.order_date,
        o.delivery_date,
        o.delivery_address,
        o.payment_status,
        o.order_status,
        u.full_name as customer_name,
        u.phone as customer_phone,
        u.email as customer_email
    FROM orders o
    JOIN users u ON o.customer_id = u.user_id
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    WHERE p.baker_id = ?
    ORDER BY o.order_date DESC
";

$stmt = $conn->prepare($orders_query);
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$orders_result = $stmt->get_result();

// Function to get order items for a specific baker
function getBakerOrderItems($conn, $order_id, $baker_id)
{
    $stmt = $conn->prepare("
        SELECT oi.*, p.name as product_name, p.price, p.image
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.product_id 
        WHERE oi.order_id = ? AND p.baker_id = ?
    ");
    $stmt->bind_param("ii", $order_id, $baker_id);
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

// Process orders and count stats
$pending_count = $accepted_count = $completed_count = $total_count = 0;
$orders_array = [];

while ($order = $orders_result->fetch_assoc()) {
    // Get items for this baker in this order
    $items_result = getBakerOrderItems($conn, $order['order_id'], $baker_id);
    $items = [];
    $order_total = 0;
    $has_pending = false;
    $has_accepted = false;
    $all_delivered = true;
    
    while ($item = $items_result->fetch_assoc()) {
        $items[] = $item;
        $order_total += $item['total_price'];
        
        if ($item['baker_status'] == 'pending') {
            $has_pending = true;
            $all_delivered = false;
        } elseif ($item['baker_status'] == 'accepted') {
            $has_accepted = true;
            $all_delivered = false;
        } elseif ($item['baker_status'] != 'delivered') {
            $all_delivered = false;
        }
    }
    
    // Determine order category for this baker
    $order['items'] = $items;
    $order['total_amount'] = $order_total;
    
    if ($has_pending) {
        $order['baker_category'] = 'pending';
        $pending_count++;
    } elseif ($has_accepted && !$all_delivered) {
        $order['baker_category'] = 'accepted';
        $accepted_count++;
    } elseif ($all_delivered && count($items) > 0) {
        $order['baker_category'] = 'completed';
        $completed_count++;
    } else {
        $order['baker_category'] = 'other';
    }
    
    if (count($items) > 0) {
        $orders_array[] = $order;
        $total_count++;
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
                <div class="number"><?php echo $accepted_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>Completed</h3>
                <div class="number"><?php echo $completed_count; ?></div>
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
                    if ($order['baker_category'] != 'pending')
                        continue;
                    $found_pending = true;
                ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h4><?php echo htmlspecialchars($order['customer_name']); ?></h4>
                                <p>Order #ORBKET<?php echo $order['order_id']; ?> • 
                                   <?php echo time_elapsed_string($order['order_date']); ?></p>
                            </div>
                            <div>
                                <span class="order-status status-pending">New Request</span>
                                <span class="payment-status payment-<?php echo $order['payment_status']; ?>">
                                    Payment: <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="order-items-detailed">
                            <h5>Your Products in this Order:</h5>
                            <?php foreach ($order['items'] as $item): ?>
                                <?php if ($item['baker_status'] == 'pending'): ?>
                                    <div class="item-row">
                                        <div>
                                            <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                            <br>Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['unit_price'], 2); ?>
                                            <br>Total: ₹<?php echo number_format($item['total_price'], 2); ?>
                                        </div>
                                        <div class="item-actions">
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="order_item_id" value="<?php echo $item['order_item_id']; ?>">
                                                <input type="hidden" name="baker_status" value="accepted">
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        onclick="return confirm('Accept this item?')">Accept</button>
                                            </form>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="order_item_id" value="<?php echo $item['order_item_id']; ?>">
                                                <input type="hidden" name="baker_status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Reject this item?')">Reject</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Delivery Date</span>
                                <span class="meta-value"><?php echo date('M j, Y g:i A', strtotime($order['delivery_date'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Your Items Total</span>
                                <span class="meta-value">₹<?php echo number_format($order['total_amount'], 2); ?></span>
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
                    </div>
                <?php endforeach; ?>

                <?php if (!$found_pending): ?>
                    <div class="no-orders">
                        <h3>No Pending Orders</h3>
                        <p>You currently have no pending order requests.</p>
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
                    if ($order['baker_category'] != 'accepted')
                        continue;
                    $found_ongoing = true;
                ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h4><?php echo htmlspecialchars($order['customer_name']); ?></h4>
                                <p>Order #ORBKET<?php echo $order['order_id']; ?> • 
                                   <?php echo time_elapsed_string($order['order_date']); ?></p>
                            </div>
                            <div>
                                <span class="order-status status-accepted">In Progress</span>
                                <span class="payment-status payment-<?php echo $order['payment_status']; ?>">
                                    Payment: <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="order-items-detailed">
                            <h5>Your Accepted Products:</h5>
                            <?php foreach ($order['items'] as $item): ?>
                                <?php if ($item['baker_status'] == 'accepted'): ?>
                                    <div class="item-row">
                                        <div>
                                            <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                            <span class="item-status status-accepted">Accepted</span>
                                            <br>Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['unit_price'], 2); ?>
                                            <br>Total: ₹<?php echo number_format($item['total_price'], 2); ?>
                                        </div>
                                        <div class="item-actions">
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="order_item_id" value="<?php echo $item['order_item_id']; ?>">
                                                <input type="hidden" name="baker_status" value="delivered">
                                                <button type="submit" class="btn btn-success btn-sm"
                                                        onclick="return confirm('Mark this item as delivered?')">Mark Delivered</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Delivery Date</span>
                                <span class="meta-value"><?php echo date('M j, Y g:i A', strtotime($order['delivery_date'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Your Items Total</span>
                                <span class="meta-value">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Contact</span>
                                <span class="meta-value"><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                            </div>
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
                    if ($order['baker_category'] != 'completed')
                        continue;
                    $found_completed = true;
                ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h4><?php echo htmlspecialchars($order['customer_name']); ?></h4>
                                <p>Order #ORBKET<?php echo $order['order_id']; ?> • Completed 
                                   <?php echo time_elapsed_string($order['order_date']); ?></p>
                            </div>
                            <div>
                                <span class="order-status status-completed">Completed</span>
                                <span class="payment-status payment-<?php echo $order['payment_status']; ?>">
                                    Payment: <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="order-items-detailed">
                            <h5>Delivered Products:</h5>
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="item-row">
                                    <div>
                                        <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                        <span class="item-status status-<?php echo $item['baker_status']; ?>">
                                            <?php echo ucfirst($item['baker_status']); ?>
                                        </span>
                                        <br>Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['unit_price'], 2); ?>
                                        <br>Total: ₹<?php echo number_format($item['total_price'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Delivered</span>
                                <span class="meta-value"><?php echo date('M j, Y g:i A', strtotime($order['delivery_date'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Your Items Total</span>
                                <span class="meta-value">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Payment Status</span>
                                <span class="meta-value"><?php echo ucfirst($order['payment_status']); ?></span>
                            </div>
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
    </script>
</body>

</html>