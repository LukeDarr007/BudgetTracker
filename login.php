<?php
session_start();
include 'db.php'; // Make sure this sets up $conn properly

// Initialize error message
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepare and execute query
    $sql = "SELECT * FROM User WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            // Successful login → store session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];

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
    <link rel="stylesheet" href="styles.css">
    <title>Login | Buff Budgets</title>
    <style>
        .error {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="register-page">

<nav class="register-navbar">
    <div class="index-logo">
        <a href="index.html"><img src="logo.png" alt="Buff Budgets Logo"></a>
    </div>
    <ul class="index-nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    </ul>
</nav>

<div class="register-container">
    <div class="register-card">
        <img src="logo.png" alt="Buff Budgets Logo" class="register-logo">
        <h2>Login</h2>
        <p class="register-subtitle">Access your budget dashboard</p>

        <!-- Display error message -->
        <?php if($message): ?>
            <p class="error"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form class="register-form" method="POST" action="">
            <input 
                type="email" 
                name="email" 
                placeholder="Email Address"
                value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                required
            >

            <input 
                type="password" 
                name="password" 
                placeholder="Password"
                required
            >

            <button type="submit" class="register-btn">
                Login
            </button>
        </form>
    </div>
</div>

</body>
</html>
