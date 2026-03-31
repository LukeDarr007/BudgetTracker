<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];

    $sql = "INSERT INTO expense (user_id, amount, description, date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdss", $user_id, $amount, $description, $date);
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Error adding expense.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Expense - Buff Budgets</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>
<body class="dashboard-page">

<nav class="dashboard-navbar">
    <div class="dashboard-logo">
        <a href="dashboard.php"><img src="logo.png" alt="Buff Budgets Logo"></a>
    </div>
    <ul class="dashboard-nav-links">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<main class="dashboard-main">
<h2>Add Expense</h2>
<?php if ($message) echo "<p style='color:red;'>$message</p>"; ?>
<form method="POST">
    <label>Amount</label>
    <input type="number" name="amount" step="0.01" required>
    <label>Description</label>
    <input type="text" name="description" required>
    <label>Date</label>
    <input type="date" name="date" required>
    <button type="submit">Add Expense</button>
</form>
</main>
</body>
</html>