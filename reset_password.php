<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    <title>Buff Budgets</title>

    <style>
    .reset-container {
        max-width: 500px;
        margin: 140px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        text-align: center;
    }

    .reset-container input {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    .reset-container button {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        background: #dfa1dc;
        font-weight: bold;
        cursor: pointer;
    }

    .reset-message {
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 8px;
        font-weight: bold;
    }

    .reset-message.success {
        background: #d4edda;
        color: #155724;
    }

    .reset-message.error {
        background: #f8d7da;
        color: #721c24;
    }
    </style>
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
        <li><a href="contact.php">Contact Us</a></li>
        <li><a href="contact.php">Contact Us</a></li>
    </ul>
</nav>

<?php
include "db.php";

$token = $_GET['token'] ?? '';
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST['token'];
    $new_password = $_POST['password'];

    if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $new_password)) {
        $message = "Password must be 8+ characters, include a capital letter, number and special character.";
        $messageType = "error";
    } else {

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
            $messageType = "success";

        } else {
            $message = "Invalid or expired token.";
            $messageType = "error";
        }
    }
}
?>

<div class="reset-container">

    <h2>Reset Password</h2>

    <?php if (!empty($message)): ?>
        <div class="reset-message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="password" name="password" placeholder="New Password" required>
        <button type="submit">Reset Password</button>
    </form>

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
                <li><a href="index.html">Home</a></li>
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