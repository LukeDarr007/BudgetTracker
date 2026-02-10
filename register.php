<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address    = $_POST['address'];
    $role       = 'User';

    $initials = strtoupper($first_name[0] . $last_name[0]);
    $random_number = rand(100, 999);
    $user_id = $initials . "BT" . $random_number;

    $sql = "INSERT INTO User (user_id, first_name, last_name, email, password, address, role)
            VALUES ('$user_id', '$first_name', '$last_name', '$email', '$password', '$address', '$role')";

    if ($conn->query($sql) === TRUE) {
        $message = "Registration successful! Your User ID is $user_id";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Register | Buff Budgets</title>
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

    <h1>Register</h1>
    <?php if($message) echo "<p>$message</p>"; ?>

    <div class="register-container">
        <div class="register-card">
            <img src="logo.png" alt="Buff Budgets Logo" class="register-logo">
            <h2>Register For An Account</h2>
            <p class="register-subtitle">Manage your budgets with confidence</p>

            <form class="register-form" method="POST" action="">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="register-btn">Create Account</button>
            </form>
        </div>
    </div>
</body>
</html>
