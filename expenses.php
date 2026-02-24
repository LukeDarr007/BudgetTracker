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
    <title>Expenses</title>
</head>

<body class="expenses-page">

    <nav class="expenses-navbar">
        <div class="expenses-logo">
             <a href="dashboard.php"><img src="logo.png" alt="Buff Budgets Logo"></a>
        </div>
        <ul class="expenses-nav-links">
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

    <header class="expenses-hero-banner hero-banner">
        <div class="expenses-hero-text hero-text">
            <h1>Expenses</h1>
            <p class="expenses-subtitle subtitle">
                Manage your expenses
            </p>
        </div>
    </header>

    <main class="expenses-main">
        <h2 class="expenses-heading">Welcome Back, User!</h2>


            </div>
</main>


    <footer class="expenses-footer">
        <div class="expenses-footer-container footer-container">
            <div class="expenses-footer-column footer-column">
                <img src="logo.png" alt="Buff Budgets Logo" class="footer-logo">
                <p>© 2026 Buff Budgets. All rights reserved.</p>
            </div>
            <div class="expenses-footer-column footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="account.php">Account</a></li>
                    <li><a href="expenses.php">Expenses</a></li>
                    <li><a href="budgets.php">Budgets</a></li>
                </ul>
            </div>
            <div class="expenses-footer-column footer-column">
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
