<?php
session_start();
include 'db.php';

// Check if user is logged in as customer
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php");
    exit();
}

// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

$customer_id = $_SESSION['user_id'];
$error_message = '';
$success_message = '';

//Handle payment logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_payment'])) {
    $order_id = intval($_POST['order_id']);
    $payment_method = $_POST['payment_method'];

    // Validate order belongs to customer and is payable
    $stmt = $conn->prepare("
        SELECT o.*, b.brand_name, u.full_name as baker_name, u.user_id as baker_user_id 
        FROM orders o 
        JOIN bakers b ON o.baker_id = b.baker_id 
        JOIN users u ON b.user_id = u.user_id 
        WHERE o.order_id = ? AND o.customer_id = ? AND o.order_status = 'accepted' AND o.payment_status = 'pending'
    ");
    $stmt->bind_param("ii", $order_id, $customer_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();

    if (!$order) {
        $error_message = "Invalid order or order not available for payment.";
    } else {
        if ($payment_method === 'netbanking') {
            $holder_name = $_POST['holder_name'] ?? '';
            $cardno = $_POST['cardno'] ?? '';
            $cvv = $_POST['cvv'] ?? '';
            $exp_date = $_POST['exp_date'] ?? '';

            // Check if card exists and belongs to logged-in customer
            $stmt = $conn->prepare("
                SELECT * FROM bank_accounts 
                WHERE user_id = ? AND holder_name = ? AND cardno = ? AND cvv = ? AND exp_date = ?
            ");
            $stmt->bind_param("issss", $customer_id, $holder_name, $cardno, $cvv, $exp_date);
            $stmt->execute();
            $bank = $stmt->get_result()->fetch_assoc();

            if (!$bank) {
                $error_message = "Invalid card details.";
            } elseif ($bank['balance'] < $order['total_amount']) {
                $error_message = "Insufficient balance.";
            } else {
                // Deduct from customer
                $new_balance = $bank['balance'] - $order['total_amount'];
                $stmt = $conn->prepare("UPDATE bank_accounts SET balance = ? WHERE id = ?");
                $stmt->bind_param("di", $new_balance, $bank['id']);
                $stmt->execute();

                // Credit baker
                $baker_user_id = $order['baker_user_id'];
                $baker_account = $conn->query("SELECT * FROM bank_accounts WHERE user_id = $baker_user_id")->fetch_assoc();
                if ($baker_account) {
                    $baker_new_balance = $baker_account['balance'] + $order['total_amount'];
                    $stmt = $conn->prepare("UPDATE bank_accounts SET balance = ? WHERE id = ?");
                    $stmt->bind_param("di", $baker_new_balance, $baker_account['id']);
                    $stmt->execute();

                    // Update order status
                    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'success' WHERE order_id = ?");
                    $stmt->bind_param("i", $order_id);
                    $stmt->execute();

                    $success_message = "Payment successful! Your order will be processed.";
                } else {
                    $error_message = "Baker account not found. Please contact support.";
                }
            }
        } else {
            // Simulate balance deduction for other payment methods (UPI, card, wallet)
            // Assume customer has a bank account for simulation
            $stmt = $conn->prepare("SELECT * FROM bank_accounts WHERE user_id = ? LIMIT 1");
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $bank = $stmt->get_result()->fetch_assoc();

            if (!$bank) {
                $error_message = "No bank account found for payment.";
            } elseif ($bank['balance'] < $order['total_amount']) {
                $error_message = "Insufficient balance.";
            } else {
                // Deduct from customer
                $new_balance = $bank['balance'] - $order['total_amount'];
                $stmt = $conn->prepare("UPDATE bank_accounts SET balance = ? WHERE id = ?");
                $stmt->bind_param("di", $new_balance, $bank['id']);
                $stmt->execute();

                // Credit baker
                $baker_user_id = $order['baker_user_id'];
                $baker_account = $conn->query("SELECT * FROM bank_accounts WHERE user_id = $baker_user_id")->fetch_assoc();
                if ($baker_account) {
                    $baker_new_balance = $baker_account['balance'] + $order['total_amount'];
                    $stmt = $conn->prepare("UPDATE bank_accounts SET balance = ? WHERE id = ?");
                    $stmt->bind_param("di", $baker_new_balance, $baker_account['id']);
                    $stmt->execute();

                    // Update order status
                    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'success' WHERE order_id = ?");
                    $stmt->bind_param("i", $order_id);
                    $stmt->execute();

                    $success_message = "Payment successful! Your order will be processed.";
                } else {
                    $error_message = "Baker account not found. Please contact support.";
                }
            }
        }

    }
}

// Get order details if order_id is provided
$order_details = null;
if (isset($_POST['order_id']) || isset($_GET['order_id'])) {
    $order_id = intval($_POST['order_id'] ?? $_GET['order_id']);

    // Fetch order with items
    $stmt = $conn->prepare("
        SELECT o.*, b.brand_name, u.full_name as baker_name, u.email as baker_email
        FROM orders o 
        JOIN bakers b ON o.baker_id = b.baker_id 
        JOIN users u ON b.user_id = u.user_id 
        WHERE o.order_id = ? AND o.customer_id = ? AND o.order_status = 'accepted' AND o.payment_status = 'pending'
    ");
    $stmt->bind_param("ii", $order_id, $customer_id);
    $stmt->execute();
    $order_details = $stmt->get_result()->fetch_assoc();

    if ($order_details) {
        // Fetch order items
        $stmt = $conn->prepare("
            SELECT oi.*, p.name as product_name, p.image 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.product_id 
            WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}

// If no valid order, redirect back
if (!$order_details && !$success_message) {
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
            font-family: 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #fef7cd 0%, #fee996 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(45deg, #d97706, #f59e0b, #fcd34d);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-family: 'Puanto', Roboto, sans-serif;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .content {
            padding: 30px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .order-summary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .order-number {
            font-size: 1.2rem;
            font-weight: bold;
            color: #495057;
        }

        .baker-info {
            text-align: right;
            color: #6c757d;
        }

        .items-list {
            margin-bottom: 20px;
        }

        .item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }

        .item-details {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .item-price {
            font-weight: bold;
            color: #495057;
        }

        .total-section {
            border-top: 2px solid #495057;
            padding-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .total-amount {
            font-size: 1.3rem;
            font-weight: bold;
            color: #28a745;
        }

        .payment-section {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 25px;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .payment-method:hover {
            border-color: #f59e0b;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }

        .payment-method.selected {
            border-color: #f59e0b;
            background: #f8f9ff;
        }

        .payment-method input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .payment-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-family: 'Segoe UI', Roboto, sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(45deg, #d97706, #f59e0b, #fcd34d);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-large {
            padding: 15px 40px;
            font-size: 1.1rem;
            width: 100%;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #f59e0b;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .delivery-info {
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .delivery-info h4 {
            color: #d97706;
            margin-bottom: 10px;
        }

        #netbanking-form input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        #netbanking-form label {
            display: block;
            margin-bottom: 5px;
            color: #495057;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
            }

            .content {
                padding: 20px;
            }

            .order-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .baker-info {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>BakeJourney Secure Payment</h1>
            <p>Complete your order payment safely</p>
        </div>

        <div class="content">
            <a href="customerorders.php" class="back-link">← Back to My Orders</a>

            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    ❌ <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    ✅ <?= htmlspecialchars($success_message) ?>
                    <br><small>Redirecting to orders page in <span id="countdown">3</span> seconds...</small>
                </div>

                <script>
                    let seconds = 3;
                    const countdownEl = document.getElementById('countdown');

                    const interval = setInterval(() => {
                        seconds--;
                        countdownEl.textContent = seconds;
                        if (seconds <= 0) {
                            clearInterval(interval);
                            window.location.href = 'customerorders.php';
                        }
                    }, 1000);
                </script>
            <?php endif; ?>

            <?php if ($order_details && !$success_message): ?>
                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="order-header">
                        <div>
                            <div class="order-number">Order
                                #BJ<?= str_pad($order_details['order_id'], 6, '0', STR_PAD_LEFT) ?></div>
                            <div style="color: #6c757d; font-size: 0.9rem;">
                                Placed on <?= date('d M Y, h:i A', strtotime($order_details['order_date'])) ?>
                            </div>
                        </div>
                        <div class="baker-info">
                            <div><strong><?= htmlspecialchars($order_details['brand_name']) ?></strong></div>
                            <div><?= htmlspecialchars($order_details['baker_name']) ?></div>
                        </div>
                    </div>

                    <div class="items-list">
                        <?php foreach ($order_items as $item): ?>
                            <div class="item">
                                <div class="item-image">
                                    <img src="<?= $item['image'] ? 'uploads/' . $item['image'] : 'media/placeholder.jpg' ?>"
                                        alt="<?= htmlspecialchars($item['product_name']) ?>">
                                </div>
                                <div class="item-info">
                                    <div class="item-name"><?= htmlspecialchars($item['product_name']) ?></div>
                                    <div class="item-details">
                                        Quantity: <?= $item['quantity'] ?> × ₹<?= number_format($item['price'], 2) ?>
                                    </div>
                                </div>
                                <div class="item-price">
                                    ₹<?= number_format($item['quantity'] * $item['price'], 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="total-section">
                        <span class="total-label">Total Amount:</span>
                        <span class="total-amount">₹<?= number_format($order_details['total_amount'], 2) ?></span>
                    </div>

                    <div class="delivery-info">
                        <h4>Delivery Details</h4>
                        <p><strong>Address:</strong> <?= htmlspecialchars($order_details['delivery_address']) ?></p>
                        <p><strong>Expected Delivery:</strong>
                            <?= date('d M Y, h:i A', strtotime($order_details['delivery_date'])) ?></p>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="payment-section">
                    <h3 style="margin-bottom: 20px; color: #495057;">Choose Payment Method</h3>

                    <form method="POST" action="payments.php">
                        <input type="hidden" name="order_id" value="<?= $order_details['order_id'] ?>">

                        <div class="payment-methods">
                            <label class="payment-method" for="upi">
                                <input type="radio" name="payment_method" value="upi" id="upi" required>
                                <div class="payment-icon">📱</div>
                                <div><strong>UPI</strong></div>
                                <div style="font-size: 0.9rem; color: #6c757d;">PhonePe, GPay, Paytm</div>
                            </label>

                            <label class="payment-method" for="card">
                                <input type="radio" name="payment_method" value="card" id="card" required>
                                <div class="payment-icon">💳</div>
                                <div><strong>Debit/Credit Card</strong></div>
                                <div style="font-size: 0.9rem; color: #6c757d;">Visa, Mastercard, Rupay</div>
                            </label>

                            <label class="payment-method" for="netbanking">
                                <input type="radio" name="payment_method" value="netbanking" id="netbanking" required>
                                <div class="payment-icon">🏦</div>
                                <div><strong>Net Banking</strong></div>
                                <div style="font-size: 0.9rem; color: #6c757d;">All major banks</div>
                            </label>

                            <label class="payment-method" for="wallet">
                                <input type="radio" name="payment_method" value="wallet" id="wallet" required>
                                <div class="payment-icon">👛</div>
                                <div><strong>Digital Wallet</strong></div>
                                <div style="font-size: 0.9rem; color: #6c757d;">Paytm, Amazon Pay</div>
                            </label>
                        </div>

                        <!-- Net Banking Form (Hidden by default) -->
                        <div id="netbanking-form"
                            style="display: none; margin-top: 20px; margin-bottom: 20px; padding: 15px; border: 1px solid #dee2e6; border-radius: 5px;">
                            <h4 style="color: #495057; margin-bottom: 15px;">Net Banking Details</h4>
                            <div style="margin-bottom: 15px;">
                                <label for="cardholder-name">Cardholder Name</label>
                                <input style="font-family: 'Segoe UI', Roboto, sans-serif;" type="text" id="cardholder-name"
                                    name="holder_name" placeholder="Enter cardholder name">
                            </div>
                            <div style="margin-bottom: 15px;">
                                <label for="card-number">Card Number</label>
                                <input style="font-family: 'Segoe UI', Roboto, sans-serif;" type="text" id="card-number"
                                    name="cardno" placeholder="Enter card number" maxlength="16">
                            </div>
                            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                                <div style="flex: 1;">
                                    <label for="expiry-date">Expiry Date(YYYY-MM-DD)</label>
                                    <input style="font-family: 'Segoe UI', Roboto, sans-serif;" type="text" id="expiry-date"
                                        name="exp_date" placeholder="YYYY-MM-DD">
                                </div>
                                <div style="flex: 1;">
                                    <label for="cvv">CVV</label>
                                    <input style="font-family: 'Segoe UI', Roboto, sans-serif;" type="text" id="cvv"
                                        name="cvv" placeholder="CVV" maxlength="3">
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="process_payment" class="btn btn-primary btn-large">
                            Pay ₹<?= number_format($order_details['total_amount'], 2) ?> Securely
                        </button>
                    </form>
                </div>

                <div style="text-align: center; margin-top: 20px; color: #6c757d; font-size: 0.9rem;">
                    🔒 Your payment information is secure and encrypted
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Toggle net banking form visibility
        document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
            radio.addEventListener('change', function () {
                const netbankingForm = document.getElementById('netbanking-form');
                if (this.value === 'netbanking' && this.checked) {
                    netbankingForm.style.display = 'block';
                } else {
                    netbankingForm.style.display = 'none';
                }
            });
        });


        // Client-side validation for net banking form
        document.querySelector('form').addEventListener('submit', function (e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (paymentMethod && paymentMethod.value === 'netbanking') {
                const cardNumber = document.getElementById('card-number').value;
                const cvv = document.getElementById('cvv').value;
                const expiryDate = document.getElementById('expiry-date').value;
                const holderName = document.getElementById('cardholder-name').value;

                if (!holderName.trim()) {
                    e.preventDefault();
                    alert('Cardholder name is required.');
                    return;
                }
                if (!/^[0-9]{16}$/.test(cardNumber)) {
                    e.preventDefault();
                    alert('Card number must be 16 digits.');
                    return;
                }
                if (!/^[0-9]{3}$/.test(cvv)) {
                    e.preventDefault();
                    alert('CVV must be 3 digits.');
                    return;
                }
                if (!/^\d{4}-\d{2}-\d{2}$/.test(expiryDate)) {
                    e.preventDefault();
                    alert('Expiry date must be in YYYY-MM-DD format.');
                    return;
                }
                // Ensure expiry date is not in the past
                const [year, month, day] = expiryDate.split('-');
                const expDate = new Date(year, month - 1, day);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (expDate < today) {
                    e.preventDefault();
                    alert('Expiry date cannot be in the past.');
                    return;
                }
            }
        });


        // Add visual feedback for payment method selection
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function () {
                // Remove selected class from all methods
                document.querySelectorAll('.payment-method').forEach(method => {
                    method.classList.remove('selected');
                });
                // Add selected class to current method
                this.closest('.payment-method').classList.add('selected');
            });
        });

    </script>
</body>

</html>