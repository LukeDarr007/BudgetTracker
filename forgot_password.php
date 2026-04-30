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

                $stmt = $conn->prepare("UPDATE User SET reset_token=?, reset_expires=? WHERE email=?");
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

</head>

<?php include 'navbar.php'; ?>


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