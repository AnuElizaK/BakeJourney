<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.php"); // Redirect to login if not authorized
    exit();
}

// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

$customer_id = $_SESSION['user_id'];
// Fetch liked products for the logged-in user
$stmt = $conn->prepare("
    SELECT p.*
    FROM products p
    INNER JOIN product_likes pl ON p.product_id = pl.product_id
    WHERE pl.customer_id = ?
    ORDER BY pl.liked_at DESC
");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$liked_products = $stmt->get_result();
$stmt->close();

// Get products in cart for this customer

$cart_stmt = $conn->prepare("SELECT product_id FROM cart WHERE user_id = ?");
$cart_stmt->bind_param("i", $customer_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

$cart_products = [];
while ($cart_item = $cart_result->fetch_assoc()) {
    $cart_products[] = $cart_item['product_id'];
}

// Handle Remove Like
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_like'])) {
    $product_id = intval($_POST['product_id']);
    $stmt = $conn->prepare("DELETE FROM product_likes WHERE customer_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $customer_id, $product_id);
    $stmt->execute();
    $stmt->close();
    header("Location: likedprod.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Us | BakeJourney</title>
    <meta name="description" content="BakeJourney - The Home Baker's Marketplace" />
    <meta name="author" content="BakeJourney" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@bakejourney" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            padding-top: 80px;
            background: linear-gradient(135deg, #fef7cd 0%, #fee996 100%);
            color: #1f2a38;
        }

        h1,
        h2 {
            font-family: 'Puanto', Roboto, sans-serif;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #fcd34d, #f59e0b);
            color: white;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(217, 119, 6, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(217, 119, 6, 0.4);
        }

        .btn-large {
            padding: 18px 48px;
            font-size: 1.25rem;
        }

        .btn-full {
            width: 100%;
        }

        .btn-outline {
            border: 2px solid white;
            color: white;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
        }

        .btn-outline:hover {
            background: white;
            color: #f59e0b;
            transform: translateY(-2px);
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-header h2 {
            font-size: 3rem;
            font-weight: bold;
            color: #1f2a38;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .section-header p {
            font-size: 1.25rem;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* Products Section */
        .products {
            padding: 50px 0;
        }

        .products-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
        }

        @media (min-width: 1024px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .product-card {
            position: relative;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .product-card:hover {
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.15);
            transform: translateY(-8px);
        }

        .product-image {
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-content {
            padding: 10px 20px 20px; 
        }

        .product-content h3 {
            font-size: 1.3rem;
            color: #1f2a38;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .product-content p {
            color: #6b7280;
            font-size: 0.8rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .section-header h2 {
                font-size: 2rem;
            }

            .section-header p {
                font-size: 1rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1030px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .no-products {
            text-align: center;
            color: #6b7280;
            font-size: 1.25rem;
            padding: 50px 0;
        }

        .cart-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #fcd34d, #d97706);
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            font-family: 'Segoe UI', Roboto, sans-serif;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.4s ease;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .cart-button:hover {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
        }

        .cart-button.added {
            background: white;
            border: 2px solid #f59e0b;
            color: #f59e0b;
        }

        /* Three-dot menu */
        .menu-container {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }

        .three-dot-menu {
            background: #ffffff;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            align-items: center;
            font-size: 1.3rem;
            color: black;
            padding: 5px 15px;
        }

        .menu-content {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-width: 120px;
            z-index: 1;
        }

        .menu-content.show {
            display: block;
        }

        .menu-content form {
            margin: 0;
        }

        .menu-content button {
            width: 100%;
            text-align: left;
            padding: 10px 5px;
            font-family: 'Segoe UI', Roboto, sans-serif;
            font-size: 1rem;
            color: #ef4444;
            background: none;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
    <!-- Liked Products Section -->
    <section class="products" id="products">
        <div class="container">
            <div class="section-header">
                <h2>Your Liked Products</h2>
                <p>Explore the products you've loved from our bakers.</p>
            </div>

            <div class="products-grid">
                <?php if ($liked_products->num_rows > 0): ?>
                    <?php while ($product = $liked_products->fetch_assoc()): ?>

                        <div class="product-card"
                            onclick="window.location.href='productinfopage.php?product_id=<?= $product['product_id']; ?>'">
                            <div class="menu-container">
                                <button class="three-dot-menu"
                                    onclick="event.stopPropagation(); toggleMenu(<?php echo $product['product_id']; ?>)">⋮</button>
                                <div class="menu-content" id="menu-<?php echo $product['product_id']; ?>">
                                    <form method="POST" action="likedprod.php">
                                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                        <button type="submit" name="remove_like" class="btn">Remove</button>
                                    </form>
                                </div>
                            </div>
                            <div class="product-image">
                                <img src="<?= !empty($product['image']) ? 'uploads/' . htmlspecialchars($product['image']) : 'media/pastry.png' ?>"
                                    alt="<?= htmlspecialchars($product['name']) ?>">
                            </div>
                            <?php
                            $is_in_cart = in_array($product['product_id'], $cart_products); ?>
                            <?php if ($is_in_cart): ?>
                                <!-- Show "Added to Cart" button if product is in cart -->
                                <button class="cart-button added" disabled>
                                    <img src="media/cart2yellow.png" alt="Added"
                                        style="width: 20px; height: 20px; vertical-align: top;">
                                    Added to Cart
                                </button>
                            <?php else: ?>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="return_to" value="likedprod.php">
                                    <button type="submit" name="add_to_cart" class="cart-button">
                                        <img src="media/cart2.png" alt="Cart"
                                            style="width: 20px; height: 20px; vertical-align: top;"> Add to Cart
                                    </button>
                                </form>
                            <?php endif; ?>
                            <div class="product-content">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p>
                                    <?php
                                    $desc = strip_tags($product['description']);
                                    $words = explode(' ', $desc);
                                    $max_words = 10;
                                    if (count($words) > $max_words) {
                                        $short = implode(' ', array_slice($words, 0, $max_words)) . '...' . ' more';
                                    } else {
                                        $short = $desc;
                                    }
                                    echo htmlspecialchars($short);
                                    ?>
                                </p>
                                <a href="productinfopage.php?product_id=<?= $product['product_id']; ?>"
                                    class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-products">
                        <p>You haven't liked any products yet. Start exploring our bakers' creations!</p>
                        <a href="products.php" class="btn btn-primary">Browse Products</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'globalfooter.php'; ?>

    <script>
        function toggleMenu(productId) {
            event.preventDefault();
            const menu = document.getElementById(`menu-${productId}`);
            const isShown = menu.classList.contains('show');
            // Close all menus
            document.querySelectorAll('.menu-content').forEach(m => m.classList.remove('show'));
            // Toggle the clicked menu
            if (!isShown) {
                menu.classList.add('show');
            }
        }

        // Close menu when clicking outside
        document.addEventListener('click', function (event) {
            if (!event.target.classList.contains('three-dot-menu')) {
                document.querySelectorAll('.menu-content').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    </script>

</body>

</html>