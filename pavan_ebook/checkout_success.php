<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout Success</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .success-container {
            text-align: center;
            padding: 50px;
            background-color: #f3f3f3;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: 50px auto;
        }
        .success-icon {
            font-size: 50px;
            color: green;
        }
        .success-text {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .success-msg {
            font-size: 18px;
            color: #555;
            margin-top: 10px;
        }
        .success-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .success-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="success-container">
        <div class="success-icon">✅</div>
        <p class="success-text">Purchase Successful!</p>
        <p class="success-msg">Thank you for your purchase. Your books are now available in <a href="mybooks.php">My Books</a>.</p>
        <a href="mybooks.php" class="success-btn">Go to My Books</a>
    </div>

</body>
</html>
