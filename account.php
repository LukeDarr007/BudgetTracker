<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT first_name, last_name, email, address, role FROM User WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc() ?? [];

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);

    $update_sql = "UPDATE User SET first_name=?, last_name=?, email=?, address=? WHERE user_id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $address, $user_id);
    if ($stmt->execute()) {
        $message = "Account details updated successfully.";
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $user['first_name'] = $first_name;
        $user['last_name'] = $last_name;
        $user['email'] = $email;
        $user['address'] = $address;
    } else {
        $message = "Failed to update details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Account - Buff Budgets</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
<style>
body.account-page {
    background-color: var(--light-bg);
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: var(--primary-color);
    padding: 1rem 2rem;
}

.navbar .logo img {
    height: 40px;
}

.navbar .nav-links {
    list-style: none;
    display: flex;
    gap: 1rem;
    margin: 0;
    padding: 0;
}

.navbar .nav-links li a {
    color: var(--white);
    text-decoration: none;
    font-weight: bold;
}

.navbar .nav-links li a:hover {
    color: var(--accent-color);
}

.account-container {
    display: flex;
    min-height: calc(100vh - 70px);
}

.account-sidebar {
    width: 250px;
    background-color: var(--primary-color);
    color: var(--white);
    padding: 2rem 1rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.account-main {
    flex: 1;
    padding: 2rem;
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

.account-card {
    background-color: var(--white);
    padding: 2rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    width: 100%;
    max-width: 600px;
}

.account-card h3 {
    margin-bottom: 1rem;
    text-align: center;
}

.account-card label {
    font-weight: bold;
}

.account-card input {
    width: 100%;
    padding: 0.75rem;
    margin-bottom: 1rem;
    border: 1px solid #ccc;
    border-radius: var(--radius);
}

.account-card button {
    background-color: var(--primary-color);
    color: var(--white);
    border: none;
    padding: 12px 20px;
    border-radius: var(--radius);
    cursor: pointer;
    font-weight: bold;
    width: 100%;
    transition: var(--transition);
}

.account-card button:hover {
    background-color: #154a19;
}

.message {
    margin-bottom: 1rem;
    color: green;
    font-weight: bold;
    text-align: center;
}

@media(max-width: 768px) {
    .account-container {
        flex-direction: column;
    }
    .account-sidebar {
        width: 100%;
        flex-direction: row;
        justify-content: space-around;
        text-align: center;
    }
}
</style>
</head>
<body class="account-page">

<nav class="navbar">
    <div class="logo">
        <a href="dashboard.php"><img src="logo.png" alt="Buff Budgets Logo"></a>
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

<div class="account-container">
    <div class="account-sidebar">
        <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
        <p>User ID: <?php echo htmlspecialchars($user_id); ?></p>
    </div>

    <div class="account-main">
        <div class="account-card">
            <?php if($message): ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <h3>Account Details</h3>
            <form method="POST">
                <label>First Name</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

                <label>Last Name</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label>Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">

                <button type="submit">Update Details</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>