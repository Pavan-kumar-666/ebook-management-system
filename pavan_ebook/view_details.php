<?php
include "config.php"; // Database connection
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view book details.'); window.location.href='login_user.html';</script>";
    exit;
}

// Ensure a valid book ID is provided
if (!isset($_GET['book']) || !is_numeric($_GET['book'])) {
    echo "<script>alert('Invalid book selection.'); window.location.href='index.php';</script>";
    exit;
}

$book_id = intval($_GET['book']); // Convert to integer for safety
$user_id = $_SESSION['user_id'];

// Fetch book details
$sql = $conn->prepare("SELECT * FROM books WHERE id = ?");
$sql->bind_param("i", $book_id);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Book not found.'); window.location.href='index.php';</script>";
    exit;
}

$book = $result->fetch_assoc();

// Check if book is already in favorites
$fav_check = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND book_id = ?");
$fav_check->bind_param("ii", $user_id, $book_id);
$fav_check->execute();
$is_favorited = $fav_check->get_result()->num_rows > 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - View Details</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function showPopup(message) {
            let popup = document.getElementById("popup");
            popup.innerText = message;
            popup.style.display = "block";
            setTimeout(() => { popup.style.display = "none"; }, 2000);
        }

        function addToCart(bookId) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "add_to_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                showPopup(xhr.responseText.trim() === "success" ? "📥 Added to Cart!" : xhr.responseText);
            };
            xhr.send("book_id=" + encodeURIComponent(bookId));
        }

        function addToFavorites(bookId) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "add_to_favorites.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                showPopup(xhr.responseText.trim() === "success" ? "⭐ Added to Favorites!" : xhr.responseText);
                if (xhr.responseText.trim() === "success") {
                    document.getElementById("fav-btn").innerText = "✅ Already in Favorites";
                    document.getElementById("fav-btn").disabled = true;
                }
            };
            xhr.send("book_id=" + encodeURIComponent(bookId));
        }
    </script>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            width: 90%;
            max-width: 800px;
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .book-cover {
            width: 250px;
            height: 350px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.2);
        }

        .book-title {
            font-size: 26px;
            font-weight: bold;
            margin-top: 10px;
        }

        .book-author {
            font-size: 18px;
            font-style: italic;
            color: #555;
        }

        .book-price {
            font-size: 22px;
            color: #e74c3c;
            font-weight: bold;
            margin: 10px 0;
        }

        .description {
            font-size: 16px;
            padding: 10px;
            line-height: 1.5;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            color: white;
        }

        .cart-btn {
            background: #007bff;
        }

        .fav-btn {
            background: #ff9800;
        }

        #popup {
            display: none;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 14px 25px;
            border-radius: 6px;
            font-size: 18px;
            z-index: 1000;
        }
    </style>
</head>
<body>

    <div class="container">
        <img src="uploads/<?php echo !empty($book['cover_image']) ? htmlspecialchars($book['cover_image']) : 'default_cover.jpg'; ?>" alt="Book Cover" class="book-cover">
        <h2 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h2>
        <p class="book-author">✍️ Author: <?php echo htmlspecialchars($book['author']); ?></p>
        <p class="book-price">💰 Price: ₹<?php echo number_format($book['price'], 2); ?></p>
        <p class="description"><?php echo htmlspecialchars($book['description']); ?></p>

        <div class="buttons">
            <button class="btn cart-btn" onclick="addToCart(<?php echo $book['id']; ?>)">🛒 Add to Cart</button>
            <button class="btn fav-btn" id="fav-btn" onclick="addToFavorites(<?php echo $book['id']; ?>)" 
                <?php if ($is_favorited) echo 'disabled'; ?>>
                <?php echo $is_favorited ? "✅ Already in Favorites" : "⭐ Add to Favorites"; ?>
            </button>
        </div>
    </div>

    <div id="popup"></div>

</body>
</html>
