<?php
include "config.php"; // Database connection
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];

    // Update the user's details in the database
    $sql = "UPDATE users SET full_name = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $full_name, $phone, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile!'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
