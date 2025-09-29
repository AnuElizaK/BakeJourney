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
    $check_stmt = $conn->prepare("SELECT order_id FROM orders WHERE order_id = ? AND baker_id = ? LIMIT 1");
    $check_stmt->bind_param("ii", $order_id, $baker_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $update_stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ? AND baker_id = ?");
        $update_stmt->bind_param("sii", $new_status, $order_id, $baker_id);
        
        if ($update_stmt->execute()) {
            $message = '';
            switch($new_status) {
                case 'accepted':
                    $message = 'Order accepted successfully!';
                    break;
                case 'shipped':
                    $message = 'Order marked as shipped!';
                    break;
                case 'delivered':
                    $message = 'Order marked as delivered!';
                    break;
                case 'cancelled':
                    $message = 'Order cancelled!';
                    break;
                default:
                    $message = 'Order updated successfully!';
            }
            header("Location: bakerordermngmt.php?msg=" . urlencode($message) . "#lastcard");
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

// Get all orders for this baker
$orders_query = "
    SELECT o.order_id, o.customer_id, o.order_date, o.total_amount,
           o.payment_status, o.order_status, o.delivery_date,
           u.full_name as customer_name, u.phone as customer_phone, 
           u.email as customer_email,
           o.delivery_address
    FROM orders o
    JOIN users u ON o.customer_id = u.user_id
    WHERE o.baker_id = ?
    ORDER BY o.order_date DESC
";

$stmt = $conn->prepare($orders_query);
$stmt->bind_param("i", $baker_id);
$stmt->execute();
$orders_result = $stmt->get_result();

// Function to get order items for a specific order
function getOrderItems($conn, $order_id) {
    $stmt = $conn->prepare("
        SELECT oi.order_item_id, oi.product_id, oi.quantity, oi.price,
               p.name as product_name, p.image
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.product_id 
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Function to format time elapsed
function time_elapsed_string($datetime) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->d > 0) return $diff->d . " day(s) ago";
    if ($diff->h > 0) return $diff->h . " hour(s) ago";
    if ($diff->i > 0) return $diff->i . " minute(s) ago";
    return "Just now";
}

// Process orders and count stats
$pending_count = $accepted_count = $completed_count = $total_count = 0;
$orders_array = [];

while ($order = $orders_result->fetch_assoc()) {
    // Get items for this order
    $items_result = getOrderItems($conn, $order['order_id']);
    $items = [];
    
    while ($item = $items_result->fetch_assoc()) {
        $item['total'] = $item['quantity'] * $item['price'];
        $items[] = $item;
    }
    
    $order['items'] = $items;
    $orders_array[] = $order;
    $total_count++;
    
    // Count by status
    switch($order['order_status']) {
        case 'pending':
            $pending_count++;
            break;
        case 'accepted':
        case 'shipped':
            $accepted_count++;
            break;
        case 'delivered':
            $completed_count++;
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management | BakeJourney</title>
    <link rel="stylesheet" href="bakerordermngmt.css">
</head>
<?php include 'bakernavbar.php'; ?>
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
                <h3>Pending</h3>
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
            <div class="stat-card" id="lastcard">
                <h3>Total Orders</h3>
                <div class="number"><?php echo $total_count; ?></div>
            </div>
        </div>

 <!-- Success/Error Messages -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="success"><?php echo htmlspecialchars($_GET['msg']); ?>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_GET['error']); ?>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="showTab('pending')">Pending Requests</button>
            <button class="tab" onclick="showTab('ongoing')">Ongoing Orders</button>
            <button class="tab" onclick="showTab('delivered')">Past Orders</button>
        </div>

        <!-- Pending Orders -->
        <div id="pending" class="orders-section active">
            <div class="orders-grid">
                <?php
                $found_pending = false;
                foreach ($orders_array as $order):
                    if ($order['order_status'] != 'pending') continue;
                    $found_pending = true;
                ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h4><?php echo htmlspecialchars($order['customer_name']); ?></h4>
                                <p>Order #BJ<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?> • 
                                   <?php echo time_elapsed_string($order['order_date']); ?></p>
                            </div>
                            <div>
                                <span class="order-status status-pending">New Request</span>
                                <span class="payment-status payment-<?php echo $order['payment_status']; ?>">
                                    Payment <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="order-items-detailed">
                            <h5>Items in this Order:</h5>
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="item-row">
                                    <div class="item-image">
                                        <img src="<?= $item['image'] ? 'uploads/' . $item['image'] : 'media/placeholder.jpg' ?>" 
                                             alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    </div>
                                    <div class="item-details">
                                        <strong style="font-family: 'Puanto', Roboto, sans-serif;"><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                        <br>Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?>
                                        <br>Total: ₹<?php echo number_format($item['total'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-actions" style="margin: 16px 0; text-align: center;">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <input type="hidden" name="order_status" value="accepted">
                                <button type="submit" class="btn btn-success btn-sm" 
                                        onclick="return confirm('Accept this entire order?')">
                                    Accept
                                </button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <input type="hidden" name="order_status" value="cancelled">
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Reject this order? This cannot be undone.')">
                                    Reject
                                </button>
                            </form>
                        </div>

                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Delivery Date</span>
                                <span class="meta-value"><?php echo date('M j, Y g:i A', strtotime($order['delivery_date'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Order Total</span>
                                <span class="meta-value">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Delivery Address</span>
                                <span class="meta-value"><?php echo htmlspecialchars($order['delivery_address'] ?? 'Not specified'); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Contact</span>
                                <span class="meta-value">
                                    <?php echo htmlspecialchars($order['customer_phone']); ?>
                                    <br><small><?php echo htmlspecialchars($order['customer_email']); ?></small>
                                </span>
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
                    if (!in_array($order['order_status'], ['accepted', 'shipped'])) continue;
                    $found_ongoing = true;
                ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h4><?php echo htmlspecialchars($order['customer_name']); ?></h4>
                                <p>Order #BJ<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?> • 
                                   <?php echo time_elapsed_string($order['order_date']); ?></p>
                            </div>
                            <div>
                                <span class="order-status status-accepted">
                                    <?= $order['order_status'] === 'shipped' ? 'Shipped' : 'In Progress' ?>
                                </span>
                                <span class="payment-status payment-<?php echo $order['payment_status']; ?>">
                                    Payment <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="order-items-detailed">
                            <h5>Order Items:</h5>
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="item-row">
                                    <div class="item-image">
                                        <img src="<?= $item['image'] ? 'uploads/' . $item['image'] : 'media/placeholder.jpg' ?>" 
                                             alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    </div>
                                    <div class="item-details">
                                        <strong style="font-family: 'Puanto', Roboto, sans-serif;"><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                        <span class="item-status status-accepted">Accepted</span>
                                        <br>Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?>
                                        <br>Total: ₹<?php echo number_format($item['total'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-actions" style="margin-top: 16px; text-align: center;">
                            <?php if ($order['order_status'] === 'accepted'): ?>
                                <form method="POST" style="display:inline; margin-right: 8px;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <input type="hidden" name="order_status" value="shipped">
                                    <button type="submit" class="btn btn-primary btn-sm"
                                            onclick="return confirm('Mark this order as shipped?')">
                                        Mark as Shipped
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <?php if ($order['order_status'] === 'shipped'): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <input type="hidden" name="order_status" value="delivered">
                                    <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Mark this order as delivered?')">
                                        Mark as Delivered
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>

                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Delivery Date</span>
                                <span class="meta-value"><?php echo date('M j, Y g:i A', strtotime($order['delivery_date'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Order Total</span>
                                <span class="meta-value">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Contact</span>
                                <span class="meta-value">
                                    <?php echo htmlspecialchars($order['customer_phone']); ?>
                                    <br><small><?php echo htmlspecialchars($order['customer_email']); ?></small>
                                </span>
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
        <div id="delivered" class="orders-section">
            <div class="orders-grid">
                <?php
                $found_completed = false;
                foreach ($orders_array as $order):
                    if ($order['order_status'] != 'delivered' && $order['order_status'] != 'cancelled') continue;

                    $found_completed = true;
                ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h4><?php echo htmlspecialchars($order['customer_name']); ?></h4>
                                <p>Order #BJ<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?> • Completed 
                                   <?php echo time_elapsed_string($order['order_date']); ?></p>
                            </div>
                            <div>
                                <span class="order-status status-<?php echo $order['order_status']; ?>"><?php echo $order['order_status']; ?></span>
                                <span class="payment-status payment-<?php echo $order['payment_status']; ?>">
                                    Payment <?php echo ucfirst($order['payment_status']); ?>
                                </span>
                            </div>
                        </div>

                        <div class="order-items-detailed">
                            <h5>Delivered Items:</h5>
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="item-row">
                                    <div class="item-image">
                                        <img src="<?= $item['image'] ? 'uploads/' . $item['image'] : 'media/placeholder.jpg' ?>" 
                                             alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    </div>
                                    <div class="item-details">
                                        <strong style="font-family: 'Puanto', Roboto, sans-serif;"><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                        <span class="item-status status-<?php echo strtoupper($order['order_status']); ?>"><?php echo $order['order_status']; ?></span>
                                        <br>Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?>
                                        <br>Total: ₹<?php echo number_format($item['total'], 2); ?>
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
                                <span class="meta-label">Order Total</span>
                                <span class="meta-value">₹<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Payment Status</span>
                                <span class="meta-value"><?php echo ucfirst($order['payment_status']); ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Customer</span>
                                <span class="meta-value">
                                    <?php echo htmlspecialchars($order['customer_phone']); ?>
                                    <br><small><?php echo htmlspecialchars($order['customer_email']); ?></small>
                                </span>
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

    <?php include 'globalfooter.php'; ?>

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