<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Categories</title>
</head>

<body class="categories-page">

    <nav class="categories-navbar">
        <div class="categories-logo">
             <a href="dashboard.php"><img src="logo.png" alt="Buff Budgets Logo"></a>
        </div>
        <ul class="categories-nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="account.php">Account</a></li>
            <li><a href="expenses.php">Expenses</a></li>
            <li><a href="budgets.php">Budgets</a></li>
            <li><a href="income.php">Income</a></li>
            <li><a href="monthly_summary.php">Monthly Summary</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="logout.php">Logout</a></li>

        </ul>
    </nav>

    <header class="categories-hero-banner hero-banner">
        <div class="categories-hero-text hero-text">
            <h1>Categories</h1>
            <p class="categories-subtitle subtitle">
                Manage your income categories
            </p>
        </div>
    </header>

    <main class="categories-main">
        <h2 class="categories-heading">Welcome Back, User!</h2>


            </div>
</main>


    <footer class="categories-footer">
        <div class="categories-footer-container footer-container">
            <div class="categories-footer-column footer-column">
                <img src="logo.png" alt="Buff Budgets Logo" class="footer-logo">
                <p>© 2026 Buff Budgets. All rights reserved.</p>
            </div>
            <div class="categories-footer-column footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="account.php">Account</a></li>
                    <li><a href="expenses.php">Expenses</a></li>
                    <li><a href="budgets.php">Budgets</a></li>
                </ul>
            </div>
            <div class="categories-footer-column footer-column">
                <h4>Contact Us</h4>
                <p>Tel: (01321) 2340 235</p>
                <p>Fax: (01321) 2340 236</p>
                <p>Email: <a href="mailto:info@buffbudgets.com">info@buffbudgets.com</a></p>
            </div>
        </div>
    </footer>

    <script src="3Javascript/script.js"></script>

</body>

</html>
