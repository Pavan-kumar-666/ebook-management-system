<?php
session_start();
include("db_connect.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle Cover Image Upload
    $cover_image = "";
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $cover_image = basename($_FILES["cover_image"]["name"]); // Store only filename
        move_uploaded_file($_FILES["cover_image"]["tmp_name"], "uploads/" . $cover_image);
    }

    // Handle PDF Upload
    $pdf = "";
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        $pdf = basename($_FILES["pdf"]["name"]); // Store only filename
        move_uploaded_file($_FILES["pdf"]["tmp_name"], "uploads/" . $pdf);
    }

    // Insert into Database
    $query = "INSERT INTO books (title, cover_image, author, description, price, pdf) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssdss", $title, $cover_image, $author, $description, $price, $pdf);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?success=Book added successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            width: 400px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-container h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, .form-group textarea {
            width: 95%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group input[type="file"] {
            border: none;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Add New Book</h2>
        <form action="add_book.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Book Title</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div class="form-group">
                <label for="cover_image">Cover Image</label>
                <input type="file" name="cover_image" id="cover_image" required>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price (₹)</label>
                <input type="number" name="price" id="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="pdf">PDF File</label>
                <input type="file" name="pdf" id="pdf" required>
            </div>
            <button type="submit" class="submit-btn">Add Book</button>
        </form>
    </div>

</body>
</html>
