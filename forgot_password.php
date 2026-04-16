<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    <title>Buff Budgets</title>
</head>

<body class="index-page">

    <nav class="index-navbar">
        <div class="index-logo">
            <a href="index.html">
                <img src="logo.png" alt="Buff Budgets Logo">
            </a>
        </div>

        <ul class="index-nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>

<?php
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT user_id FROM User WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt = $conn->prepare("UPDATE User SET reset_token=?, reset_expiry=? WHERE email=?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        $message = "Reset link created: http://localhost/BudgetTracker/reset_password.php?token=$token";

    } else {
        $message = "No account found with that email.";
    }
}
?>

<form method="POST">
    <h2>Forgot Password</h2>
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Send Reset Link</button>
</form>

<p><?php echo htmlspecialchars($message); ?></p>

