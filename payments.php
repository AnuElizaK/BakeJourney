<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['action'])) {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];

    if ($action === 'pay') {
        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'success' WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
    } elseif ($action === 'cancel') {
        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'failed' WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
    }
    header("Location: customerorders.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Payment Status</h1>
        <p>Your payment has been processed successfully.</p>
        <a href="customerorders.php" class="btn btn-primary">View My Orders</a>
    </div>
</body>
</html>