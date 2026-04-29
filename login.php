<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $security_answer = trim($_POST['security_answer'] ?? '');

    $stmt = $conn->prepare("SELECT * FROM User WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (!password_verify($password, $user['password'])) {
            $message = "Incorrect credentials.";
        }
        elseif (strcasecmp($security_answer, $user['security_answer']) !== 0) {
            $message = "Incorrect credentials.";
        }
        else {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'] ?? 'user';

            if (
                $_SESSION['role'] === 'admin' ||
                $email === 'buffbudgetadmin@gmail.com'
            ) {
                $_SESSION['role'] = 'admin';
                header("Location: admin.php");
                exit();
            }

            header("Location: dashboard.php");
            exit();
        }

    } else {
        $message = "Incorrect credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>

<body class="login-page">

<?php include 'navbar.php'; ?>

<div class="login-container">
    <div class="login-card">

        <img src="logo 2.png" class="login-logo">
        <h2>Login</h2>

        <?php if ($message): ?>
            <div class="login-message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="login-form">

            <input type="email" name="email" placeholder="Email Address" required>

            <input type="password" name="password" placeholder="Password" required>

            <input type="text" name="security_answer" placeholder="Security Answer" required>

            <button type="submit" class="login-btn">Login</button>

            <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
            <a href="contact.php" class="forgot-link">Forgot security answer? Contact us</a>

</div>

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
                    <li><a href="index.html">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
            <div class="index-footer-column">
                <h4>Contact Us</h4>
                <p>Tel: (01321) 2340 235</p>
                <p>Email: buffbudgetadmin@gmail.com</p>
            </div>
        </div>
    </footer>

</body>
</html>