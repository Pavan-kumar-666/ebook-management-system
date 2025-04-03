<?php
include "config.php";
session_start();

$user_id = $_SESSION['user_id'];

// Fetch user's wallet balance
$sql_wallet = "SELECT wallet_balance FROM users WHERE id = '$user_id'";
$result_wallet = $conn->query($sql_wallet);
$user = $result_wallet->fetch_assoc();
$wallet_balance = $user['wallet_balance'];

// Fetch cart items and total price
$sql_cart = "SELECT b.id, b.price FROM cart c JOIN books b ON c.book_id = b.id WHERE c.user_id = '$user_id'";
$result_cart = $conn->query($sql_cart);

$total_price = 0;
$books_to_buy = [];

while ($row = $result_cart->fetch_assoc()) {
    $total_price += $row['price'];
    $books_to_buy[] = $row['id'];
}

// Apply 5% tax
$tax = 0.05 * $total_price;
$grand_total = $total_price + $tax;

// Check balance
if ($wallet_balance >= $grand_total) {
    // Deduct balance
    $new_balance = $wallet_balance - $grand_total;
    $conn->query("UPDATE users SET wallet_balance = '$new_balance' WHERE id = '$user_id'");

    // Move books from cart to my_books
    foreach ($books_to_buy as $book_id) {
        $conn->query("INSERT INTO my_books (user_id, book_id) VALUES ('$user_id', '$book_id')");
    }

    // Clear the cart
    $conn->query("DELETE FROM cart WHERE user_id = '$user_id'");

    // Redirect to success page
    header("Location: checkout_success.php");
    exit();
} else {
    // Redirect back with error
    header("Location: mycart.php?error=insufficient_balance");
    exit();
}
?>
