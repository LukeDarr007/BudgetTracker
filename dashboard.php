<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Buff Budgets</title>
</head>

<body class="dashboard-page">

    <nav class="dashboard-navbar">
        <div class="dashboard-logo">
             <a href="dashboard.php"><img src="logo.png" alt="Buff Budgets Logo"></a>
        </div>
        <ul class="dashboard-nav-links">
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

    <header class="dashboard-hero-banner hero-banner">
        <div class="dashboard-hero-text hero-text">
            <h1>Dashboard</h1>
            <p class="dashboard-subtitle subtitle">
                Dashboard
            </p>
        </div>
    </header>

    <main class="dashboard-main">
        <h2 class="dashboard-heading">Welcome Back, User!</h2>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Buff Budgets</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="logout.php">Logout</a>
</nav>

<header>
    <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
</header>

<section class="summary">
    <div class="card">
        <h3>Total Budget</h3>
        <p>$<?php echo number_format($total_budget, 2); ?></p>
    </div>
    <div class="card">
        <h3>Total Expenses</h3>
        <p>$<?php echo number_format($total_expenses, 2); ?></p>
    </div>
    <div class="card">
        <h3>Remaining</h3>
        <p>$<?php echo number_format($total_budget - $total_expenses, 2); ?></p>
    </div>
</section>

<section class="transactions">
    <h2>Recent Transactions</h2>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($transactions as $t): ?>
            <tr>
                <td><?php echo htmlspecialchars($t['description']); ?></td>
                <td>$<?php echo number_format($t['amount'], 2); ?></td>
                <td><?php echo $t['date']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<section class="actions">
    <a href="add_budget.php">Add Budget</a>
    <a href="add_expense.php">Add Expense</a>
</section>

</body>
</html>

            </div>
</main>


    <footer class="dashboard-footer">
        <div class="dashboard-footer-container footer-container">
            <div class="dashboard-footer-column footer-column">
                <img src="logo.png" alt="Buff Budgets Logo" class="footer-logo">
                <p>© 2026 Buff Budgets. All rights reserved.</p>
            </div>
            <div class="dashboard-footer-column footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.html">Dashboard</a></li>
                    <li><a href="account.html">Account</a></li>
                    <li><a href="expenses.html">Expenses</a></li>
                    <li><a href="budgets.html">Budgets</a></li>
                </ul>
            </div>
            <div class="dashboard-footer-column footer-column">
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
