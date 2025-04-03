<?php
include "config.php";
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view favorites.'); window.location.href='login_user.html';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch favorite books
$sql = $conn->prepare("SELECT books.id, books.title, books.author, books.price, books.cover_image FROM favorites 
                       JOIN books ON favorites.book_id = books.id WHERE favorites.user_id = ?");
$sql->bind_param("i", $user_id);
$sql->execute();
$result = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function showPopup(message) {
            let popup = document.getElementById("popup");
            popup.innerText = message;
            popup.style.display = "block";
            setTimeout(() => { popup.style.display = "none"; }, 2000);
        }

        function moveToCart(bookId) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "move_to_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    showPopup(xhr.responseText.trim() === "success" ? "📥 Moved to Cart!" : xhr.responseText);
                    setTimeout(() => { location.reload(); }, 1000);
                } else {
                    showPopup("❌ Failed to move!");
                }
            };
            xhr.send("book_id=" + encodeURIComponent(bookId));
        }

        function removeFromFavorites(bookId) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "remove_from_favorites.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    showPopup(xhr.responseText.trim() === "success" ? "❌ Removed from Favorites!" : xhr.responseText);
                    setTimeout(() => { location.reload(); }, 1000);
                } else {
                    showPopup("❌ Failed to remove!");
                }
            };
            xhr.send("book_id=" + encodeURIComponent(bookId));
        }
    </script>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        
        .container {
            width: 90%;
            max-width: 900px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .book-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .book-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.2);
            padding: 15px;
            text-align: center;
            width: 200px;
        }

        .book-cover {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
        }

        .book-title {
            font-size: 18px;
            font-weight: bold;
            color: #222;
            margin-top: 10px;
        }

        .book-author {
            font-size: 14px;
            color: #777;
        }

        .book-price {
            font-size: 16px;
            color: #e74c3c;
            font-weight: bold;
            margin: 8px 0;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            gap: 5px;
            margin-top: 10px;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        .cart-btn {
            background: #007bff;
        }

        .cart-btn:hover {
            background: #0056b3;
        }

        .remove-btn {
            background: #e74c3c;
        }

        .remove-btn:hover {
            background: #c0392b;
        }

        .popup {
            display: none;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 14px 25px;
            border-radius: 6px;
            font-size: 16px;
            z-index: 1000;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>⭐ Your Favorite Books</h2>
        <div class="book-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($book = $result->fetch_assoc()): ?>
                    <div class="book-card">
                        <img src="uploads/<?php echo !empty($book['cover_image']) ? htmlspecialchars($book['cover_image']) : 'default_cover.jpg'; ?>" alt="Book Cover" class="book-cover">
                        <p class="book-title"><?php echo htmlspecialchars($book['title']); ?></p>
                        <p class="book-author">✍️ <?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="book-price">💰 ₹<?php echo number_format($book['price'], 2); ?></p>
                        <div class="buttons">
                            <button class="btn cart-btn" onclick="moveToCart(<?php echo $book['id']; ?>)">🛒 Move to Cart</button>
                            <button class="btn remove-btn" onclick="removeFromFavorites(<?php echo $book['id']; ?>)">❌ Remove</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No favorites added yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div id="popup" class="popup"></div>

</body>
</html>
