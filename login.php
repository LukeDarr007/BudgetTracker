<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
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

<?php if($message) echo "<p>$message</p>"; ?>

<div class="register-container">
    <div class="register-card">

        <img src="logo.png" alt="Buff Budgets Logo" class="register-logo">

        <h2>Login</h2>
        <p class="register-subtitle">Access your budget dashboard</p>

        <form class="register-form" method="POST" action="">

            <input 
                type="email" 
                name="email" 
                placeholder="Email Address"
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
