<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT first_name, last_name, email, phone, address FROM User WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc() ?? [];

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['delete_account'])) {
        $stmt = $conn->prepare("DELETE FROM User WHERE user_id=?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        session_unset();
        session_destroy();
        header("Location: index.html");
        exit();
    }

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $update_sql = "UPDATE User SET first_name=?, last_name=?, email=?, phone=?, address=? WHERE user_id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $address, $user_id);

    if ($stmt->execute()) {
        $message = "Account updated successfully.";
        $user = array_merge($user, [
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "phone" => $phone,
            "address" => $address
        ]);
    } else {
        $message = "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Account</title>

<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">




</head>

<body class="account-page">

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

<div class="account-wrapper">

    <div class="sidebar">
        <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
        <p><b>ID:</b> <?php echo htmlspecialchars($user_id); ?></p>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
    </div>

    <div class="content">

        <?php if($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h2>Account Details</h2>

        <form method="POST">
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="Phone">
            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">

            <button type="submit" class="update">Update Account</button>
        </form>

        <form method="POST">
            <button type="submit" name="delete_account" class="delete"
                onclick="return confirm('Are you sure you want to delete your account?');">
                Delete Account
            </button>
        </form>

    </div>

</div>

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
                <li><a href="budgets.php">Budgets</a></li>
                <li><a href="income.php">Income</a></li>
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