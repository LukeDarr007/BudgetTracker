<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$message = "";

if (isset($_POST['add_expense'])) {

    $description = trim($_POST['description'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);
    $date = $_POST['date'] ?? date('Y-m-d');

    if ($description !== "" && $amount > 0) {

        $stmt = $conn->prepare("INSERT INTO expense (user_id, description, amount, date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $user_id, $description, $amount, $date);

        $stmt->execute();
        $stmt->close();

        $message = "Expense added.";
    } else {
        $message = "Invalid input.";
    }
}

if (isset($_POST['delete_expense'])) {

    $expense_id = intval($_POST['expense_id']);

    $stmt = $conn->prepare("DELETE FROM expense WHERE expense_id=? AND user_id=?");
    $stmt->bind_param("is", $expense_id, $user_id);
    $stmt->execute();
    $stmt->close();

    $message = "Expense deleted.";
}

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
if ($year < 2000) $year = 2000;
if ($year > 2026) $year = 2026;

$stmt = $conn->prepare("SELECT expense_id, description, amount, date FROM expense WHERE user_id=? AND YEAR(date)=? ORDER BY date DESC");
$stmt->bind_param("si", $user_id, $year);
$stmt->execute();
$result = $stmt->get_result();

$expenses = [];
while ($row = $result->fetch_assoc()) {
    $expenses[] = $row;
}

$total = 0;
foreach ($expenses as $e) {
    $total += $e['amount'];
}

$monthly = array_fill(1, 12, 0);

foreach ($expenses as $e) {
    $m = (int)date('n', strtotime($e['date']));
    $monthly[$m] += $e['amount'];
}

$max = max($monthly);
if ($max == 0) $max = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Expenses</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">

</head>

<body>

<nav class="navbar">
    <div class="dashboard-logo">
        <a href="dashboard.php"><img src="logo.png"></a>
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

<main class="expenses-main">

<?php if ($message): ?>
<p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<div class="top-bar">

<form method="GET">
    <label>Year</label>
    <input type="number" name="year" min="2000" max="2026" value="<?php echo $year; ?>">
    <button type="submit">Filter</button>
</form>

<div class="total-box">
£<?php echo number_format($total,2); ?>
</div>

<button onclick="openPopup()">Add Expense</button>

</div>

<div class="chart">
<?php foreach ($monthly as $v): ?>
<div class="bar" style="height:<?php echo ($v/$max)*220; ?>px;"></div>
<?php endforeach; ?>
</div>

<div class="months">
<?php foreach(["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"] as $m): ?>
<span><?php echo $m; ?></span>
<?php endforeach; ?>
</div>

<h2>Recent Expenses</h2>

<table>
<tr>
<th>Description</th>
<th>Amount</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php foreach ($expenses as $e): ?>
<tr>
<td><?php echo htmlspecialchars($e['description']); ?></td>
<td>£<?php echo number_format($e['amount'],2); ?></td>
<td><?php echo $e['date']; ?></td>
<td>
<form method="POST">
    <input type="hidden" name="expense_id" value="<?php echo $e['expense_id']; ?>">
    <button name="delete_expense">Delete</button>
</form>
</td>
</tr>
<?php endforeach; ?>

</table>

</main>

<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">X</span>

        <form method="POST">
            <input type="text" name="description" placeholder="Description" required>
            <input type="number" step="0.01" name="amount" placeholder="Amount" required>
            <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>">
            <button name="add_expense">Add</button>
        </form>

    </div>
</div>

<script>
function openPopup() {
    document.getElementById("popup").style.display = "flex";
}
function closePopup() {
    document.getElementById("popup").style.display = "none";
}
</script>

<footer class="index-footer">
    <div class="index-footer-container">
        <div class="index-footer-column">
            <img src="logo.png" class="footer-logo">
            <p>© 2026 Buff Budgets. All rights reserved.</p>
        </div>

        <div class="index-footer-column">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="index.html">Home</a></li>
            </ul>
        </div>

        <div class="index-footer-column">
            <h4>Contact Us</h4>
            <p>Tel: (01321) 2340 235</p>
            <p>Email: info@buffbudgets.com</p>
        </div>
    </div>
</footer>

</body>
</html>