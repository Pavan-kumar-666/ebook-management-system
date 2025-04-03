<?php
include "config.php"; // Database connection
session_start();

$user_id = $_SESSION['user_id'];

// Fetch wallet balance
$sql_wallet = "SELECT wallet_balance FROM users WHERE id = '$user_id'";
$result_wallet = $conn->query($sql_wallet);
$user = $result_wallet->fetch_assoc();
$wallet_balance = number_format($user['wallet_balance'], 2);

// Fetch books from the database
$sql_books = "SELECT * FROM books";
$result_books = $conn->query($sql_books);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - E-book Management System</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleSidebar() {
            let sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("open");
        }

        function searchBooks() {
            let input = document.getElementById("searchBar").value.toLowerCase();
            let books = document.getElementsByClassName("book-item");
            for (let i = 0; i < books.length; i++) {
                let title = books[i].getElementsByTagName("h3")[0].innerText.toLowerCase();
                books[i].style.display = title.includes(input) ? "block" : "none";
            }
        }
    </script>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            right: -250px;
            top: 0;
            background: #222;
            padding-top: 20px;
            transition: 0.3s ease-in-out;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
        }
        .sidebar.open {
            right: 0;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 15px;
            font-size: 18px;
            color: white;
            text-decoration: none;
            transition: 0.3s;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar a:hover {
            background: #444;
        }

        /* Wallet Display */
        .wallet {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #ffcc00;
            color: black;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        /* Menu Button */
        .menu-btn {
            font-size: 25px;
            background: #ff5722;
            color: white;
            border: none;
            padding: 12px 15px;
            cursor: pointer;
            position: fixed;
            top: 20px;
            right: 20px;
            border-radius: 5px;
            z-index: 1000;
        }

        /* Main Content */
        .content {
            margin: 20px;
            padding: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }

        /* Search Bar */
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        #searchBar {
            width: 50%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #007bff;
            border-radius: 5px;
        }

        /* Books Grid */
        .books-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* Book Item */
        .book-item {
            width: 250px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 15px;
            transition: 0.3s;
        }
        .book-item:hover {
            transform: translateY(-5px);
        }
        .book-cover {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
        }
        .btn {
            display: block;
            padding: 10px;
            margin: 5px 0;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Wallet Balance Display -->
    <div class="wallet">
        <i class="fas fa-wallet"></i> Wallet: ₹<?php echo $wallet_balance; ?>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
        <a href="mybooks.php"><i class="fas fa-book"></i> My Books</a>
        <a href="mycart.php"><i class="fas fa-shopping-cart"></i> My Cart</a>
        <a href="favourites.php"><i class="fas fa-heart"></i> Favourites</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Menu Button -->
    <button class="menu-btn" onclick="toggleSidebar()">☰</button>

    <!-- Main Content -->
    <div class="content">
        <h2>📚 Welcome to Your Dashboard</h2>

        <!-- Search Bar -->
        <div class="search-container">
            <input type="text" id="searchBar" placeholder="Search books..." onkeyup="searchBooks()">
        </div>

        <!-- Book Listings -->
        <div class="books-container">
            <?php if ($result_books->num_rows > 0): ?>
                <?php while ($row = $result_books->fetch_assoc()): ?>
                    <div class="book-item">
                        <img src="uploads/<?php echo !empty($row['cover_image']) ? $row['cover_image'] : 'default_cover.jpg'; ?>" 
                             alt="Book Cover" class="book-cover">
                        <h3><?php echo $row['title']; ?></h3>
                        <p>by <?php echo $row['author']; ?></p>
                        <p><?php echo $row['description']; ?></p>
                        <p><strong>Price:</strong> ₹<?php echo number_format($row['price'], 2); ?></p>
                        
                        <a href="view_details.php?book=<?php echo $row['id']; ?>" class="btn"><i class="fas fa-info-circle"></i> View Details</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No books available.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
