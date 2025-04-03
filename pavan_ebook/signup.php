<?php
include "config.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

    // Check if the email already exists
    $check_query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already exists! Please use a different email.'); window.location.href='signup.html';</script>";
        exit();
    }

    // Insert new user into database
    $insert_query = "INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssss", $full_name, $email, $phone, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup Successful! You can now log in.'); window.location.href='login_user.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
