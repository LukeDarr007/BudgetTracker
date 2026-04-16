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

<style>
body.account-page {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f4f6f8;
}

/* NAV */
.navbar {
    position: fixed;
    top: 0;
    width: 100%;
    background: #1B5E20;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    z-index: 1000;
}

.nav-links {
    display: flex;
    gap: 15px;
    list-style: none;
}

.nav-links a {
    color: white;
    text-decoration: none;
    font-weight: 500;
}

/* LAYOUT */
.account-wrapper {
    display: flex;
    gap: 25px;
    padding: 120px 40px 40px;
}

/* SIDEBAR */
.sidebar {
    width: 28%;
    background: #1B5E20;
    color: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}

.sidebar h2 {
    margin: 0 0 10px 0;
}

/* MAIN CARD */
.content {
    flex: 1;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
}

h2 {
    margin-bottom: 20px;
}

/* FORM */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
}

input:focus {
    border-color: #1B5E20;
}

/* BUTTONS */
button {
    padding: 12px;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    font-weight: bold;
    width: 100%;
    margin-top: 10px;
}

.update {
    background: #1B5E20;
    color: white;
}

.update:hover {
    background: #145016;
}

.delete {
    background: #d32f2f;
    color: white;
}

.delete:hover {
    background: #a61f1f;
}

/* MESSAGE */
.message {
    background: #e8f5e9;
    color: #1B5E20;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 500;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .account-wrapper {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
    }
}
</style>

</head>

<body class="account-page">

<nav class="navbar">
    <div class="logo">
        <a href="dashboard.php"><img src="logo.png" height="40"></a>
    </div>

    <ul class="nav-links">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="account.php">Account</a></li>
        <li><a href="expenses.php">Expenses</a></li>
        <li><a href="budgets.php">Budgets</a></li>
        <li><a href="income.php">Income</a></li>
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

</body>
</html>