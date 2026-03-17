<?php
session_start();
include "db.php";



$name = $_SESSION['name'] ?? "User";
$user_id = $_SESSION['user_id'] ?? "";




$total_income = 0;

if($user_id != ""){
    $sql = "SELECT SUM(amount) AS total FROM income WHERE user_id='$user_id'";
    $result = $conn->query($sql);

    if($result){
        $row = $result->fetch_assoc();
        $total_income = $row['total'] ?? 0;
    }
}


$total_expenses = 0;

if($user_id != ""){
    $sql = "SELECT SUM(amount) AS total FROM expense WHERE user_id='$user_id'";
    $result = $conn->query($sql);

    if($result){
        $row = $result->fetch_assoc();
        $total_expenses = $row['total'] ?? 0;
    }
}




$transactions = [];

if($user_id != ""){
    $sql = "SELECT description, amount, date 
            FROM expense 
            WHERE user_id='$user_id'
            ORDER BY date DESC 
            LIMIT 5";

    $result = $conn->query($sql);

    if($result){
        while($row = $result->fetch_assoc()){
            $transactions[] = $row;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Buff Budgets</title>
<link rel="stylesheet" href="styles.css">
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
<h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
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

<h2>Recent Expenses</h2>

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
<td>£<?php echo number_format($t['amount'],2); ?></td>
<td><?php echo $t['date']; ?></td>
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
```
