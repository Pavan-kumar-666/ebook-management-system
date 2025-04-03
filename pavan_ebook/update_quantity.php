<?php
include "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];
    $action = $_POST['action']; // 'increase' or 'decrease'

    // Get current quantity
    $sql = "SELECT quantity FROM cart WHERE user_id = '$user_id' AND book_id = '$book_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row) {
        $current_quantity = $row['quantity'];

        if ($action == "increase") {
            $new_quantity = $current_quantity + 1;
        } elseif ($action == "decrease" && $current_quantity > 1) {
            $new_quantity = $current_quantity - 1;
        } else {
            // If quantity is 1 and "decrease" is clicked, remove from cart
            $delete_sql = "DELETE FROM cart WHERE user_id = '$user_id' AND book_id = '$book_id'";
            $conn->query($delete_sql);
            header("Location: mycart.php");
            exit();
        }

        // Update quantity in database
        $update_sql = "UPDATE cart SET quantity = '$new_quantity' WHERE user_id = '$user_id' AND book_id = '$book_id'";
        $conn->query($update_sql);
    }
}

header("Location: mycart.php");
exit();
?>
