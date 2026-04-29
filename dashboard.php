<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$first_name = $_SESSION['first_name'] ?? "User";
$last_name = $_SESSION['last_name'] ?? "";

$stmt = $conn->prepare("SELECT SUM(amount) AS total FROM income WHERE user_id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$total_income = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

$stmt = $conn->prepare("SELECT SUM(amount) AS total FROM expense WHERE user_id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$total_expenses = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

$incomes = [];
$stmt = $conn->prepare("SELECT income_id, description, amount, date FROM income WHERE user_id=? ORDER BY date DESC LIMIT 5");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $incomes[] = $row;
}

$expenses = [];
$stmt = $conn->prepare("SELECT expense_id, description, amount, date FROM expense WHERE user_id=? ORDER BY date DESC LIMIT 5");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $expenses[] = $row;
}

$current_year = date('Y');
$current_month = date('n');

$stmt = $conn->prepare("SELECT target_amount FROM budgets WHERE user_id=? AND year=? AND month=?");
$stmt->bind_param("sii", $user_id, $current_year, $current_month);
$stmt->execute();
$this_month_budget = $stmt->get_result()->fetch_assoc()['target_amount'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="dashboard-page">

<?php include 'navbar.php'; ?>

<header class="dashboard-hero-banner">
<h1>Welcome, <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h1>
</header>

<main class="dashboard-main">

<div class="dashboard-container">

<div class="chart-box">
<canvas id="financeChart"></canvas>
</div>

<div class="dashboard-content">

<h2>Financial Overview</h2>

<div class="summary-grid">

<div class="card">
<h3>Total Income</h3>
<p>£<?php echo number_format($total_income,2); ?></p>
</div>

<div class="card">
<h3>Total Expenses</h3>
<p>£<?php echo number_format($total_expenses,2); ?></p>
</div>

<div class="card">
<h3>Remaining Balance</h3>
<p>£<?php echo number_format($total_income - $total_expenses,2); ?></p>
</div>

<div class="card">
<h3>This Month's Financial Target</h3>
<p>£<?php echo number_format($this_month_budget,2); ?></p>
</div>

</div>

<h2>Recent Income</h2>
<table class="table">
<tr><th>Description</th><th>Amount</th><th>Date</th><th>Action</th></tr>
<?php foreach($incomes as $i): ?>
<tr>
<td><?php echo htmlspecialchars($i['description']); ?></td>
<td>£<?php echo number_format($i['amount'],2); ?></td>
<td><?php echo $i['date']; ?></td>
<td><a href="delete.php?type=income&id=<?php echo $i['income_id']; ?>" style="color:red;">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>

<h2>Recent Expenses</h2>
<table class="table">
<tr><th>Description</th><th>Amount</th><th>Date</th><th>Action</th></tr>
<?php foreach($expenses as $e): ?>
<tr>
<td><?php echo htmlspecialchars($e['description']); ?></td>
<td>£<?php echo number_format($e['amount'],2); ?></td>
<td><?php echo $e['date']; ?></td>
<td><a href="delete.php?type=expense&id=<?php echo $e['expense_id']; ?>" style="color:red;">Delete</a></td>
</tr>
<?php endforeach; ?>
</table>

<div class="actions">
<a href="income.php">Add Income</a>
<a href="expenses.php">Add Expense</a>
</div>

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
<li><a href="forgot_password.php">Forgot Password</a></li>
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
window.onload = function() {
    new Chart(document.getElementById('financeChart'), {
        type: 'pie',
        data: {
            labels: ['Income', 'Expenses'],
            datasets: [{
                data: [<?php echo $total_income ?: 1; ?>, <?php echo $total_expenses ?: 1; ?>],
                backgroundColor: ['#4CAF50', '#F44336']
            }]
        },
        options: { responsive: true }
    });
};
</script>

</body>
</html>