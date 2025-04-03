<?php
include "config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Please log in first!";
    exit;
}

if (!isset($_POST['book_id']) || !is_numeric($_POST['book_id'])) {
    echo "Invalid book selection!";
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id']);

// Check if the book is already in favorites
$check_fav = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND book_id = ?");
$check_fav->bind_param("ii", $user_id, $book_id);
$check_fav->execute();
$result = $check_fav->get_result();

if ($result->num_rows > 0) {
    echo "This book is already in your favorites!";
    exit;
}

// Add to favorites
$insert_fav = $conn->prepare("INSERT INTO favorites (user_id, book_id) VALUES (?, ?)");
$insert_fav->bind_param("ii", $user_id, $book_id);

if ($insert_fav->execute()) {
    echo "success";  // JavaScript will show a success message
} else {
    echo "Error adding to favorites!";
}
?>
