<?php
// Start session
session_start();
include("db_connect.php"); // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

// Fetch admin details
$admin_id = $_SESSION['admin_id'];
$query = "SELECT full_name, email FROM admins WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            width: 350px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile-container h2 {
            color: #007bff;
        }

        .profile-info {
            margin-top: 15px;
            font-size: 18px;
            color: #333;
        }

        .logout-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .logout-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <h2>Admin Profile</h2>

        <?php if ($admin): ?>
            <div class="profile-info">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($admin['full_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
            </div>
            <a href="logout.php" class="logout-btn">Logout</a>
        <?php else: ?>
            <p>No admin details found.</p>
        <?php endif; ?>

    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
