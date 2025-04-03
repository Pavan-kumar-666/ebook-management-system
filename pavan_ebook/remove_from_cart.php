<?php
include "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];

    $sql = "DELETE FROM cart WHERE user_id = '$user_id' AND book_id = '$book_id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: mycart.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
