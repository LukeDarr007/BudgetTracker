<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

if ($year < 2000) $year = 2000;
if ($year > 2026) $year = 2026;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $month = intval($_POST['month']);
    $amount = floatval($_POST['amount']);

    // check if exists
    $check = $conn->prepare("SELECT id FROM budgets WHERE user_id=? AND year=? AND month=?");
    $check->bind_param("sii", $user_id, $year, $month);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // update
        $row = $result->fetch_assoc();
        $stmt = $conn->prepare("UPDATE budgets SET target_amount=? WHERE id=?");
        $stmt->bind_param("di", $amount, $row['id']);
        $stmt->execute();
    } else {
        // insert
        $stmt = $conn->prepare("INSERT INTO budgets (user_id, year, month, target_amount) VALUES (?,?,?,?)");
        $stmt->bind_param("siid", $user_id, $year, $month, $amount);
        $stmt->execute();
    }

    header("Location: budgets.php?year=$year");
    exit();
}


if (isset($_GET['delete'])) {
    $month = intval($_GET['delete']);

    $stmt = $conn->prepare("DELETE FROM budgets WHERE user_id=? AND year=? AND month=?");
    $stmt->bind_param("sii", $user_id, $year, $month);
    $stmt->execute();

    header("Location: budgets.php?year=$year");
    exit();
}

$stmt = $conn->prepare("SELECT month, target_amount FROM budgets WHERE user_id=? AND year=?");
$stmt->bind_param("si", $user_id, $year);
$stmt->execute();
$res = $stmt->get_result();

$budgets = [];
while ($row = $res->fetch_assoc()) {
    $budgets[$row['month']] = $row['target_amount'];
}

$months = [
    1=>"Jan","Feb","Mar","Apr","May","Jun",
    "Jul","Aug","Sep","Oct","Nov","Dec"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Budgets</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>

<body class="monthly-summary-page">

<nav class="navbar">
    <div class="dashboard-logo">
        <a href="dashboard.php"><img src="logo.png"></a>
    </div>
    <ul class="dashboard-nav-links">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="account.php">Account</a></li>
        <li><a href="expenses.php">Expenses</a></li>
        <li><a href="income.php">Income</a></li>
        <li><a href="budgets.php">Budgets</a></li>
        <li><a href="categories.php">Categories</a></li>
        <li><a href="monthly_summary.php">Monthly Summary</a></li>
    </ul>
</nav>

<header class="monthly-summary-hero-banner">
    <div class="hero-text">
        <h1>Budgets</h1>
        <p>Set your monthly financial targets</p>
    </div>
</header>

<main class="monthly-summary-main">

<h2>Budget for <?php echo $year; ?></h2>

<div style="text-align:center; margin-bottom:20px;">
<a class="btn" href="?year=<?php echo $year - 1; ?>">Prev Year</a>
<a class="btn" href="?year=<?php echo $year + 1; ?>">Next Year</a>
</div>

<div class="budget-grid">

<?php foreach ($months as $num => $name): ?>

<div class="budget-card">

<h3><?php echo $name; ?></h3>

<p>
Current Target:
<strong>
£<?php echo isset($budgets[$num]) ? number_format($budgets[$num],2) : "0.00"; ?>
</strong>
</p>

<form method="POST">
    <input type="hidden" name="month" value="<?php echo $num; ?>">

    <input type="number" step="0.01" name="amount"
        placeholder="Set budget"
        value="<?php echo $budgets[$num] ?? ''; ?>">

    <button type="submit" class="btn">Save</button>
</form>

<?php if (isset($budgets[$num])): ?>
<a href="?delete=<?php echo $num; ?>&year=<?php echo $year; ?>"
   class="btn" style="background:red;">Delete</a>
<?php endif; ?>

</div>

<?php endforeach; ?>

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

</body>
</html>