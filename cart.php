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

$user_id = $_SESSION['user_id'];

// Handle cart quantity updates and deletions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $cart_id = $_POST['cart_id'];
  $action = $_POST['action'];

  if ($action == 'delete') {
    $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    header("Location: cart.php"."#cartcontent");
    exit;
  } else {
    $quantity = intval($_POST['quantity']);

    if ($action == 'increase') {
      $quantity += 1;
    } elseif ($action == 'decrease' && $quantity > 1) {
      $quantity -= 1;
    }

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
    $stmt->bind_param("ii", $quantity, $cart_id);
    $stmt->execute();
    header("Location: cart.php");
    exit;
  }
}

// Handle add to cart 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
  $product_id = intval($_POST['product_id']);
  $quantity = intval($_POST['quantity']);
  $return_to = isset($_POST['return_to']) ? $_POST['return_to'] : 'products.php';

  // Check if product is already in cart
  $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
  $stmt->bind_param("ii", $user_id, $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($existing = $result->fetch_assoc()) {
    // If already in cart, update quantity
    $new_quantity = $existing['quantity'] + $quantity;
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
    $stmt->bind_param("ii", $new_quantity, $existing['cart_id']);
    $stmt->execute();
  } else {
    // Else, insert new cart item
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
  }

  header("Location: $return_to");
  exit;
}

// Fetch cart items for display
$cart_items = [];
$sql = "SELECT c.cart_id, c.product_id, c.quantity, p.name, p.price, p.image, 
               b.brand_name, u.full_name, p.baker_id
        FROM cart c 
        JOIN products p ON c.product_id = p.product_id 
        JOIN bakers b ON p.baker_id = b.baker_id
        JOIN users u ON b.user_id = u.user_id
        WHERE c.user_id = ?
        ORDER BY b.baker_id, p.name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $cart_items[] = $row;
}

// Group items by baker for order processing
$baker_groups = [];
foreach ($cart_items as $item) {
  $baker_groups[$item['baker_id']][] = $item;
}

// Handle order placement - FIXED FOR YOUR TABLE STRUCTURE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
  $delivery_address = $_POST['delivery_address'] ?? '';
  $delivery_date = $_POST['delivery_date'] ?? '';

  if (empty($delivery_address) || empty($delivery_date)) {
    echo "<script>alert('Please fill in all delivery details.'); window.location.href='cart.php';</script>";
    exit;
  }

  // Start transaction
  $conn->autocommit(FALSE);

  try {
    // Create separate orders for each baker (like Flipkart does with different sellers)
    foreach ($baker_groups as $baker_id => $items) {
      // Calculate total for this baker's items
      $total_amount = 0;
      foreach ($items as $item) {
        $total_amount += $item['price'] * $item['quantity'];
      }

      // Insert order for this baker
      $order_stmt = $conn->prepare("INSERT INTO orders (customer_id, baker_id, total_amount, delivery_address, delivery_date, order_status, payment_status) VALUES (?, ?, ?, ?, ?, 'pending', 'pending')");
      $order_stmt->bind_param("iidss", $user_id, $baker_id, $total_amount, $delivery_address, $delivery_date);
      $order_stmt->execute();
      $order_id = $order_stmt->insert_id;

      // Insert order items for this order
      $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

      foreach ($items as $item) {
        $item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $item_stmt->execute();

        // Remove from cart
        $delete_stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
        $delete_stmt->bind_param("i", $item['cart_id']);
        $delete_stmt->execute();
      }
    }

    // Commit transaction
    $conn->commit();
    $conn->autocommit(TRUE);

    echo "<script>alert('Orders placed successfully! Each baker will confirm their items separately.'); window.location.href='customerorders.php';</script>";
    exit;

  } catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    $conn->autocommit(TRUE);
    echo "<script>alert('Error placing order. Please try again.'); window.location.href='cart.php';</script>";
    exit;
  }
}
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

<?php include 'custnavbar.php'; ?> 

<body>
  <!-- Main Content -->
  <main class="main">
    <div class="container">
      <div class="order-grid">
        <!-- Product Selection -->
        <div class="content-card">
          <div class="card-header" id="cartcontent">
            <h2>Your Cart</h2>
            <p class="card-description">View your items and change their quantities</p>
          </div>
          <div class="card-content" >
            <?php if (empty($cart_items)) { ?>
              <h2 class="cart-empty-title">Oops! Your cart's feeling a little lonely.🥺</h2>
              <p class="cart-empty-message">It's currently as empty as a cookie jar after midnight. Why not sprinkle in
                some sweetness and start shopping?</p>
              <p class="cart-empty">
                <button class="shortcut" onclick="window.location.href='products.php'">Browse Treats</button> 
                or
                <button class="shortcut" onclick="window.location.href='customerdashboard.php'">Return to Home</button>
              </p>
            <?php } else { ?>

              <!-- Group items by baker -->
              <?php
              $total = 0;
              foreach ($baker_groups as $baker_id => $items):
                $baker_total = 0;
                ?>

                <!-- Products Grid for this baker -->
                <div class="products-grid">
                  <?php foreach ($items as $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $baker_total += $subtotal;
                    ?>
                    <div class="product-card">
                      <div class="product-image">
                        <img src="<?php echo $item['image'] ? 'uploads/' . $item['image'] : 'no preview available'; ?>"
                          alt="<?php echo htmlspecialchars($item['name']); ?>">
                      </div>
                      <div class="product-info">
                        <div class="product-name"
                          onclick="window.location.href='productinfopage.php?product_id=<?= $item['product_id']; ?>'"
                          title="View Product Details"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="product-description"
                          onclick="window.location.href='bakerinfopage.php?baker_id=<?= $item['baker_id']; ?>'"
                          title="View Baker Details">
                          <?= htmlspecialchars($item['brand_name'] ?: $item['full_name']) ?>
                        </div>
                        <div class="product-price">₹<?= number_format($item['price'], 2) ?></div>

                        <!-- Quantity Controls -->
                        <form action="cart.php" method="post" class="quantity-controls">
                          <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                          <button name="action" value="decrease" class="quantity-btn" type="submit">-</button>
                          <input type="number" name="quantity" class="quantity-input" value="<?= $item['quantity'] ?>" min="1"
                            readonly>
                          <button name="action" value="increase" class="quantity-btn" type="submit">+</button>
                        </form>

                        <!-- Delete item -->
                        <form action="cart.php" method="post" style="margin-top: 12px;">
                          <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                          <button type="submit" name="action" value="delete" class="delete-btn-modern">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                              stroke-width="2">
                              <polyline points="3,6 5,6 21,6"></polyline>
                              <path d="M19,6V20a2,2 0 0,1 -2,2H7a2,2 0,0,1 -2,-2V6M8,6V4a2,2 0,0,1 2,-2h4a2,2 0,0,1 2,2V6">
                              </path>
                              <line x1="10" y1="11" x2="10" y2="17"></line>
                              <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                            Remove
                          </button>
                        </form>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>

              <!-- Special Instructions -->             
                <!-- <div class="special-section">
                  <h4>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    Special Instructions (if any)
                  </h4>
                  <textarea class="form-textarea"  name="special_msg[<?= $item['product_id'] ?>]"
                    placeholder="Any special requests, dietary restrictions, or custom decorations..."
                    style="width: 100%; border: 1px solid #f59e0b;"></textarea>
                </div> -->
                
                <div class="sub-total">
                  <strong style="color: #006c4a">Sub Total:
                    ₹<?= number_format($baker_total, 2) ?></strong>
                </div>

                <?php
                $total += $baker_total;
              endforeach;
              ?>


              <!-- Customer Information -->
              <div class="form-section">
                <h3>Customer Information</h3>
                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-input" required readonly
                      value="<?= htmlspecialchars($_SESSION['name']) ?>">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required readonly
                      value="<?= htmlspecialchars($_SESSION['email']) ?>">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Phone
                      <small style="color: #6b7280">(<a style="color: #6b7280" href="customerprofile.php">Change phone number?</a>)</small>
                    </label>
                    <input type="tel" name="phone" class="form-input" required readonly
                      value="<?= htmlspecialchars($_SESSION['phone']) ?>">
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="content-card order-summary">
          <div class="card-header">
            <h2>Order Summary</h2>
            <p class="card-description">Review your items</p>
          </div>
          <div class="card-content">
            <?php if (!empty($cart_items)) { ?>
              <div class="summary-item">
                <span class="summary-name all"><b>No of items</b></span>
                <span class="summary-quantity"><?= count($cart_items) ?></span>
              </div>

              <?php foreach ($baker_groups as $baker_id => $items):
                $baker_total = 0;
                ?>
                <?php foreach ($items as $item):
                  $item_total = $item['price'] * $item['quantity'];
                  $baker_total += $item_total;
                  ?>
                  <div class="summary-item">
                    <span class="summary-name"><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></span>
                    <span class="summary-price">₹<?= number_format($item_total, 2) ?></span>
                  </div>
                <?php endforeach; ?>

              <?php endforeach; ?>

              <div class="summary-item total">
                <span class="summary-total"><b>Grand Total</b></span>
                <span class="summary-price sum"><b>₹<?= number_format($total, 2) ?></b></span>
              </div>

              <!-- Form for placing order -->
              <form method="post" action="cart.php">
                <input type="hidden" name="place_order" value="1">
                <div class="form-section">
                  <h3>Delivery Details</h3>
                  <div class="form-grid">
                    <div class="form-group">
                      <label class="form-label">Delivery Date & Time</label>
                      <input type="datetime-local" name='delivery_date' class="form-input" required
                        min="<?= date('Y-m-d\TH:i', strtotime('+2 hours')) ?>">
                    </div>
                    <div class="form-group">
                      <label class="form-label">Delivery Address</label>
                      <textarea name='delivery_address' class="form-textarea"
                        placeholder="Enter complete delivery address with pincode" rows="3" required></textarea>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn-primary" style="margin-top: 24px;">
                  <img src="media/cart2.png" alt="Cart" width="25" height="25" style="vertical-align: top;">
                  Place Order
                </button>
              </form>

              <p style="font-size: 0.75rem; color: #6b7280; text-align: center; margin-top: 16px;">
                You will receive a confirmation email with details.
              </p>
            <?php } ?>
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
</body>

</html>