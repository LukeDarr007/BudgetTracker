<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['first_name'] ?? "User";

if(isset($_POST['add_expense'])) {
    $description = $_POST['description'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    $date = $_POST['date'] ?? date('Y-m-d');

    if($description && $amount > 0){
        $stmt = $conn->prepare("INSERT INTO expense (user_id, description, amount, date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $user_id, $description, $amount, $date);
        $stmt->execute();
        $stmt->close();
    }
}

$expenses = [];
$stmt = $conn->prepare("SELECT description, amount, date FROM expense WHERE user_id=? ORDER BY date DESC");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    $expenses[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Expenses - Buff Budgets</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>

<body class="expenses-page">

<nav class="navbar">
    <div class="logo">
        <a href="dashboard.php"><img src="logo.png" alt="Logo"></a>
    </div>

    <ul class="nav-links">
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

<header class="expenses-hero-banner">
<h1>Expenses</h1>
<p>Manage your expenses, <?php echo htmlspecialchars($name); ?></p>
</header>

<main class="expenses-main">

<h2>Add New Expense</h2>
<form method="POST" class="expense-form">
    <input type="text" name="description" placeholder="Description" required>
    <input type="number" step="0.01" name="amount" placeholder="Amount (£)" required>
    <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
    <button type="submit" name="add_expense">Add Expense</button>
</form>

<h2>Recent Expenses</h2>
<table class="transactions">
<thead>
<tr>
<th>Description</th>
<th>Amount</th>
<th>Date</th>
</tr>
</thead>
<tbody>

<?php if(count($expenses) > 0): ?>
    <?php foreach($expenses as $e): ?>
    <tr>
        <td><?php echo htmlspecialchars($e['description']); ?></td>
        <td>£<?php echo number_format($e['amount'],2); ?></td>
        <td><?php echo $e['date']; ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
<tr><td colspan="3">No expenses added yet.</td></tr>
<?php endif; ?>

</tbody>
</table>

</main>

<footer class="expenses-footer">
    <p>© 2026 Buff Budgets</p>
</footer>

</body>
</html>