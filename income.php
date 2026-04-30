<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT user_id FROM user WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    session_destroy();
    die("Invalid session - user does not exist in database.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_income'])) {

    $description = trim($_POST['description'] ?? '');
    $amount = $_POST['amount'] ?? '';
    $date = $_POST['date'] ?? date('Y-m-d');

    if ($description === '' || $amount === '') {
        $message = "All fields are required.";
    }
    elseif (!is_numeric($amount) || $amount <= 0 || $amount > 1000000) {
        $message = "Invalid amount.";
    }
    else {
        $stmt = $conn->prepare("INSERT INTO income (user_id, description, amount, date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $user_id, $description, $amount, $date);
        $stmt->execute();

        header("Location: income.php?year=" . date('Y'));
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_income'])) {

    $income_id = $_POST['income_id'] ?? 0;

    $stmt = $conn->prepare("DELETE FROM income WHERE income_id = ? AND user_id = ?");
    $stmt->bind_param("is", $income_id, $user_id);
    $stmt->execute();

    header("Location: income.php");
    exit();
}

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : 0;

if ($month > 0) {
    $stmt = $conn->prepare("SELECT income_id, description, amount, date FROM income WHERE user_id = ? AND YEAR(date) = ? AND MONTH(date) = ? ORDER BY date DESC");
    $stmt->bind_param("sii", $user_id, $year, $month);
} else {
    $stmt = $conn->prepare("SELECT income_id, description, amount, date FROM income WHERE user_id = ? AND YEAR(date) = ? ORDER BY date DESC");
    $stmt->bind_param("si", $user_id, $year);
}

$stmt->execute();
$result = $stmt->get_result();

$income = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $income[] = $row;
    $total += $row['amount'];
}

$monthly = array_fill(1, 12, 0);

foreach ($income as $i) {
    $m = (int)date('n', strtotime($i['date']));
    $monthly[$m] += $i['amount'];
}

$max = max($monthly);
if ($max <= 0) $max = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Income</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>

<body class="expenses-page">

<?php include 'navbar.php'; ?>

<main class="expenses-main">

<h1 class="page-title">Your Income</h1>

<?php if ($message): ?>
<p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<div class="top-bar">

<form method="GET">
<input type="number" name="year" value="<?php echo $year; ?>">
<select name="month">
<option value="0">All Months</option>
<?php for ($i = 1; $i <= 12; $i++): ?>
<option value="<?php echo $i; ?>" <?php if ($month == $i) echo "selected"; ?>>
<?php echo date("F", mktime(0,0,0,$i,1)); ?>
</option>
<?php endfor; ?>
</select>
<button type="submit">Filter</button>
</form>

<div class="total-box">
£<?php echo number_format($total, 2); ?>
</div>

<button onclick="openPopup()">Add Income</button>

</div>

<div class="chart">
<?php foreach ($monthly as $v): ?>
<div class="bar" style="height:<?php echo ($v / $max) * 220; ?>px;"></div>
<?php endforeach; ?>
</div>

<div class="months">
<?php foreach (["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"] as $m): ?>
<span><?php echo $m; ?></span>
<?php endforeach; ?>
</div>

<h2>Recent Income</h2>

<table>
<tr>
<th>Description</th>
<th>Amount</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php foreach ($income as $i): ?>
<tr>
<td><?php echo htmlspecialchars($i['description']); ?></td>
<td>£<?php echo number_format($i['amount'], 2); ?></td>
<td><?php echo $i['date']; ?></td>
<td>
<form method="POST">
<input type="hidden" name="income_id" value="<?php echo $i['income_id']; ?>">
<button name="delete_income">Delete</button>
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
<input type="text" name="description" required>
<input type="number" step="0.01" name="amount" required>
<input type="date" name="date" value="<?php echo date('Y-m-d'); ?>">
<button name="add_income">Add</button>
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

</body>
</html>