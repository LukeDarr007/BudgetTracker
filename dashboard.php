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

$total_income = 0;
$sql = "SELECT SUM(amount) AS total FROM income WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_income = $row['total'] ?? 0;

$total_expenses = 0;
$sql = "SELECT SUM(amount) AS total FROM expense WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_expenses = $row['total'] ?? 0;

$incomes = [];
$sql = "SELECT income_id, description, amount, date FROM income WHERE user_id=? ORDER BY date DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $incomes[] = $row;
}

$expenses = [];
$sql = "SELECT expense_id, description, amount, date FROM expense WHERE user_id=? ORDER BY date DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $expenses[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Buff Budgets</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
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

<header class="dashboard-hero-banner">
    <h1>Welcome, <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?>!</h1>
</header>

<main class="dashboard-main">
<h2>Your Financial Overview</h2>
<section class="summary">
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
</section>

<section class="transactions">
<h2>Recent Income</h2>
<table>
<thead>
<tr>
<th>Description</th>
<th>Amount</th>
<th>Date</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach($incomes as $i): ?>
<tr>
<td><?php echo htmlspecialchars($i['description']); ?></td>
<td>£<?php echo number_format($i['amount'],2); ?></td>
<td><?php echo $i['date']; ?></td>
<td><a href="delete.php?type=income&id=<?php echo $i['income_id']; ?>" style="color:red;font-weight:bold;">Delete</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<h2>Recent Expenses</h2>
<table>
<thead>
<tr>
<th>Description</th>
<th>Amount</th>
<th>Date</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach($expenses as $e): ?>
<tr>
<td><?php echo htmlspecialchars($e['description']); ?></td>
<td>£<?php echo number_format($e['amount'],2); ?></td>
<td><?php echo $e['date']; ?></td>
<td><a href="delete.php?type=expense&id=<?php echo $e['expense_id']; ?>" style="color:red;font-weight:bold;">Delete</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</section>

<section class="actions">
<a href="add_income.php">Add Income</a>
<a href="add_expense.php">Add Expense</a>
</section>
</main>

<footer class="dashboard-footer">
<p>© 2026 Buff Budgets</p>
</footer>
</body>
</html>