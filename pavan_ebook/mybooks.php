<?php
include "config.php";
session_start();

$user_id = $_SESSION['user_id'];

// Fetch purchased books
$sql = "SELECT b.* FROM my_books mb JOIN books b ON mb.book_id = b.id WHERE mb.user_id = '$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Books</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>📖 My Purchased Books</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="books-container">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-item">
                        <img src="uploads/<?php echo $row['cover_image']; ?>">
                        <h3><?php echo $row['title']; ?></h3>
                        <a href="uploads/<?php echo $row['pdf']; ?>" target="_blank" class="btn">📄 View PDF</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>You haven't purchased any books yet. <a href="user_dashboard.php">Browse books</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
