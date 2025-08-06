<?php session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
  header("Location: index.php"); // Redirect to login if not authorized
  exit();
}

// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // $user_id = $_SESSION['user_id']; // Make sure user is logged in
    $product_id = intval($_POST['product_id']);
     $quantity = intval($_POST['quantity']);
    

    // Check if product is already in cart
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($existing = $result->fetch_assoc()) {
        // If already in cart, update quantity
        $new_quantity = $existing['quantity'] +1;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
        $stmt->bind_param("ii", $new_quantity, $existing['cart_id']);
        $stmt->execute();
    } else {
        // Else, insert new cart item
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity ) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
    }


    header("Location: cart.php");
    exit;
}


//fetch cart items from database
$sql = "SELECT *
        FROM cart c 
        JOIN products p ON c.product_id = p.product_id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Place Your Order - BakeJourney</title>
  <meta name="description" content="Order fresh baked goods" />
  <link rel="stylesheet" href="cart.css" />
</head>

<?php
include 'custnavbar.php';
?>

<body>
  <!-- Main Content -->
  <main class="main">
    <div class="container">
      <div class="order-grid">
        <!-- Product Selection -->
        <div class="content-card">
          <div class="card-header">
            <h2>Your Cart</h2>
            <p class="card-description">Select your items </p>
          </div>
          <div class="card-content">

            <!-- Products Grid -->
            <div class="products-grid">
              <?php
              $total = 0;
              foreach ($cart_items as $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                ?>
                <div class="product-card" onclick="toggleSelect(this)">
                  <div class="product-image">
                    <img
                      src="<?php echo $item['image'] ? 'uploads/' . $item['image'] : 'no preview available'; ?>"
                      alt="<?php echo $item['name']; ?>">
                  </div>
                  <div class="product-info">
                    <div class="product-name" onclick="window.location.href='productpage.php'" title="View More Details">
                      <?php echo $item['name']; ?>
                    </div>
                    <div class="product-description"><?php echo $item['description']; ?></div>
                    <div class="product-price">₹<?php echo number_format($item['price'], 2); ?></div>

                    <!-- Quantity Controls -->
                    <div>
                      <form action="cart.php" method="post" class="quantity-controls">
                        <input type="hidden" name="cart_id" class="quantity-input" value="<?= $item['cart_id'] ?>">
                        <button name="action" value="decrease" class="quantity-btn">-</button>
                        <input type="number" name="quantity" class="quantity-input" value="<?= $item['quantity'] ?>"
                          min="1">
                        <button name="action" value="increase" class="quantity-btn">+</button>
                      </form>
                    </div>

                    <!-- delete item -->
                   <!-- Replace your delete section with this -->
<div>
  <form action="cart.php" method="post" style="margin-top: 8px;">
    <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
    <button type="submit" name="action" value="delete" class="delete-btn-modern"
            onclick="return confirm('Remove this item from cart?')">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="3,6 5,6 21,6"></polyline>
        <path d="M19,6V20a2,2 0 0,1 -2,2H7a2,2 0,0,1 -2,-2V6M8,6V4a2,2 0,0,1 2,-2h4a2,2 0,0,1 2,2V6"></path>
        <line x1="10" y1="11" x2="10" y2="17"></line>
        <line x1="14" y1="11" x2="14" y2="17"></line>
      </svg>
      
    </button>
  </form>
</div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Special Instructions -->
            <div class="special-section">
              <h4>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                Special Instructions
              </h4>
              <textarea class="form-textarea"
                placeholder="Any special requests, dietary restrictions, or custom decorations..."
                style="width: 100%; border: 1px solid #f59e0b;"></textarea>
            </div>

            <!-- Customer Information -->
            <div class="form-section">
              <h3>Customer Information</h3>
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Full Name </label>
                  <input type="text" name="full_name" class="form-input" required
                    value="<?php echo $_SESSION['name']; ?>">
                </div>
                <div class="form-group">
                  <label class="form-label">Email </label>
                  <input type="email" name="email" class="form-input" required
                    value="<?php echo $_SESSION['email']; ?>">
                </div>
                <div class="form-group">
                  <label class="form-label">Phone </label>
                  <input type="tel" name="phone" class="form-input" required value="<?php echo $_SESSION['phone']; ?>">
                </div>
              </div>
            </div>

            <!-- Delivery Information -->
            <div class="form-section">
              <h3>Delivery Details</h3>
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Delivery Date </label>
                  <input type="datetime-local" name='delivery_date' class="form-input" required>
                </div>
                <div class="form-group">
                  <label class="form-label">Delivery Address </label>
                  <textarea name='delivery_address' class="form-input" required></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="content-card order-summary">
          <div class="card-header">
            <h2>Order Summary</h2>
            <p class="card-description">Review your items</p>
          </div>
          <div class="card-content">

            <div class="summary-item">
              <span class="summary-name">No of items</span>
              <span class="summary-price"><?= count($cart_items) ?></span>
            </div>
            <?php foreach ($cart_items as $item): ?>
              <div class="summary-item">
                <span class="summary-name"><?= $item['name'] ?> × <?= $item['quantity'] ?></span>
                <span class="summary-price">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
              </div>
            <?php endforeach; ?>

            <div class="summary-item">
              <span class="summary-name"><b>Grand total</b></span>
              <span class="summary-price"><b>₹<?= number_format($total, 2) ?></b></span>
            </div>


            <button class="btn-primary" style="margin-top: 24px;">
              <img src="media/cart2.png" alt="Cart" width="25" height="25" style="vertical-align:middle;"> Place Order
            </button>

            <p style="font-size: 0.75rem; color: #6b7280; text-align: center; margin-top: 16px;">
              You will receive a confirmation email with pickup details
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include 'globalfooter.php'; ?>

  <script>
    function toggleSelect(card) {
      card.classList.toggle('selected');
    }
  </script>

  <?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = $_POST['cart_id'];
    $action = $_POST['action'];
    $quantity = intval($_POST['quantity']);

    // Adjust quantity based on button clicked
    if ($action == 'increase') {
      $quantity += 1;
    } elseif ($action == 'decrease' && $quantity > 1) {
      $quantity -= 1;
    }
    elseif ($action == 'delete') {
      // Delete item from cart
      $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
      $stmt->bind_param("i", $cart_id);
      if ($stmt->execute()) {
        echo "<script>window.location.href='cart.php';</script>";
      }
    }

    // Update cart
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
    $stmt->bind_param("ii", $quantity, $cart_id);
    if ($stmt->execute()) {
      echo "<script>window.location.href='cart.php';</script>";
    }

    $stmt->close();


    exit;
  }
  ?>
</body>

</html>