<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">

<div class="nav-container">

    <?php if (empty($_SESSION['user_id'])): ?>

        <div class="logo">
            <a href="index.php">
                <img src="logo.png" alt="Buff Budgets Logo">
            </a>
        </div>

        <button class="hamburger" onclick="toggleMenu()">☰</button>

        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="contact.php">Contact Us</a></li>
        </ul>

    <?php else: ?>

        <div class="logo">
            <a href="dashboard.php">
                <img src="logo.png" alt="Logo">
            </a>
        </div>

        <button class="hamburger" onclick="toggleMenu()">☰</button>

        <ul class="nav-links" id="navLinks">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="account.php">Account</a></li>
            <li><a href="expenses.php">Expenses</a></li>
            <li><a href="income.php">Income</a></li>
            <li><a href="budgets.php">Budgets</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="monthly_summary.php">Monthly Summary</a></li>
        </ul>

    <?php endif; ?>

</div>

</nav>

<script>
function toggleMenu() {
    document.getElementById("navLinks").classList.toggle("active");
}
</script>