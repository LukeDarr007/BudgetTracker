<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete_user'])) {

    $id = intval($_GET['delete_user']);

    if ($id > 0 && $id != $_SESSION['user_id']) {

        $stmt = $conn->prepare("DELETE FROM User WHERE user_id = ? AND role != 'admin' LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin.php");
    exit();
}

$users = $conn->query("SELECT user_id, first_name, last_name, email, role FROM User WHERE role != 'admin'");
$contacts = $conn->query("SELECT id, email, message, status FROM contact_requests ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>

<body class="admin-page">

<nav class="admin-navbar">
    <div><strong>Buff Budgets Admin</strong></div>
    <div>
        <a href="dashboard.php">User Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="admin-wrapper">

<h1>Admin Dashboard</h1>

<div class="admin-grid">

    <div class="admin-card">
        <h3>Total Users</h3>
        <p><?php echo $users->num_rows; ?></p>
    </div>

    <div class="admin-card">
        <h3>Open Messages</h3>
        <p><?php echo $contacts->num_rows; ?></p>
    </div>

    <div class="admin-card">
        <h3>System Status</h3>
        <p>Active</p>
    </div>

</div>

<h2>Contact Requests</h2>

<table class="admin-table">
<tr>
    <th>Email</th>
    <th>Message</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while ($c = $contacts->fetch_assoc()): ?>
<tr>
    <td><?php echo htmlspecialchars($c['email']); ?></td>
    <td><?php echo htmlspecialchars($c['message']); ?></td>
    <td><?php echo htmlspecialchars($c['status']); ?></td>
    <td>
        <a href="admin_reset.php?user=<?php echo $c['id']; ?>">
            Handle
        </a>
    </td>
</tr>
<?php endwhile; ?>
</table>

<h2 style="margin-top:40px;">All Users</h2>

<table class="admin-table">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Action</th>
</tr>

<?php while ($row = $users->fetch_assoc()): ?>
<tr>
    <td><?php echo $row['user_id']; ?></td>
    <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['role']; ?></td>
    <td>
        <a href="admin.php?delete_user=<?php echo $row['user_id']; ?>"
           onclick="return confirm('Delete this user?')"
           style="color:red;font-weight:bold;">
           Delete
        </a>
    </td>
</tr>
<?php endwhile; ?>
</table>

</div>

</body>
</html>