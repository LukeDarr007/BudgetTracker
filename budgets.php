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
    <meta charset="UTF-8"><nav class="budgets-navbar">
        <div class="budgets-logo">
             <a href="dashboard.php"><img src="logo.png" alt="Buff Budgets Logo"></a>
        </div>
        <ul class="budgets-nav-links">
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Budgets</title>
</head>

<body class="budgets-page">

    

    <header class="budgets-hero-banner hero-banner">
        <div class="budgets-hero-text hero-text">
            <h1>Budgets</h1>
            <p class="budgets-subtitle subtitle">
                Manage your budgets
            </p>
        </div>
    </header>

    <main class="budgets-main">
        <h2 class="budgets-heading">Welcome Back, User!</h2>


            </div>
</main>


    <footer class="budgets-footer">
        <div class="budgets-footer-container footer-container">
            <div class="budgets-footer-column footer-column">
                <img src="logo.png" alt="Buff Budgets Logo" class="footer-logo">
                <p>© 2026 Buff Budgets. All rights reserved.</p>
            </div>
            <div class="budgets-footer-column footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="account.php">Account</a></li>
                    <li><a href="expenses.php">Expenses</a></li>
                    <li><a href="budgets.php">Budgets</a></li>
                </ul>
            </div>
            <div class="budgets-footer-column footer-column">
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
