<?php
session_start();
include 'db.php';
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}
// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

$customer_id = $_SESSION['user_id'];

date_default_timezone_set('Asia/Kolkata');

// Handle bill generation
if (isset($_GET['generate_bill']) && isset($_GET['order_id'])) {
    $bill_order_id = intval($_GET['order_id']);

    // Fetch order details for bill
    $bill_query = "
        SELECT o.order_id, o.customer_id, o.baker_id, o.order_date, o.total_amount,
               o.payment_status, o.order_status, o.delivery_date, o.delivery_address,
               oi.order_item_id, oi.product_id, oi.quantity, oi.price,
               p.name as product_name, p.image,
               b.brand_name, u.full_name as baker_name, u.email as baker_email,
               c.full_name as customer_name, c.email as customer_email
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN products p ON oi.product_id = p.product_id
        JOIN bakers b ON o.baker_id = b.baker_id
        JOIN users u ON b.user_id = u.user_id
        JOIN users c ON o.customer_id = c.user_id
        WHERE o.order_id = ? AND o.customer_id = ? AND o.payment_status = 'success'
    ";

    $bill_stmt = $conn->prepare($bill_query);
    $bill_stmt->bind_param("ii", $bill_order_id, $customer_id);
    $bill_stmt->execute();
    $bill_result = $bill_stmt->get_result();

    $bill_order = null;
    $bill_items = [];

    while ($row = $bill_result->fetch_assoc()) {
        if (!$bill_order) {
            $bill_order = [
                'order_id' => $row['order_id'],
                'order_date' => $row['order_date'],
                'total_amount' => $row['total_amount'],
                'delivery_address' => $row['delivery_address'],
                'delivery_date' => $row['delivery_date'],
                'baker_name' => $row['baker_name'],
                'brand_name' => $row['brand_name'],
                'baker_email' => $row['baker_email'],
                'customer_name' => $row['customer_name'],
                'customer_email' => $row['customer_email']
            ];
        }

        $bill_items[] = [
            'product_name' => $row['product_name'],
            'quantity' => $row['quantity'],
            'price' => $row['price'],
            'total' => $row['quantity'] * $row['price']
        ];
    }

    if ($bill_order) {
        // Generate bill HTML
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Bill - Order #BJ" . str_pad($bill_order['order_id'], 6, '0', STR_PAD_LEFT) . "</title>
    <style>
        @media print { 
            .no-print { display: none !important; } 
            body { margin: 0; font-family: 'Segoe UI', Roboto, sans-serif; }
        }
        
        body { margin: 0; font-family: 'Segoe UI', Roboto, sans-serif; line-height: 1.6;}
        
        .bill-container { 
            max-width: 800px; margin: 40px auto; padding: 20px 50px; 
            border: 1px solid #ddd; background: white;
        }
        
        .bill-header { text-align: center; margin-bottom: 30px; }
        
        .bill-title { font-family: 'Puanto', Roboto, sans-serif; font-size: 24px; font-weight: bold; color: #333; }
        
        .bill-info { display: flex; justify-content: space-between; margin: 20px 0; }
        
        .bill-section { margin: 15px 0; }
        
        .bill-section h3 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 15px; }

        .bill-section p { padding-bottom: 15px; }
        
        .bill-table { width: 100%; border-collapse: collapse; margin: 15px 0; border: 1.5px solid #f59e0b; }
        
        .bill-table th, .bill-table td { 
            border: 1px solid #ddd; padding: 10px; text-align: left; 
        }
        
        .bill-table th { background-color: #fef7cd; font-weight: bold; }
        
        .total-row { background-color: #fef7cd; font-weight: bold; font-size: 20px; }
        
        .btn { 
            padding: 10px 20px; margin: 10px 5px; cursor: pointer; 
            border: none; border-radius: 50px; text-decoration: none; display: inline-block;
            font-family: 'Segoe UI', Roboto, sans-serif; font-weight: 500; font-size: 15px;
        }
        
        .btn-primary { background: #f59e0b; color: white; }
        
        .btn-secondary { background: #6c757d; color: white; }
    </style>
</head>
<body>
    <div class='bill-container'>
        <div class='bill-header'>
            <h1 class='bill-title'><img src='media/Logo.png' alt='BakeJourney Logo' style='height: 40px; weight: 40px; vertical-align: text-top;'>
                BakeJourney</h1>
            <h2>INVOICE</h2>
        </div>
        
        <div class='bill-info'>
            <div>
                <h3 style='border-bottom: 2px solid #eee; padding-bottom: 5px;'>Bill To:</h3>
                <p><strong>" . htmlspecialchars($bill_order['customer_name']) . "</strong><br>
                Email: " . htmlspecialchars($bill_order['customer_email']) . "<br>
                Address: " . htmlspecialchars($bill_order['delivery_address']) . "</p>
            </div>
            <div>
                <h3 style='border-bottom: 2px solid #eee; padding-bottom: 5px;'>From:</h3>
                <p><strong>" . htmlspecialchars($bill_order['brand_name']) . "</strong><br>
                Baker: " . htmlspecialchars($bill_order['baker_name']) . "<br>
                Email: " . htmlspecialchars($bill_order['baker_email']) . "</p>
            </div>
        </div>
        
        <div class='bill-section'>
            <h3>Order Details</h3>
            <p><strong>Order ID:</strong> #BJ" . str_pad($bill_order['order_id'], 6, '0', STR_PAD_LEFT) . "<br>
            <strong>Order Date:</strong> " . date('d M Y, h:i A', strtotime($bill_order['order_date'])) . "<br>
            <strong>Delivery Date:</strong> " . date('d M Y, h:i A', strtotime($bill_order['delivery_date'])) . "<br>
            <strong>Status:</strong> Payment Successful</p>
        </div>
        
        <div class='bill-section'>
            <h3>Items Ordered</h3>
            <table class='bill-table'>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>";

        $subtotal = 0;
        foreach ($bill_items as $item) {
            $subtotal += $item['total'];
            echo "<tr>
                <td>" . htmlspecialchars($item['product_name']) . "</td>
                <td>" . $item['quantity'] . "</td>
                <td>₹" . number_format($item['price'], 2) . "</td>
                <td>₹" . number_format($item['total'], 2) . "</td>
            </tr>";
        }

        echo "    <tr class='total-row'>
                        <td colspan='3'>Total Amount</td>
                        <td>₹" . number_format($bill_order['total_amount'], 2) . "</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class='bill-section'>
            <p><strong>Payment Status:</strong> <span style='color: green;'>Paid Successfully</span></p>
            <p><strong>Generated on:</strong> " . date('d M Y, h:i A') . "</p>
        </div>
        
        <div class='no-print' style='text-align: center; margin-top: 30px;'>
            <button onclick='window.print()' class='btn btn-primary'>Print Bill</button>
            <button onclick='window.location.href=\"customerorders.php\"' class='btn btn-secondary'>← Back to Orders</button>
        </div>
    </div>
    
    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>";
        exit;
    } else {
        echo "<script>alert('Bill not found or payment not successful'); window.location.href='customerorders.php';</script>";
        exit;
    }
}



// Handle order actions (cancel, etc.)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $order_id = intval($_POST['order_id']);
    $action = $_POST['action'];

    if ($action === 'cancel') {
        // Cancel order if within 24 hours and not paid
        $stmt = $conn->prepare("SELECT order_date, payment_status FROM orders WHERE order_id = ? AND customer_id = ?");
        $stmt->bind_param("ii", $order_id, $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($order_data = $result->fetch_assoc()) {
            $order_time = strtotime($order_data['order_date']);
            $within_24hrs = (time() - $order_time) < 86400;

            if ($within_24hrs && $order_data['payment_status'] !== 'success') {
                $stmt = $conn->prepare("UPDATE orders SET order_status = 'cancelled',payment_status='cancelled' WHERE order_id = ? AND customer_id = ?");
                $stmt->bind_param("ii", $order_id, $customer_id);
                $stmt->execute();

                echo "<script>alert('Order cancelled successfully'); window.location.href='customerorders.php';</script>";
                exit;
            }
        }
    }
}


// Fetch orders with items - FIXED FOR YOUR TABLE STRUCTURE
$query = "
    SELECT o.order_id, o.customer_id, o.baker_id, o.order_date, o.total_amount,
           o.payment_status, o.order_status, o.delivery_date,
           oi.order_item_id, oi.product_id, oi.quantity, oi.price,
           p.name as product_name, p.image,
           b.brand_name, u.full_name as baker_name,
           o.delivery_address
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    JOIN bakers b ON o.baker_id = b.baker_id
    JOIN users u ON b.user_id = u.user_id
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
            'total_amount' => $row['total_amount'],
            'baker_name' => $row['baker_name'],
            'brand_name' => $row['brand_name'],
            'baker_id' => $row['baker_id'],
            'items' => []
        ];
        $total_orders++;

        // Count active/completed orders
        if (in_array($row['order_status'], ['pending', 'accepted', 'shipped'])) {
            $active_orders++;
        } elseif ($row['order_status'] === 'delivered') {
            $completed_orders++;
        }

        // Add to total spent if payment is successful
        if ($row['payment_status'] === 'success') {
            $total_spent += $row['total_amount'];
        }
    }

    $orders[$order_id]['items'][] = [
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'image' => $row['image'],
        'total' => $row['quantity'] * $row['price']
    ];
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
        <p class="page-subtitle">Track your orders and manage your purchases</p>

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
                    <button class="filter-tab active" onclick="showTab('all')">All</button>
                    <button class="filter-tab" onclick="showTab('active')">Active</button>
                    <button class="filter-tab" onclick="showTab('completed')">Completed</button>
                    <button class="filter-tab" onclick="showTab('cancelled')">Cancelled</button>
                </div>
            </div>

            <?php if (empty($orders)): ?>
                <div class="no-orders">
                    <p>You have no orders yet. Start shopping now!</p>
                    <a href="products.php" class="btn btn-primary">Shop Now</a>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <div class="order-number">Order #BJ<?= str_pad($order['order_id'], 6, '0', STR_PAD_LEFT) ?>
                                </div>
                                <div class="order-date">Placed on <?= date('d M Y, h:i A', strtotime($order['order_date'])); ?>
                                </div>
                                <div class="baker-info">
                                    <strong>Made by:&nbsp;</strong>
                                    <?= htmlspecialchars($order['brand_name'] ?: $order['baker_name']) ?>
                                </div>
                            </div>
                            <span class="order-status status-<?= $order['order_status']; ?>">
                                <?= ucfirst($order['order_status']); ?>
                            </span>
                        </div>

                        <div class="order-details">
                            <div class="items-list">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="order-item">
                                        <div class="item-image">
                                            <img src="<?= $item['image'] ? 'uploads/' . $item['image'] : 'media/placeholder.jpg' ?>"
                                                alt="<?= htmlspecialchars($item['product_name']) ?>"
                                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        </div>
                                        <div class="item-info">
                                            <div class="item-name"><?= htmlspecialchars($item['product_name']); ?></div>
                                            <div class="item-details">
                                                Quantity: <?= $item['quantity']; ?> × ₹<?= number_format($item['price'], 2); ?>
                                            </div>
                                        </div>
                                        <div class="item-price">
                                            <div>₹<?= number_format($item['total'], 2); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Order Summary -->
                            <div class="pricing-info">
                                <div class="price-breakdown payable-amount">
                                    <span>
                                        <p style="font-weight: 700;">Order Total:</p>
                                    </span>
                                    <span>
                                        <p style="font-weight: 700;">₹<?= number_format($order['total_amount'], 2) ?></p>
                                    </span>
                                </div>

                                <div class="delivery-info">
                                    <div><strong>Delivery Address:</strong>
                                        <?= htmlspecialchars($order['delivery_address'] ?? 'Not specified') ?></div>
                                    <div><strong>Expected Delivery:</strong>
                                        <?= date('d M Y, h:i A', strtotime($order['delivery_date'])) ?></div>
                                </div>
                            </div>

                            <!-- Order Actions -->
                            <div class="order-actions">
                                <?php
                                $order_time = strtotime($order['order_date']);
                                $within_24hrs = (time() - $order_time) < 86400;
                                $status = $order['order_status'];
                                $paid = $order['payment_status'] === 'success';
                                ?>

                                <!-- Payment Status Display -->
                                <?php if ($paid): ?>
                                    <span style="color: #28a745; font-weight: bold; padding: 8px 16px; border-radius: 4px;">
                                        ✓ Payment Successful
                                    </span>
                                <?php elseif ($order['payment_status'] === 'failed'): ?>
                                    <span style="color: #dc3545; font-weight: bold; padding: 8px 16px; border-radius: 4px;">
                                        ✕ Payment Failed
                                    </span>
                                <?php elseif ($order['payment_status'] === 'pending'): ?>
                                    <span style="color: #d97706; font-weight: bold; padding: 8px 16px; border-radius: 4px;">
                                        ◉ Payment Pending
                                    </span>
                                <?php else: ?>
                                <?php endif; ?>

                                <!-- Generate Bill button for successful payments -->
                                <?php if ($paid): ?>
                                    <a href="?generate_bill=1&order_id=<?= $order['order_id'] ?>" class="btn btn-primary"
                                        target="_blank">
                                        Generate Bill
                                    </a>
                                <?php endif; ?>

                                <!-- Pay Now button for accepted orders that are not paid -->
                                <?php if ($status === 'accepted' && !$paid): ?>
                                    <form method="POST" action="payments.php" style="display: inline; margin-right: 8px;">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                        <input type="hidden" name="amount" value="<?= $order['total_amount'] ?>">
                                        <input type="hidden" name="baker_id" value="<?= $order['baker_id'] ?>">
                                        <input type="hidden" name="customer_id" value="<?= $customer_id ?>">
                                        <button type="submit" name="action" value="pay" class="btn btn-primary">
                                            Pay ₹<?= number_format($order['total_amount'], 2) ?> Now
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- Cancel button if within 24 hours and not paid -->
                                <?php if (!$paid && $within_24hrs && !in_array($status, ['shipped', 'delivered', 'cancelled'])): ?>
                                    <form method="POST" action="customerorders.php">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                        <button type="submit" name="action" value="cancel" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to cancel this order?')">
                                            Cancel Order
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- Track Order button if paid and order is confirmed -->
                                <?php if ($paid && in_array($status, ['accepted', 'shipped', 'delivered'])): ?>
                                    <a href="?order_id=<?= $order['order_id'] ?>" class="btn btn-primary"
                                        onclick="event.preventDefault(); openTrackingSidebar(); loadOrderTracking(<?= $order['order_id'] ?>)">
                                        Track Order
                                    </a>
                                <?php endif; ?>

                                <!-- Contact Baker -->
                                <a href="mailto:baker@example.com" class="btn btn-secondary">
                                    Contact Baker
                                </a>
                            </div>

                            <!-- Special message for pending orders -->
                            <?php if ($status === 'pending'): ?>
                                <div
                                    style="margin-top: 20px; padding: 12px; background: #ffffff; border-radius: 50px; border: 2px solid #fcd34d; font-size: 14px; text-align: center;">
                                    <strong>⏳ Waiting for baker confirmation</strong><br>
                                    <small>You can pay once the baker accepts your order</small>
                                </div>
                            <?php endif; ?>

                            <!-- Special message for accepted unpaid orders -->
                            <?php if ($status === 'accepted' && !$paid): ?>
                                <div
                                    style="margin-top: 20px; padding: 12px; background: #ffffff; border-radius: 50px; border: 2px solid #fcd34d; font-size: 14px; text-align: center;">
                                    <strong>🎉 Order Confirmed by Baker!</strong><br>
                                    <small>Please complete payment to proceed with delivery</small>
                                </div>
                            <?php endif; ?>
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
                    case 'all':
                        order.style.display = 'block';
                        break;
                    case 'active':
                        order.style.display = ['pending', 'accepted', 'shipped'].includes(status) ? 'block' : 'none';
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