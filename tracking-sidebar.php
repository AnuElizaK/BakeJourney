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
            width: 100%;
            right: -100%;
        }

        .contact-buttons {
            flex-direction: column;
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
// ==============================================
// FILE: includes/sidebar.php
// ==============================================

// Sample order data - in real implementation, fetch from database
$orderData = [
    'order_id' => '#BJ-2024-001',
    'placed_date' => 'January 15, 2024',
    'expected_date' => 'January 17, 2024',
    'total_amount' => '₹39.00',
    'baker_name' => 'Sarah Johnson',
    'baker_phone' => '+1234567890',
    'status' => 'baking', // confirmed, preparing, baking, ready, completed
    'steps' => [
        [
            'status' => 'completed',
            'title' => 'Order Confirmed',
            'description' => 'Your order has been received and confirmed by Sarah Johnson',
            'time' => 'Jan 15, 2024 - 10:30 AM',
            'icon' => '✓'
        ],
        [
            'status' => 'completed',
            'title' => 'Ingredients Prepared',
            'description' => 'All ingredients have been gathered and preparation has started',
            'time' => 'Jan 15, 2024 - 2:15 PM',
            'icon' => '✓'
        ],
        [
            'status' => 'current',
            'title' => 'Baking in Progress',
            'description' => 'Your delicious breads are currently being baked with love',
            'time' => 'Started: Jan 16, 2024 - 6:00 AM',
            'icon' => '🥖'
        ],
        [
            'status' => 'pending',
            'title' => 'Ready for Pickup',
            'description' => 'Order will be packaged and ready for collection',
            'time' => 'Expected: Jan 17, 2024 - 9:00 AM',
            'icon' => '📦'
        ],
        [
            'status' => 'pending',
            'title' => 'Order Complete',
            'description' => 'Enjoy your freshly baked goods!',
            'time' => 'Expected: Jan 17, 2024',
            'icon' => '🎉'
        ]
    ]
];

// Function to render tracking sidebar
function renderTrackingSidebar($orderData)
{
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
            <!-- Order Details -->
            <div class="sidebar-order-details">
                <div class="detail-row">
                    <span class="detail-label">Order ID</span>
                    <span class="detail-value"><?php echo $orderData['order_id']; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Placed</span>
                    <span class="detail-value"><?php echo $orderData['placed_date']; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Expected</span>
                    <span class="detail-value"><?php echo $orderData['expected_date']; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total</span>
                    <span class="detail-value"><?php echo $orderData['total_amount']; ?></span>
                </div>
            </div>

            <!-- Tracking Steps -->
            <div class="tracking-steps">
                <?php foreach ($orderData['steps'] as $step): ?>
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

            <!-- Contact Baker -->
            <div class="contact-baker">
                <h3>Need to reach <?php echo $orderData['baker_name']; ?>?</h3>
                <p>Have questions about your order? Get in touch directly with your baker.</p>
                <div class="contact-buttons">
                    <a href="#message" class="btn-contact-sidebar btn-message">💬 Send Message</a>
                    <a href="tel:<?php echo $orderData['baker_phone']; ?>" class="btn-contact-sidebar btn-call">📞 Call
                        Baker</a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<script>
    // Sidebar functionality
    function openTrackingSidebar() {
        const sidebar = document.getElementById('trackingSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.add('active');
        overlay.classList.add('active');

        // Prevent body scroll when sidebar is open
        document.body.style.overflow = 'hidden';
    }

    function closeTrackingSidebar() {
        const sidebar = document.getElementById('trackingSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.remove('active');
        overlay.classList.remove('active');

        // Restore body scroll
        document.body.style.overflow = '';
    }

    // Close sidebar with Escape key
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeTrackingSidebar();
        }
    });
</script>