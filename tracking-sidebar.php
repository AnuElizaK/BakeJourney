<style>
    h1,
    h2,
    h3 {
        font-family: 'Puanto', Roboto, sans-serif;
    }

    body {
        font-family: 'Segoe UI', Roboto, sans-serif;
        color: #1f2a38;
    }

    /* Sidebar Overlay */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        right: -450px;
        width: 450px;
        height: 100vh;
        background: white;
        box-shadow: -5px 0 25px rgba(0, 0, 0, 0.15);
        z-index: 1001;
        transition: right 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        overflow-y: auto;
    }

    .sidebar.active {
        right: 0;
    }

    .sidebar-header {
        padding: 25px;
        border-bottom: 1.5px solid #fcd34d;
        background: linear-gradient(135deg, #fee996 0%, #fef7cd 100%);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .sidebar-title {
        font-size: 24px;
        font-family: 'Puanto', Roboto, sans-serif;
        color: #1f2a38;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .close-btn {
        position: absolute;
        top: 5px;
        right: 1px;
        background: none;
        border: none;
        color: #f59e0b;
        cursor: pointer;
        font-size: 24px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .close-btn:hover {
        background: #fcd34d5b;
        transform: rotate(90deg);
    }

    .sidebar-subtitle {
        color: #484c54;
        font-size: 14px;
    }

    .sidebar-content {
        padding: 25px;
    }

    /* Order tracking steps */
    .tracking-steps {
        margin-bottom: 30px;
    }

    .step {
        display: flex;
        align-items: flex-start;
        margin-bottom: 25px;
        position: relative;
    }

    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 20px;
        top: 45px;
        width: 2px;
        height: 40px;
        background: #e0e0e0;
    }

    .step.completed::after {
        background: #f59e0b;
    }

    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 16px;
        font-weight: bold;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    .step.completed .step-icon {
        background: linear-gradient(135deg, #fcd34d 40%, #f59e0b);
        color: white;
    }

    .step.current .step-icon {
        background: #fef7cd;
        color: #f59e0b;
        border: 3px solid #f59e0b;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(233, 143, 25, 0.87);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(102, 126, 234, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
        }
    }

    .step.pending .step-icon {
        background: #f8f9fa;
        color: #6c757d;
        border: 2px solid #e0e0e0;
    }

    .step-content {
        flex: 1;
        padding-top: 8px;
    }

    .step-title {
        font-weight: 500;
        color: #f59e0b;
        margin-bottom: 5px;
    }

    .step-description {
        color: #1f2a38;
        font-size: 14px;
        line-height: 1.4;
    }

    .step-time {
        color: #999;
        font-size: 12px;
        margin-top: 5px;
    }

    /* Order details in sidebar */
    .sidebar-order-details {
        background: #f3f4f6;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #c0c3c7ff;
    }

    .detail-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .detail-label {
        color: #484c54;
        font-weight: 500;
    }

    .detail-value {
        color: #f59e0b;
        font-weight: 600;
    }

    .delivery-info-section {
        padding: 20px;
        border-radius: 15px;
        background: #f3f4f6;
        margin-bottom: 25px;
    }

    .delivery-details {
        border-top: 1px solid #c0c3c7ff;
        margin-top: 10px;
        padding-top: 10px;
    }

    /* Contact baker section */
    .contact-baker {
        background: #fef7cd;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
    }

    .contact-baker h3 {
        color: #f59e0b;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .contact-baker p {
        color: #484c54;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .contact-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-contact-sidebar {
        padding: 5px 10px;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .baker-name p {
        text-align: center;
        font-weight: 500;
        font-size: 18px;
        margin-top: 10px;
        color: #f59e0b;
    }

    .btn-message {
        background: linear-gradient(135deg, #fcd34d, #f59e0b);
        color: white;
    }

    .btn-call {
        background: #ffffff;
        color: #f59e0b;
    }

    .btn-contact-sidebar:hover {
        color: white;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .sidebar {
            width: 85%;
            right: -100%;
        }

        .sidebar-title {
            font-size: 1.1rem;
        }

        .close-btn {
            font-size: 20px;
        }

        .sidebar-subtitle {
            color: #484c54;
            font-size: 12px;
        }

        .step-icon {
            font-size: 14px;
        }

        .step-content {
            flex: 1;
            padding-top: 8px;
        }

        .step-title {
            font-size: 14px;
        }

        .step-description {
            font-size: 12px;
        }

        .step-time {
            font-size: 10px;
        }

        /* Order details in sidebar */
        .sidebar-order-details {
            padding: 10px;
        }

        .detail-row {
            margin-bottom: 8px;
            padding-bottom: 8px;
        }

        .detail-label {
            font-size: 12px;
        }

        .detail-value {
            font-size: 12px;
        }

        /* Contact baker section */
        .contact-baker {
            background: #fef7cd;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
        }

        .contact-baker h3 {
            font-size: 16px;
        }

        .contact-baker p {
            font-size: 12px;
        }

        .btn-contact-sidebar {
            padding: 5px 15px;
            font-weight: 500;
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .sidebar-content {
            padding: 15px;
        }

        .sidebar-header {
            padding: 15px;
        }
    }
</style>

<?php
// Function to render tracking sidebar with real database data
function renderTrackingSidebar($orderData = null)
{
    // Get order details if order_id is passed
    if (isset($_GET['order_id']) && !$orderData) {
        include 'db.php';
        $order_id = $_GET['order_id'];
        $customer_id = $_SESSION['user_id'];

        // Fetch order details with customer information
        $query = "
            SELECT DISTINCT
                o.*,
                u.full_name as customer_name,
                u.phone as customer_phone,
                u.email as customer_email
            FROM orders o
            JOIN users u ON o.customer_id = u.user_id
            WHERE o.order_id = ? AND o.customer_id = ?
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $order_id, $customer_id);
        $stmt->execute();
        $order_result = $stmt->get_result();
        $order_info = $order_result->fetch_assoc();

        if (!$order_info) {
            return; // Order not found or unauthorized
        }

        // Fetch order items with baker info - FIXED FOR YOUR TABLE STRUCTURE
        $items_query = "
            SELECT 
                oi.*,
                p.name as product_name,
                p.image,
                b.brand_name,
                b.baker_id,
                bu.phone as baker_phone,
                bu.full_name as baker_name,
                bu.email as baker_email
            FROM order_items oi
            JOIN products p ON oi.product_id = p.product_id
            JOIN bakers b ON p.baker_id = b.baker_id
            JOIN users bu ON b.user_id = bu.user_id
            WHERE oi.order_id = ?
            ORDER BY b.brand_name
        ";

        $stmt = $conn->prepare($items_query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $items_result = $stmt->get_result();

        $order_items = [];
        $total_amount = $order_info['total_amount']; // Use the total from orders table
        $bakers_info = [];

        while ($item = $items_result->fetch_assoc()) {
            $order_items[] = $item;

            // Collect unique bakers
            if (!isset($bakers_info[$item['baker_id']])) {
                $bakers_info[$item['baker_id']] = [
                    'baker_name' => $item['baker_name'],
                    'brand_name' => $item['brand_name'],
                    'baker_phone' => $item['baker_phone'],
                    'baker_email' => $item['baker_email']
                ];
            }
        }

        $orderData = [
            'order_info' => $order_info,
            'items' => $order_items,
            'bakers' => $bakers_info,
            'total_amount' => $total_amount
        ];
    }

    // Generate tracking steps based on order status
    function generateTrackingSteps($order_status, $order_date, $delivery_date, $payment_status)
    {
        $steps = [];

        // Step 1: Order Placed
        $steps[] = [
            'status' => 'completed',
            'title' => 'Order Placed',
            'description' => 'Your order has been successfully placed',
            'time' => date('M j, Y - g:i A', strtotime($order_date)),
            'icon' => '📋'
        ];

        // Step 2: Baker Confirmation
        $steps[] = [
            'status' => ($order_status == 'pending') ? 'current' : 'completed',
            'title' => 'Baker Confirmation',
            'description' => ($order_status == 'pending') ? 'Waiting for baker to accept your order' : 'Baker has accepted your order',
            'time' => ($order_status == 'pending') ? 'Pending' : 'Confirmed',
            'icon' => ($order_status == 'pending') ? '⏳' : '✅'
        ];

        // Step 3: Payment
        if ($payment_status == 'success') {
            $steps[] = [
                'status' => 'completed',
                'title' => 'Payment Confirmed',
                'description' => 'Payment has been processed successfully',
                'time' => 'Completed',
                'icon' => '💳'
            ];
        } else if ($order_status == 'accepted') {
            $steps[] = [
                'status' => 'current',
                'title' => 'Awaiting Payment',
                'description' => 'Order accepted! Complete payment to proceed',
                'time' => 'Action Required',
                'icon' => '💰'
            ];
        } else {
            $steps[] = [
                'status' => 'pending',
                'title' => 'Payment Pending',
                'description' => 'Payment will be processed after baker confirmation',
                'time' => 'Pending',
                'icon' => '⏳'
            ];
        }

        // Step 4: Preparing/Baking (only if paid)
        if ($payment_status == 'success') {
            $steps[] = [
                'status' => ($order_status == 'accepted') ? 'current' : (in_array($order_status, ['shipped', 'delivered']) ? 'completed' : 'pending'),
                'title' => 'Preparing Your Order',
                'description' => 'Your delicious baked goods are being prepared with care',
                'time' => ($order_status == 'accepted') ? 'In Progress' : (in_array($order_status, ['shipped', 'delivered']) ? 'Completed' : 'Pending'),
                'icon' => ($order_status == 'accepted') ? '👩‍🍳' : (in_array($order_status, ['shipped', 'delivered']) ? '✅' : '⏳')
            ];

            // Step 5: Ready/Shipped
            $steps[] = [
                'status' => ($order_status == 'shipped') ? 'current' : ($order_status == 'delivered' ? 'completed' : 'pending'),
                'title' => 'Ready for Delivery',
                'description' => 'Your order is packaged and ready for delivery',
                'time' => ($order_status == 'shipped') ? 'Ready Now' : ($order_status == 'delivered' ? 'Completed' : 'Expected: ' . date('M j, Y', strtotime($delivery_date))),
                'icon' => ($order_status == 'shipped') ? '📦' : ($order_status == 'delivered' ? '✅' : '📅')
            ];

            // Step 6: Delivered
            $steps[] = [
                'status' => ($order_status == 'delivered') ? 'completed' : 'pending',
                'title' => 'Order Delivered',
                'description' => ($order_status == 'delivered') ? 'Enjoy your freshly baked goods!' : 'Your order will be delivered fresh',
                'time' => ($order_status == 'delivered') ? 'Delivered' : 'Expected: ' . date('M j, Y', strtotime($delivery_date)),
                'icon' => ($order_status == 'delivered') ? '🎉' : '🚚'
            ];
        }

        return $steps;
    }
    ?>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeTrackingSidebar()"></div>

    <!-- Tracking Sidebar -->
    <div class="sidebar" id="trackingSidebar">
        <div class="sidebar-header">
            <div class="sidebar-title">
                <h3>Order Tracking</h3>
                <button class="close-btn" onclick="closeTrackingSidebar()">&times;</button>
            </div>
            <p class="sidebar-subtitle">Track your order status in real-time</p>
        </div>

        <div class="sidebar-content">
            <?php if ($orderData && $orderData['order_info']): ?>
                <!-- Order Details -->
                <div class="sidebar-order-details">
                    <div class="detail-row">
                        <span class="detail-label">Order ID</span>
                        <span
                            class="detail-value">#BJ<?php echo str_pad($orderData['order_info']['order_id'], 6, '0', STR_PAD_LEFT); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Placed</span>
                        <span
                            class="detail-value"><?php echo date('M j, Y - g:i A', strtotime($orderData['order_info']['order_date'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Expected Delivery</span>
                        <span
                            class="detail-value"><?php echo date('M j, Y - g:i A', strtotime($orderData['order_info']['delivery_date'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Total Amount</span>
                        <span class="detail-value">₹<?php echo number_format($orderData['total_amount'], 2); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Status</span>
                        <span class="detail-value payment-<?php echo $orderData['order_info']['payment_status']; ?>">
                            <?php
                            switch ($orderData['order_info']['payment_status']) {
                                case 'success':
                                    echo 'Paid';
                                    break;
                                case 'failed':
                                    echo 'Failed';
                                    break;
                                default:
                                    echo '⏳ Pending';
                                    break;
                            }
                            ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Order Status</span>
                        <span class="detail-value order-<?php echo $orderData['order_info']['order_status']; ?>">
                            <?php
                            switch ($orderData['order_info']['order_status']) {
                                case 'pending':
                                    echo 'Pending Confirmation';
                                    break;
                                case 'accepted':
                                    echo 'Confirmed';
                                    break;
                                case 'shipped':
                                    echo 'Shipped';
                                    break;
                                case 'delivered':
                                    echo 'Delivered';
                                    break;
                                case 'cancelled':
                                    echo 'Cancelled';
                                    break;
                                default:
                                    echo ucfirst($orderData['order_info']['order_status']);
                                    break;
                            }
                            ?>
                        </span>
                    </div>
                </div>

                <!-- Tracking Steps -->
                <div class="tracking-steps">
                    <h4 style="margin-bottom: 20px;">Order Progress</h4>
                    <?php
                    $steps = generateTrackingSteps(
                        $orderData['order_info']['order_status'],
                        $orderData['order_info']['order_date'],
                        $orderData['order_info']['delivery_date'],
                        $orderData['order_info']['payment_status']
                    );

                    foreach ($steps as $step):
                        ?>
                        <div class="step <?php echo $step['status']; ?>">
                            <div class="step-icon"><?php echo $step['icon']; ?></div>
                            <div class="step-content">
                                <div class="step-title"><?php echo $step['title']; ?></div>
                                <div class="step-description"><?php echo $step['description']; ?></div>
                                <div class="step-time"><?php echo $step['time']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Delivery Information -->
                <?php if (!empty($orderData['order_info']['delivery_address'])): ?>
                    <div class="delivery-info-section">
                        <h4>Delivery Information</h4>
                        <div class="delivery-details">
                            <p style="font-weight: 500; color: #484c54;">Address:<br>
                            <p><?php echo htmlspecialchars($orderData['order_info']['delivery_address']); ?></p>
                            </p>
                            <p style="font-weight: 500; color: #484c54; margin-top: 10px;">Expected Delivery:<br>
                            <p><?php echo date('l, M j, Y - g:i A', strtotime($orderData['order_info']['delivery_date'])); ?></p>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Contact Bakers -->
                <?php if (!empty($orderData['bakers'])): ?>
                    <div class="contact-section">

                        <?php foreach ($orderData['bakers'] as $baker): ?>
                            <div class="contact-baker">
                                <h4>Need Help?</h4>
                                <small style="color: #484c54">Contact your baker for any queries or assistance regarding your order.</small>
                                <div class="baker-name">
                                    <p>Connect with <?php echo htmlspecialchars($baker['baker_name']); ?></p>
                                </div>
                                <div class="contact-buttons">
                                    <?php if (!empty($baker['baker_phone'])): ?>
                                        <a href="tel:<?php echo $baker['baker_phone']; ?>" class="btn-contact-sidebar btn-call">Call</a>
                                    <?php endif; ?>
                                    <a href="#message" class="btn-contact-sidebar btn-message"
                                        onclick="openMessageModal('<?php echo htmlspecialchars($baker['baker_name']); ?>')">Message</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="no-order-selected">
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <h3>No Order Selected</h3>
                        <p>Click "Track Order" on any of your orders to view detailed tracking information.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Sidebar functionality
        function openTrackingSidebar() {
            const sidebar = document.getElementById('trackingSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar && overlay) {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeTrackingSidebar() {
            const sidebar = document.getElementById('trackingSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar && overlay) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        function openMessageModal(bakerName) {
            alert(`Messaging feature with ${bakerName} coming soon!`);
        }

        // Close sidebar with Escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeTrackingSidebar();
            }
        });
    </script>
    <?php
}
?>