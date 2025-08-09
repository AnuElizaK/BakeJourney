<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

$customer_id = $_SESSION['user_id'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];
    
    if ($action === 'cancel') {
        // Cancel the order permanently
        $update_query = "UPDATE orders SET order_status = 'cancelled', payment_status = 'failed' WHERE order_id = ? AND customer_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ii", $order_id, $customer_id);
        
        if ($stmt->execute()) {
            echo "<script>
                alert('Order cancelled successfully!');
                window.location.href = 'customerorders.php';
            </script>";
        } else {
            echo "<script>
                alert('Error cancelling order. Please try again.');
                window.history.back();
            </script>";
        }
        exit();
    }
    
    if ($action === 'pay') {
        // Redirect to payment form
        $order_id = $_POST['order_id'];
        $amount = $_POST['amount'];
    }
}

// Handle payment processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_payment'])) {
    $order_id = $_POST['order_id'];
    $payment_method = $_POST['payment_method'];
    $payment_action = $_POST['payment_action'];
    
    if ($payment_action === 'pay_now') {
        // Simulate successful payment
        $update_query = "UPDATE orders SET payment_status = 'success', payment_method = ? WHERE order_id = ? AND customer_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sii", $payment_method, $order_id, $customer_id);
        
        if ($stmt->execute()) {
            echo "<script>
                alert('Payment successful! Thanks for shopping with BakeJourney!');
                window.location.href = 'customerorders.php';
            </script>";
        } else {
            echo "<script>
                alert('Payment failed. Please try again.');
                window.history.back();
            </script>";
        }
    } else {
        // Payment cancelled
        echo "<script>
            alert('Payment cancelled.');
            window.location.href = 'customerorders.php';
        </script>";
    }
    exit();
}

// Get order details if redirected from customerorders.php
if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $amount = $_POST['amount'];
    
    // Fetch order details
    $query = "
        SELECT o.*, oi.*, p.name as product_name, p.image, b.brand_name
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN products p ON oi.product_id = p.product_id
        JOIN bakers b ON p.baker_id = b.baker_id
        WHERE o.order_id = ? AND o.customer_id = ? AND oi.baker_status = 'accepted'
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $order_id, $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $order_items = [];
    $order_info = null;
    
    while ($row = $result->fetch_assoc()) {
        if (!$order_info) {
            $order_info = [
                'order_id' => $row['order_id'],
                'order_date' => $row['order_date'],
                'delivery_address' => $row['delivery_address'],
                'delivery_date' => $row['delivery_date']
            ];
        }
        $order_items[] = $row;
    }
} else {
    header("Location: customerorders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | BakeJourney</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .payment-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .payment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .payment-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .payment-content {
            padding: 40px;
        }
        
        .order-summary {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .order-summary h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .item-details h4 {
            color: #333;
            margin-bottom: 5px;
        }
        
        .item-details p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .item-price {
            font-weight: bold;
            color: #667eea;
            font-size: 1.1rem;
        }
        
        .total-section {
            background: #667eea;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
        }
        
        .total-section h3 {
            font-size: 1.5rem;
        }
        
        .payment-methods {
            margin-top: 30px;
        }
        
        .payment-methods h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        
        .payment-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .payment-option {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-option:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        
        .payment-option.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .payment-option input[type="radio"] {
            display: none;
        }
        
        .payment-option i {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
        }
        
        .payment-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        
        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .order-info {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .order-info p {
            margin-bottom: 10px;
            color: #333;
        }
        
        .order-info strong {
            color: #1976d2;
        }
        
        @media (max-width: 768px) {
            .payment-options {
                grid-template-columns: 1fr;
            }
            
            .payment-actions {
                flex-direction: column;
            }
            
            .payment-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h1>🛒 Payment Checkout</h1>
            <p>Complete your order payment</p>
        </div>
        
        <div class="payment-content">
            <div class="order-info">
                <p><strong>Order ID:</strong> ORBKET<?= $order_info['order_id'] ?></p>
                <p><strong>Order Date:</strong> <?= date('d M Y', strtotime($order_info['order_date'])) ?></p>
                <p><strong>Delivery Address:</strong> <?= htmlspecialchars($order_info['delivery_address']) ?></p>
                <p><strong>Delivery Date:</strong> <?= date('d M Y', strtotime($order_info['delivery_date'])) ?></p>
            </div>
            
            <div class="order-summary">
                <h2>📋 Order Summary (Accepted Items Only)</h2>
                
                <?php foreach ($order_items as $item): ?>
                    <div class="order-item">
                        <div class="item-details">
                            <h4><?= htmlspecialchars($item['product_name']) ?></h4>
                            <p>by <?= htmlspecialchars($item['brand_name']) ?> • Qty: <?= $item['quantity'] ?></p>
                        </div>
                        <div class="item-price">₹<?= number_format($item['total_price'], 2) ?></div>
                    </div>
                <?php endforeach; ?>
                
                <div class="total-section">
                    <h3>Total Amount: ₹<?= number_format($amount, 2) ?></h3>
                </div>
            </div>
            
            <form method="POST" id="paymentForm">
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <input type="hidden" name="process_payment" value="1">
                
                <div class="payment-methods">
                    
                    <h3>💳 Select Payment Method <span style="color: red;">*</span></h3>
                    
                    <div class="payment-options">
                        <div class="payment-option" onclick="selectPayment('UPI')">
                            <input type="radio" name="payment_method" value="UPI" id="upi" required>
                            <i>📱</i>
                            <h4>UPI Payment</h4>
                            <p>Pay using UPI apps</p>
                        </div>
                        
                        <div class="payment-option" onclick="selectPayment('Card')">
                            <input type="radio" name="payment_method" value="Card" id="card" required>
                            <i>💳</i>
                            <h4>Card Payment</h4>
                            <p>Debit/Credit Card</p>
                        </div>
                    </div>
                </div>
                
                <div class="payment-actions">
                    <button type="submit" name="payment_action" value="pay_now" class="btn btn-primary" id="payBtn" disabled>
                        💰 Pay ₹<?= number_format($amount, 2) ?>
                    </button>
                    <button type="submit" name="payment_action" value="cancel" class="btn btn-secondary">
                        ❌ Cancel Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function selectPayment(method) {
            // Remove selected class from all options
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.currentTarget.classList.add('selected');
            
            // Check the radio button
            document.getElementById(method.toLowerCase()).checked = true;
            
            // Enable pay button
            document.getElementById('payBtn').disabled = false;
        }
        
        // Form validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedMethod && e.submitter.value === 'pay_now') {
                e.preventDefault();
                alert('Please select a payment method!');
            }
        });
    </script>
</body>
</html>