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
    $amount_raw = $_POST['amount'] ?? '';
    $date = $_POST['date'] ?? date('Y-m-d');

    if ($description === "") {
        $message = "Description required.";
    }
    elseif ($amount_raw === "") {
        $message = "Amount is required.";
    }
    elseif (!is_numeric($amount_raw)) {
        $message = "Amount must be a valid number.";
    }
    else {
        $amount = floatval($amount_raw);

        if ($amount <= 0) {
            $message = "Amount must be greater than 0.";
        }
        elseif ($amount > 1000000) {
            $message = "Amount cannot exceed 1,000,000.";
        }
        else {
            $stmt = $conn->prepare("INSERT INTO expense (user_id, description, amount, date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $user_id, $description, $amount, $date);
            $stmt->execute();
            $stmt->close();

            header("Location: expenses.php?year=" . date('Y'));
            exit();
        }
    }
}

if (isset($_POST['delete_expense'])) {
    $expense_id = intval($_POST['expense_id']);

    $stmt = $conn->prepare("DELETE FROM expense WHERE expense_id=? AND user_id=?");
    $stmt->bind_param("is", $expense_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: expenses.php");
    exit();
}

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : 0;

if ($year < 2000) $year = 2000;
if ($year > 2026) $year = 2026;

if ($month > 0) {
    $stmt = $conn->prepare("SELECT expense_id, description, amount, date FROM expense WHERE user_id=? AND YEAR(date)=? AND MONTH(date)=? ORDER BY date DESC");
    $stmt->bind_param("sii", $user_id, $year, $month);
} else {
    $stmt = $conn->prepare("SELECT expense_id, description, amount, date FROM expense WHERE user_id=? AND YEAR(date)=? ORDER BY date DESC");
    $stmt->bind_param("si", $user_id, $year);
}

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

$max = 100000;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Expenses</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>

<body class="expenses-page">

<?php include 'navbar.php'; ?>

<main class="expenses-main">

<h1 class="page-title">Add Your Expenses</h1>

<?php if ($message): ?>
<p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<div class="top-bar">

<form method="GET">
    <input type="number" name="year" min="2000" max="2026" value="<?php echo $year; ?>">

    <select name="month">
        <option value="0">All Months</option>
        <?php for ($i=1; $i<=12; $i++): ?>
            <option value="<?php echo $i; ?>" <?php if($month==$i) echo "selected"; ?>>
                <?php echo date("F", mktime(0,0,0,$i,1)); ?>
            </option>
        <?php endfor; ?>
    </select>

    <button type="submit">Filter</button>
</form>

<div class="total-box">
£<?php echo number_format($total,2); ?>
</div>

<button onclick="openPopup()">Add Expense</button>

</div>

<div class="chart">
<?php foreach ($monthly as $v): ?>
<div class="bar" style="height:<?php echo min(220, ($v / $max) * 220); ?>px;"></div>
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
        <span onclick="closePopup()">X</span>
        <form method="POST">
            <input type="text" name="description" placeholder="Description" required>
            <input type="number" step="0.01" name="amount" min="0.01" max="1000000" required>
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

</body>
</html>