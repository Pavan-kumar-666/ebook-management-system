<?php
include "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin["password"])) {
            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_name"] = $admin["full_name"];
            header("Location: admin_dashboard.php"); // Redirect to admin panel
            exit();
        } else {
            echo "<script>alert('Invalid Password!'); window.location.href='login_admin.html';</script>";
        }
    } else {
        echo "<script>alert('Admin not found!'); window.location.href='login_admin.html';</script>";
    }
}
?>
