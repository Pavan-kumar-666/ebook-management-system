<?php
include "config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Login required";
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'];

$conn->query("DELETE FROM favorites WHERE user_id = $user_id AND book_id = $book_id");

echo "success";
?>
