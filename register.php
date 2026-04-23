<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';
session_start();

$message = "";
$messageType = "";

$questions = [
    "What was your childhood pet's name?",
    "What is your mother's maiden name?",
    "What is your favourite holiday destination?"
];

if (!isset($_SESSION['security_question'])) {
    $_SESSION['security_question'] = $questions[array_rand($questions)];
}

$selected_question = $_SESSION['security_question'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $address    = trim($_POST['address']);
    $password_raw = $_POST['password'];
    $security_answer = isset($_POST['security_answer']) ? trim($_POST['security_answer']) : "";

    if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password_raw)) {
        $message = "Password must be 8+ characters, include a capital letter, number and special character.";
        $messageType = "error";
    }

    elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        $message = "Phone number must be 10–15 digits.";
        $messageType = "error";
    }

    elseif (strlen($address) < 5) {
        $message = "Please enter a valid address.";
        $messageType = "error";
    }

    elseif ($security_answer === "") {
        $message = "Please answer the security question.";
        $messageType = "error";
    }

    else {

        $check = $conn->prepare("SELECT user_id FROM User WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "This account already exists.";
            $messageType = "error";
        }

        else {

            $password = password_hash($password_raw, PASSWORD_DEFAULT);
            $initials = strtoupper($first_name[0] . $last_name[0]);
            $user_id = $initials . "BT" . rand(100,999);
            $role = "User";

            $stmt = $conn->prepare("
                INSERT INTO User 
                (user_id, first_name, last_name, email, password, phone, address, role, security_question, security_answer)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "ssssssssss",
                $user_id,
                $first_name,
                $last_name,
                $email,
                $password,
                $phone,
                $address,
                $role,
                $selected_question,
                $security_answer
            );

            if ($stmt->execute()) {
                $message = "Account created successfully. Your ID: " . $user_id;
                $messageType = "success";
                unset($_SESSION['security_question']);
            } else {
                $message = "Error creating account.";
                $messageType = "error";
            }
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
<title>Register</title>

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
        <h2>Create Account</h2>

        <?php if (!empty($message)): ?>
            <div class="register-message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form class="register-form" method="POST">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="password" name="password" placeholder="Password" required>

            <input type="text" value="<?php echo htmlspecialchars($selected_question); ?>" readonly>
            <input type="text" name="security_answer" placeholder="Your Answer">

            <button type="submit" class="register-btn">Create Account</button>
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