<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');

if ($year < 2000) $year = 2000;
if ($year > 2026) $year = 2026;
if ($month < 1) $month = 1;
if ($month > 12) $month = 12;

$stmt = $conn->prepare("SELECT SUM(amount) as total_expenses FROM expense WHERE user_id=? AND YEAR(date)=? AND MONTH(date)=?");
$stmt->bind_param("sii", $user_id, $year, $month);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$total_expenses = $res['total_expenses'] ?? 0;

$stmt = $conn->prepare("SELECT SUM(amount) as total_income FROM income WHERE user_id=? AND YEAR(date)=? AND MONTH(date)=?");
$stmt->bind_param("sii", $user_id, $year, $month);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$total_income = $res['total_income'] ?? 0;

$balance = $total_income - $total_expenses;

$prev_month = $month - 1;
$prev_year = $year;
if ($prev_month < 1) {
    $prev_month = 12;
    $prev_year--;
}

$next_month = $month + 1;
$next_year = $year;
if ($next_month > 12) {
    $next_month = 1;
    $next_year++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Monthly Summary</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="monthly-summary-page">

<?php include 'navbar.php'; ?>

<header class="monthly-summary-hero-banner hero-banner">
    <div class="monthly-summary-hero-text hero-text">
        <h1>Monthly Summary</h1>
        <p class="monthly-summary-subtitle subtitle">View your monthly financial summary</p>
    </div>
</header>

<main class="monthly-summary-main">

<h2 class="monthly-summary-heading">
<?php echo date("F Y", mktime(0,0,0,$month,1,$year)); ?>
</h2>

<div class="monthly-summary-controls">
<a href="?month=<?php echo $prev_month; ?>&year=<?php echo $prev_year; ?>" class="btn">Prev</a>
<a href="?month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>" class="btn">Next</a>
</div>

<div class="monthly-summary-content">

<div class="monthly-summary-chart">
<canvas id="summaryChart"></canvas>
</div>

<div class="monthly-summary-stats">
<p>Total Income: £<?php echo number_format($total_income,2); ?></p>
<p>Total Expenses: £<?php echo number_format($total_expenses,2); ?></p>
<p>Remaining Balance: £<?php echo number_format($balance,2); ?></p>
</div>

</div>

</main>

<footer class="index-footer">
    <div class="index-footer-container">
        <div class="index-footer-column">
            <img src="logo.png" class="footer-logo">
            <p>© 2026 Buff Budgets. All rights reserved.</p>
        </div>

        <div class="index-footer-column">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="account.php">Account</a></li>
                <li><a href="expenses.php">Expenses</a></li>
                <li><a href="income.php">Income</a></li>
                <li><a href="budgets.php">Budgets</a></li>
            </ul>
        </div>

        <div class="index-footer-column">
            <h4>Contact Us</h4>
            <p>Tel: (01321) 2340 235</p>
            <p>Email: info@buffbudgets.com</p>
        </div>
    </div>
</footer>

<script>
const ctx = document.getElementById('summaryChart').getContext('2d');

new Chart(ctx, {
type: 'pie',
data: {
labels: ['Income', 'Expenses'],
datasets: [{
data: [<?php echo $total_income; ?>, <?php echo $total_expenses; ?>],
backgroundColor: ['#4CAF50', '#F44336']
}]
},
options: {
responsive: true,
plugins: {
legend: {
position: 'bottom'
}
}
}
});
</script>

<script src="3Javascript/script.js"></script>

</body>
</html>