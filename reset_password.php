<?php
include "db.php";

$token = $_GET['token'] ?? '';
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST['token'] ?? '';
    $new_password = $_POST['password'] ?? '';

    if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $new_password)) {

        $message = "Password must be 8+ characters, include a capital letter, number and special character.";
        $messageType = "error";

    } else {

        $stmt = $conn->prepare("
            SELECT user_id 
            FROM User 
            WHERE reset_token = ? 
            AND reset_expiry > NOW()
        ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {

            $hashed = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                UPDATE User 
                SET password = ?, reset_token = NULL, reset_expiry = NULL 
                WHERE reset_token = ?
            ");
            $stmt->bind_param("ss", $hashed, $token);
            $stmt->execute();

            $message = "Password updated successfully.";
            $messageType = "success";

        } else {
            $message = "Invalid or expired reset link.";
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
    <title>Buff Budgets</title>
</head>

<body class="index-page">

<?php include 'navbar.php'; ?>

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

</body>
</html>