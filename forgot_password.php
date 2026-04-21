<?php
session_start();
include "db.php";

$message = "";
$messageType = "";
$resetLink = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $security_answer = isset($_POST['security_answer']) ? trim($_POST['security_answer']) : "";

    if ($email === "" || $security_answer === "") {
        $message = "Please fill in all fields.";
        $messageType = "error";
    } else {

        $stmt = $conn->prepare("SELECT * FROM User WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (strcasecmp($security_answer, $user['security_answer']) !== 0) {

                $message = "Incorrect security answer.";
                $messageType = "error";

            } else {

                $token = bin2hex(random_bytes(50));
                $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

                $stmt = $conn->prepare("UPDATE User SET reset_token=?, reset_expiry=? WHERE email=?");
                $stmt->bind_param("sss", $token, $expiry, $email);
                $stmt->execute();

                $resetLink = "reset_password.php?token=$token";
                $message = "Reset link created.";
                $messageType = "success";
            }

        } else {
            $message = "No account found with that email.";
            $messageType = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
<title>Forgot Password</title>

<style>
.register-message {
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 8px;
    font-weight: bold;
    text-align: center;
}
.register-message.success {
    background: #d4edda;
    color: #155724;
}
.register-message.error {
    background: #f8d7da;
    color: #721c24;
}
.reset-link {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 20px;
    background: #dfa1dc;
    color: #000;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
}
</style>

</head>

<body class="register-page">

<nav class="register-navbar">
    <div class="index-logo">
        <a href="index.html"><img src="logo.png"></a>
    </div>
    <ul class="index-nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="contact.php">Contact Us</a></li>
    </ul>
</nav>

<div class="register-container">
    <div class="register-card">

        <img src="logo 2.png" class="register-logo">
        <h2>Forgot Password</h2>

        <?php if (!empty($message)): ?>
            <div class="register-message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>

                <?php if ($messageType === "success"): ?>
                    <br>
                    <a href="<?php echo $resetLink; ?>" class="reset-link">
                        Reset Your Password
                    </a>
                    
    

                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form class="register-form" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="text" name="security_answer" placeholder="Enter your security answer" required>
            <button type="submit" class="register-btn">Get Reset Link</button>
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