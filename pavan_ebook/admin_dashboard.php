<?php
// Start session
session_start();
include("db_connect.php"); // Database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

// Fetch total books count
$query_count = "SELECT COUNT(*) AS total_books FROM books";
$result_count = $conn->query($query_count);
$row_count = $result_count->fetch_assoc();
$total_books = $row_count['total_books'];

// Fetch all books
$query_books = "SELECT id, title, author FROM books";
$result_books = $conn->query($query_books);

// Handle book deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Book deleted successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to delete book.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - E-book Management System</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleSidebar() {
            let sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("active");
        }
    </script>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f7fa;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 20px;
            transition: 0.3s;
        }
        .sidebar a {
            display: block;
            padding: 15px;
            color: white;
            text-decoration: none;
            font-size: 18px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #495057;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
            text-align: center;
        }

        /* Dashboard Stats */
        .dashboard-cards {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .card {
            width: 30%;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card h2 {
            color: #007bff;
        }

        /* Table */
        .book-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        .book-table th, .book-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        .book-table th {
            background: #007bff;
            color: white;
        }
        .book-table tr:hover {
            background: #f1f1f1;
        }

        /* Add Book Button */
        .add-book-btn {
            padding: 12px 20px;
            background: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin: 15px 0;
            font-size: 16px;
            transition: 0.3s;
        }
        .add-book-btn:hover {
            background: #218838;
        }

        /* Delete Button */
        .delete-btn {
            padding: 6px 10px;
            background: #dc3545;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }
        .delete-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="admin_dashboard.php">🏠 Dashboard</a>
        <a href="admin_profile.php">⚙️ Profile</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Admin Dashboard</h1>

        <!-- Stats Section -->
        <div class="dashboard-cards">
            <div class="card">
                <h2>📚 <?php echo $total_books; ?></h2>
                <p>Total Books</p>
            </div>
        </div>

        <!-- Add Book Button -->
        <a href="add_book.php" class="add-book-btn">➕ Add New Book</a>

        <!-- Book Table -->
        <table class="book-table">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Actions</th>
            </tr>
            <?php if ($result_books->num_rows > 0): ?>
                <?php while ($book = $result_books->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $book['id']; ?></td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td>
                            <a href="admin_dashboard.php?delete_id=<?php echo $book['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">No books available</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

</body>
</html>

<?php
$conn->close();
?>
