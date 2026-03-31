<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';
$user_id = $_SESSION['user_id'];

if ($type === 'expense' && $id) {
    $stmt = $conn->prepare("DELETE FROM expense WHERE expense_id=? AND user_id=?");
    $stmt->bind_param("ss", $id, $user_id);
    $stmt->execute();
}

if ($type === 'income' && $id) {
    $stmt = $conn->prepare("DELETE FROM income WHERE income_id=? AND user_id=?");
    $stmt->bind_param("ss", $id, $user_id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();
?>