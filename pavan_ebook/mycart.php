<?php
include "config.php";
session_start();

$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT c.book_id, b.title, b.author, b.price, b.cover_image, c.quantity 
        FROM cart c 
        JOIN books b ON c.book_id = b.id 
        WHERE c.user_id = '$user_id'";
$result = $conn->query($sql);

$cart_items = [];
$total_price = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}

// Fetch user balance
$sql_wallet = "SELECT wallet_balance FROM users WHERE id = '$user_id'";
$result_wallet = $conn->query($sql_wallet);
$user = $result_wallet->fetch_assoc();
$wallet_balance = $user['wallet_balance'];

$tax = 0.05 * $total_price;
$grand_total = $total_price + $tax;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Cart</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding: 15px;
        }
        .cart-img {
            width: 80px;
            height: auto;
            border-radius: 5px;
            margin-right: 15px;
            object-fit: cover;
        }
        .cart-details {
            flex: 1;
        }
        .cart-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .cart-author {
            font-size: 14px;
            color: #777;
        }
        .cart-price {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
        }
        .quantity-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 5px;
        }
        .quantity-btn:hover {
            background: #0056b3;
        }
        .remove-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }
        .remove-btn:hover {
            background: #c82333;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            text-align: right;
            font-size: 16px;
        }
        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
        }
        .checkout-btn {
            display: block;
            width: 100%;
            text-align: center;
            background: #007bff;
            color: white;
            padding: 12px;
            border-radius: 5px;
            font-size: 18px;
            text-decoration: none;
            margin-top: 20px;
            transition: 0.3s;
        }
        .checkout-btn:hover {
            background: #0056b3;
        }
        .error-message {
            color: red;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>🛒 My Cart</h2>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'insufficient_balance'): ?>
            <p class="error-message">❌ Not enough balance. Add funds to proceed.</p>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <p style="text-align: center; font-size: 18px;">Your cart is empty!</p>
        <?php else: ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="uploads/<?php echo $item['cover_image']; ?>" class="cart-img">
                    <div class="cart-details">
                        <p class="cart-title"><?php echo $item['title']; ?></p>
                        <p class="cart-author">by <?php echo $item['author']; ?></p>
                        <p class="cart-price">₹<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                    <div class="quantity-controls">
                        <form action="update_quantity.php" method="post">
                            <input type="hidden" name="book_id" value="<?php echo $item['book_id']; ?>">
                            <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                            <span><?php echo $item['quantity']; ?></span>
                            <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                        </form>
                    </div>
                    <form action="remove_from_cart.php" method="post">
                        <input type="hidden" name="book_id" value="<?php echo $item['book_id']; ?>">
                        <button type="submit" class="remove-btn">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>

            <div class="summary">
                <p><strong>Subtotal: ₹<?php echo number_format($total_price, 2); ?></strong></p>
                <p><strong>Tax (5%): ₹<?php echo number_format($tax, 2); ?></strong></p>
                <p class="grand-total"><strong>Grand Total: ₹<?php echo number_format($grand_total, 2); ?></strong></p>
            </div>

            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        <?php endif; ?>
    </div>

</body>
</html>
