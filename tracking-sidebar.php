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
        background: #f59e0b;
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
        font-weight: bold;
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
        padding: 10px 20px;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-message {
        background: #f59e0b;
        color: white;
    }

    .btn-call {
        background: white;
        color: #f59e0b;
        border: 2px solid #f59e0b;
    }

    .btn-contact-sidebar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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

    /* Sidebar Styles
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .sidebar {
        position: fixed;
        top: 0;
        right: -500px;
        width: 500px;
        height: 100vh;
        background: white;
        z-index: 9999;
        transition: right 0.3s ease;
        box-shadow: -5px 0 20px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
    }

    .sidebar.active {
        right: 0;
    }

    .sidebar-header {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        flex-shrink: 0;
    }

    .sidebar-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .sidebar-title h3 {
        margin: 0;
        font-size: 1.5rem;
    }

    .close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.3s ease;
    }

    .close-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .sidebar-subtitle {
        margin: 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    .sidebar-content {
        padding: 0;
        overflow-y: auto;
        flex: 1;
    }

    .sidebar-order-details {
        padding: 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        align-items: center;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-label {
        font-weight: 600;
        color: #666;
        font-size: 0.9rem;
    }

    .detail-value {
        font-weight: bold;
        color: #333;
    }

    .payment-success {
        color: #28a745;
    }

    .payment-failed {
        color: #dc3545;
    }

    .order-items-status {
        padding: 20px;
        border-bottom: 1px solid #dee2e6;
    }

    .order-items-status h4 {
        margin: 0 0 15px 0;
        color: #333;
        font-size: 1.1rem;
    }

    .item-status-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .item-status-row:last-child {
        border-bottom: none;
    }

    .item-info {
        flex: 1;
    }

    .item-info strong {
        color: #333;
        font-size: 0.95rem;
    }

    .item-info small {
        color: #666;
        font-size: 0.8rem;
    }

    .badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: bold;
        text-align: center;
    }

    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }

    .badge-accepted {
        background: #d4edda;
        color: #155724;
    }

    .badge-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-delivered {
        background: #d1ecf1;
        color: #0c5460;
    }

    .tracking-steps {
        padding: 20px;
        border-bottom: 1px solid #dee2e6;
    }

    .tracking-steps h4 {
        margin: 0 0 20px 0;
        color: #333;
        font-size: 1.1rem;
    }

    .step {
        display: flex;
        margin-bottom: 20px;
        position: relative;
    }

    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 20px;
        top: 45px;
        bottom: -20px;
        width: 2px;
        background: #e9ecef;
    }

    .step.completed::after {
        background: #28a745;
    }

    .step.current::after {
        background: linear-gradient(to bottom, #28a745 50%, #e9ecef 50%);
    }

    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 15px;
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }

    .step.completed .step-icon {
        background: #28a745;
        color: white;
    }

    .step.current .step-icon {
        background: #667eea;
        color: white;
        animation: pulse 2s infinite;
    }

    .step.pending .step-icon {
        background: #f8f9fa;
        color: #6c757d;
        border: 2px solid #e9ecef;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
        100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
    }

    .step-content {
        flex: 1;
    }

    .step-title {
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
        font-size: 1rem;
    }

    .step.completed .step-title {
        color: #28a745;
    }

    .step.current .step-title {
        color: #667eea;
    }

    .step-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 3px;
        line-height: 1.4;
    }

    .step-time {
        color: #999;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .step.current .step-time {
        color: #667eea;
        font-weight: 600;
    }

    .contact-baker {
        padding: 20px;
    }

    .contact-baker h4 {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 1.1rem;
    }

    .contact-baker p {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .baker-contact {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .baker-contact:last-child {
        border-bottom: none;
    }

    .baker-info strong {
        color: #333;
        font-size: 0.95rem;
    }

    .baker-info small {
        color: #666;
        font-size: 0.8rem;
    }

    .contact-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-contact-sidebar {
        padding: 8px 12px;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
    }

    .btn-message {
        background: #667eea;
        color: white;
    }

    .btn-message:hover {
        background: #5a6fd8;
        transform: translateY(-1px);
    }

    .btn-call {
        background: #28a745;
        color: white;
    }

    .btn-call:hover {
        background: #218838;
        transform: translateY(-1px);
    }

    .no-order-selected {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .empty-state {
        text-align: center;
        color: #666;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .empty-state h3 {
        margin: 0 0 10px 0;
        color: #333;
    }

    .empty-state p {
        margin: 0;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    /* Mobile Responsiveness */
    /* @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            right: -100%;
        }
        
        .sidebar-content {
            padding: 10px;
        }
        
        .sidebar-order-details {
            padding: 15px;
        }
        
        .tracking-steps {
            padding: 15px;
        }
        
        .contact-baker {
            padding: 15px;
        } */
    /* } */
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

        // Fetch order details with baker information
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

        // Fetch order items with baker info
        $items_query = "
            SELECT 
                oi.*,
                p.name as product_name,
                p.image,
                b.brand_name,
                b.baker_id,
                bu.phone as baker_phone,
                bu.full_name as baker_name
            FROM order_items oi
            JOIN products p ON oi.product_id = p.product_id
            JOIN bakers b ON p.baker_id = b.baker_id
            JOIN users bu ON b.user_id = bu.user_id
            WHERE oi.order_id = ?
            ORDER BY oi.baker_status DESC, b.brand_name
        ";

        $stmt = $conn->prepare($items_query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $items_result = $stmt->get_result();

        $order_items = [];
        $total_accepted = 0;
        $bakers_info = [];

        while ($item = $items_result->fetch_assoc()) {
            $order_items[] = $item;

            if ($item['baker_status'] == 'accepted') {
                $total_accepted += $item['total_price'];
            }

            // Collect unique bakers
            if (!isset($bakers_info[$item['baker_id']])) {
                $bakers_info[$item['baker_id']] = [
                    'baker_name' => $item['baker_name'],
                    'brand_name' => $item['brand_name'],
                    'baker_phone' => $item['baker_phone']
                ];
            }
        }

        $orderData = [
            'order_info' => $order_info,
            'items' => $order_items,
            'bakers' => $bakers_info,
            'total_accepted' => $total_accepted
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

        // Step 2: Payment
        if ($payment_status == 'success') {
            $steps[] = [
                'status' => 'completed',
                'title' => 'Payment Confirmed',
                'description' => 'Payment has been processed successfully',
                'time' => 'Completed',
                'icon' => '💳'
            ];
        } else {
            $steps[] = [
                'status' => 'pending',
                'title' => 'Awaiting Payment',
                'description' => 'Complete payment to proceed with your order',
                'time' => 'Pending',
                'icon' => '⏳'
            ];
        }

        // Step 3: Confirmed
        $steps[] = [
            'status' => ($order_status == 'pending') ? 'pending' : 'completed',
            'title' => 'Order Confirmed',
            'description' => ($order_status == 'pending') ? 'Waiting for baker confirmation' : 'Bakers have confirmed your order',
            'time' => ($order_status == 'pending') ? 'Pending' : 'Confirmed',
            'icon' => ($order_status == 'pending') ? '⏳' : '✅'
        ];

        // Step 4: Preparing/Baking
        $steps[] = [
            'status' => ($order_status == 'confirmed') ? 'current' : (in_array($order_status, ['shipped', 'delivered']) ? 'completed' : 'pending'),
            'title' => 'Preparing Your Order',
            'description' => 'Your delicious baked goods are being prepared with care',
            'time' => ($order_status == 'confirmed') ? 'In Progress' : (in_array($order_status, ['shipped', 'delivered']) ? 'Completed' : 'Pending'),
            'icon' => ($order_status == 'confirmed') ? '👩‍🍳' : (in_array($order_status, ['shipped', 'delivered']) ? '✅' : '⏳')
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
                        <span class="detail-value">ORBKET<?php echo $orderData['order_info']['order_id']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Placed</span>
                        <span
                            class="detail-value"><?php echo date('M j, Y', strtotime($orderData['order_info']['order_date'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Expected Delivery</span>
                        <span
                            class="detail-value"><?php echo date('M j, Y', strtotime($orderData['order_info']['delivery_date'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Total Amount</span>
                        <span class="detail-value">₹<?php echo number_format($orderData['total_accepted'], 2); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Status</span>
                        <span class="detail-value payment-<?php echo $orderData['order_info']['payment_status']; ?>">
                            <?php echo ucfirst($orderData['order_info']['payment_status']); ?>
                        </span>
                    </div>
                </div>

                <!-- Order Items Status -->
                <div class="order-items-status">
                    <h4>Items Status</h4>
                    <?php foreach ($orderData['items'] as $item): ?>
                        <div class="item-status-row">
                            <div class="item-info">
                                <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                <br><small>by <?php echo htmlspecialchars($item['brand_name']); ?></small>
                                <br><small>Qty: <?php echo $item['quantity']; ?> ×
                                    ₹<?php echo number_format($item['unit_price'], 2); ?></small>
                            </div>
                            <div class="item-status-badge">
                                <span class="badge badge-<?php echo $item['baker_status']; ?>">
                                    <?php
                                    switch ($item['baker_status']) {
                                        case 'pending':
                                            echo '⏳ Pending';
                                            break;
                                        case 'accepted':
                                            echo '✅ Accepted';
                                            break;
                                        case 'rejected':
                                            echo '❌ Rejected';
                                            break;
                                        case 'delivered':
                                            echo '🎉 Delivered';
                                            break;
                                        default:
                                            echo ucfirst($item['baker_status']);
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Tracking Steps -->
                <div class="tracking-steps">
                    <h4>Order Progress</h4>
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

                <!-- Contact Bakers -->
                <?php if (!empty($orderData['bakers'])): ?>
                    <?php foreach ($orderData['bakers'] as $baker): ?>
                        <div class="contact-baker">
                            <h4>Need to reach <?php echo htmlspecialchars($baker['brand_name']); ?></h4>
                            <p>Have questions about your order? Get in touch with your bakers directly.</p>                            
                                <div class="contact-buttons">
                                    <a href="#message" class="btn-contact-sidebar btn-message"
                                        onclick="openMessageModal('<?php echo $baker['baker_name']; ?>')">💬Send Message</a>
                                    <a href="tel:<?php echo $baker['baker_phone']; ?>" class="btn-contact-sidebar btn-call">📞Call Baker</a>
                                </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            <?php else: ?>
                <div class="no-order-selected">
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <h3>No Order Selected</h3>
                        <p>Click "Track Order" on any of your paid orders to view detailed tracking information.</p>
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