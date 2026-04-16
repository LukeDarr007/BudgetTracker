<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM User WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['email'] = $row['email'];

            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
<title>Login</title>
</head>

<body class="login-page">

<nav class="login-navbar">
    <div class="index-logo">
        <a href="index.html"><img src="logo.png"></a>
    </div>
    <ul class="index-nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    </ul>
</nav>

<div class="login-container">
    <div class="login-card">
        <img src="logo 2.png" class="login-logo">
        <h2>Login</h2>

        <?php if ($message): ?>
            <p style="color:red;text-align:center;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form class="login-form" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="login-btn">Login</button>

            <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
        </form>
    </div>
</div>

<footer class="index-footer">
        <div class="index-footer-container">
            <div class="index-footer-column">
                <img src="logo.png" alt="Buff Budgets Logo" class="footer-logo">
                <p>© 2026 Buff Budgets. All rights reserved.</p>
            </div>
            <div class="index-footer-column">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.html">Dashboard</a></li>
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