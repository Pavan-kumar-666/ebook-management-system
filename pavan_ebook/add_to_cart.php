<?php
include "config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add to cart!");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['book_id'])) {
    die("Invalid request!");
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id']);

// Check if the book exists
$check_book = $conn->prepare("SELECT id FROM books WHERE id = ?");
$check_book->bind_param("i", $book_id);
$check_book->execute();
$check_book->store_result();

if ($check_book->num_rows == 0) {
    die("Book not found!");
}

// Check if already in cart
$check_cart = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND book_id = ?");
$check_cart->bind_param("ii", $user_id, $book_id);
$check_cart->execute();
$check_cart->store_result();

if ($check_cart->num_rows > 0) {
    die("Already in Cart!");
}

// Insert into cart
$insert_cart = $conn->prepare("INSERT INTO cart (user_id, book_id) VALUES (?, ?)");
$insert_cart->bind_param("ii", $user_id, $book_id);

if ($insert_cart->execute()) {
    echo "success";
} else {
    echo "Error adding to cart!";
}

$conn->close();
?>
