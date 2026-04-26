<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

$stmt = $conn->prepare("SELECT first_name, last_name, email, phone, address, security_answer FROM User WHERE user_id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc() ?? [];

$first_name = $user['first_name'] ?? '';
$last_name  = $user['last_name'] ?? '';
$email      = $user['email'] ?? '';
$phone      = $user['phone'] ?? '';
$address    = $user['address'] ?? '';
$security   = $user['security_answer'] ?? '';

$tab = $_GET['tab'] ?? 'overview';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: index.html");
        exit();
    }

    if (isset($_POST['delete_account'])) {
        $stmt = $conn->prepare("DELETE FROM User WHERE user_id=?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        session_unset();
        session_destroy();
        header("Location: index.html");
        exit();
    }

    if (isset($_POST['update_account'])) {

        $first_name = trim($_POST['first_name'] ?? '');
        $last_name  = trim($_POST['last_name'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');
        $address    = trim($_POST['address'] ?? '');

        $stmt = $conn->prepare("UPDATE User SET first_name=?, last_name=?, email=?, phone=?, address=? WHERE user_id=?");
        $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $address, $user_id);
        $stmt->execute();

        $message = "Account updated successfully.";
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
        <li><a href="income.php">Income</a></li>
        <li><a href="budgets.php">Budgets</a></li>
        <li><a href="categories.php">Categories</a></li>
        <li><a href="monthly_summary.php">Monthly Summary</a></li>
    </ul>
</nav>


<div class="account-wrapper">

    <h1>Your Account</h1>

    <div class="account-sidebar">
        <a href="?tab=overview"> Your Account Overview</a>
        <a href="?tab=personal">Your Personal Details</a>
        <a href="?tab=security"> Account Security</a>
    </div>

    <div class="account-content">

        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if ($tab === 'overview'): ?>

            <h2>Account Overview</h2>
            <p><b>Name:</b> <?php echo htmlspecialchars($first_name . " " . $last_name); ?></p>
            <p><b>Email:</b> <?php echo htmlspecialchars($email); ?></p>
            <p><b>User ID:</b> <?php echo htmlspecialchars($user_id); ?></p>

        <?php elseif ($tab === 'personal'): ?>

            <h2> Your Personal Details</h2>

            <form method="POST">
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>">

                <button type="submit" name="update_account">Update Your Account</button>
            </form>

        <?php elseif ($tab === 'security'): ?>

            <h2>Security</h2>

            <p><b>Your Security Answer:</b> <?php echo htmlspecialchars($security); ?></p>

            <form method="POST">
                <button type="submit" name="logout">Logout Of Buff Budgets</button>
            </form>

            <form method="POST">
                <button type="submit" name="delete_account" onclick="return confirm('Are you sure you want to delete your account?, this change is irreversible once confirmed');">
                    Delete Account
                </button>
            </form>

        <?php endif; ?>

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