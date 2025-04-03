<?php
$servername = "localhost"; // or 127.0.0.1
$username = "root"; // Default XAMPP username
$password = ""; // Default is empty for XAMPP
$database = "ebook"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
