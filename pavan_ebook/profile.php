<?php
include "config.php"; // Database connection
session_start();

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT full_name, email, phone FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - E-book Management System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            text-align: center;
        }
        .profile-container {
            width: 50%;
            margin: 100px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h2 {
            color: #333;
        }
        .info {
            font-size: 18px;
            margin: 10px 0;
        }
        .edit-btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .edit-btn:hover {
            background: #0056b3;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background: white;
            padding: 20px;
            width: 30%;
            margin: 15% auto;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .modal input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .modal button {
            padding: 10px 15px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .save-btn {
            background: #28a745;
            color: white;
        }
        .save-btn:hover {
            background: #218838;
        }
        .close-btn {
            background: red;
            color: white;
        }
        .close-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <h2>👤 My Profile</h2>
        <p class="info"><strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
        <p class="info"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p class="info"><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        <button class="edit-btn" onclick="openModal()">✏️ Edit Profile</button>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <h3>Edit Profile</h3>
            <form action="update_profile.php" method="POST">
                <input type="text" name="full_name" placeholder="Full Name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                <input type="text" name="phone" placeholder="Phone Number" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <button type="submit" class="save-btn">✅ Save Changes</button>
                <button type="button" class="close-btn" onclick="closeModal()">❌ Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('editProfileModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editProfileModal').style.display = 'none';
        }
    </script>

</body>
</html>
