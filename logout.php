<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="logout-page">

    <nav class="navbar">
        <div class="logo">
            <a href="dashboard.php"><img src="logo.png" alt="Buff Budgets Logo"></a>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="account.php">Account</a></li>
            <li><a href="expenses.php">Expenses</a></li>
        </ul>
    </nav>

    <main class="hero-banner">
        <div class="hero-text">
            <h1>Are you sure you want to logout?</h1>
            <p class="subtitle">Click the button below to safely logout.</p>
            <form method="post">
                <button type="submit">Logout</button>
            </form>
        </div>
    </main>

</body>
</html>