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

$token = $_GET['token'] ?? '';
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST['token'];
    $new_password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id FROM User WHERE reset_token=? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE User SET password=?, reset_token=NULL, reset_expiry=NULL WHERE reset_token=?");
        $stmt->bind_param("ss", $hashed, $token);
        $stmt->execute();

        $message = "Password updated successfully.";

    } else {
        $message = "Invalid or expired token.";
    }
}
?>

<form method="POST">
    <h2>Reset Password</h2>

    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <input type="password" name="password" placeholder="New Password" required>

    <button type="submit">Reset Password</button>
</form>

<p><?php echo htmlspecialchars($message); ?></p>