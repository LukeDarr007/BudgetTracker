<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$user_id = intval($_GET['user'] ?? 0);
$message = "";
$temp_password = "";

function generateTempPassword($length = 8) {
    return bin2hex(random_bytes($length / 2));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $temp_password = generateTempPassword();
    $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

    $new_security = trim($_POST['security_answer']);

    $stmt = $conn->prepare("
        UPDATE User
        SET password = ?, security_answer = ?
        WHERE user_id = ?
    ");

    $stmt->bind_param("ssi", $hashed_password, $new_security, $user_id);
    $stmt->execute();

    $message = "User credentials updated successfully.";
}
?>

<h2>Reset User Account</h2>

<?php if ($message): ?>
    <p><strong><?php echo $message; ?></strong></p>
<?php endif; ?>

<?php if ($temp_password): ?>
    <p style="color:red;">
        Temporary Password: <strong><?php echo $temp_password; ?></strong>
    </p>
    <p>User must change this password after login.</p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="security_answer" placeholder="New Security Answer" required>
    <button type="submit">Reset User Password</button>
</form>