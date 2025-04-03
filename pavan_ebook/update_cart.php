<?php
include "config.php";
session_start();

$user_id = $_SESSION['user_id'];
$book_id = $_GET['book'];
$action = $_GET['action'];

if ($action == "increase") {
    $sql = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = '$user_id' AND book_id = '$book_id'";
} elseif ($action == "decrease") {
    $sql = "UPDATE cart SET quantity = quantity - 1 WHERE user_id = '$user_id' AND book_id = '$book_id' AND quantity > 1";
}

$conn->query($sql);
?>
