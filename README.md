E-Book Management System 📚💻
About the Project :
The E-Book Management System is a web-based platform that allows users to browse, purchase, and manage e-books. The system includes user and admin roles, with separate functionalities for each.

Features
✅ User Features
🔹 Signup & Login – Users can register and log in.
🔹 Browse Books – View available e-books with cover images, descriptions, and prices.
🔹 Add to Cart – Users can add books to their cart and purchase them.
🔹 Favorites – Users can save books as favorites.
🔹 My Books – Purchased books are stored in the user’s library for future access.
🔹 Wallet System – Users can check their balance before purchasing.

🔥 Cart Features
🛒 Increase/Decrease Quantity – Users can adjust book quantity using + and - buttons.
❌ Remove from Cart – Books can be removed from the cart.
💳 Proceed to Checkout – Users can finalize their purchase.

✅ Admin Features
🔹 Login – Secure admin login (Admins are manually added to the database).
🔹 Add New Books – Upload e-books with title, author, cover image, description, price, and PDF.
🔹 Manage Books – Update and delete books from the system.

Technology Stack
Frontend: HTML, CSS
Backend: PHP
Database: MySQL (via phpMyAdmin)
Version Control: Git & GitHub
Development Environment: XAMPP, VS Code


Installation & Setup
1️⃣ Clone the Repository
git clone https://github.com/Pavan-kumar-666/ebook-management-system.git

Move to the project directory:
cd ebook-management-system


2️⃣ Import Database
1️⃣ Open phpMyAdmin (http://localhost/phpmyadmin)
2️⃣ Create a database named ebook
3️⃣ Click on Import, choose ebook.sql, and click Go

3️⃣ Start XAMPP & Run the Project
1️⃣ Start Apache and MySQL in XAMPP
2️⃣ Open browser and go to:
http://localhost/pavan_ebook/
3️⃣ Done! 🚀

Folder Structure :
pavan_ebook/
│── uploads/               # Book cover images & PDFs
│── db_connect.php         # Database connection file
│── index.php              # Home page
│── login_user.php         # User login page
│── login_admin.php        # Admin login page
│── signup.php             # User signup page
│── admin_dashboard.php    # Admin panel
│── add_book.php           # Admin - Add books
│── mycart.php             # User cart
│── mybooks.php            # User purchased books
│── favourites.php         # User favorite books
│── checkout.php           # Checkout process
│── ebook.sql              # Database file (import in phpMyAdmin)
│── README.md              # Project documentation


Contributors
👨‍💻 Developed by: Pavan Kumar

Future Improvements
✅ Add payment integration
✅ Implement book reviews & ratings
✅ Improve search & filtering

License
📜 This project is open-source and free to use.

🎉 Happy Coding! 🚀

