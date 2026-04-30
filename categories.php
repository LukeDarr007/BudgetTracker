<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT user_id FROM user WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_category'])) {

    $name = trim($_POST['name'] ?? '');
    $type = $_POST['type'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $limit = (float)($_POST['spending_limit'] ?? 0);

    if ($name === '') {
        $message = "Name required";
    } else {
        $stmt = $conn->prepare("INSERT INTO category (user_id, name, type, description, spending_limit) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $user_id, $name, $type, $description, $limit);
        $stmt->execute();

        header("Location: categories.php");
        exit();
    }
}

if (isset($_GET['delete'])) {

    $category_id = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM category WHERE category_id = ? AND user_id = ?");
    $stmt->bind_param("is", $category_id, $user_id);
    $stmt->execute();

    header("Location: categories.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit_category'])) {

    $category_id = (int)$_POST['category_id'];
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $description = trim($_POST['description']);
    $limit = (float)$_POST['spending_limit'];

    $stmt = $conn->prepare("UPDATE category SET name=?, type=?, description=?, spending_limit=? WHERE category_id=? AND user_id=?");
    $stmt->bind_param("sssdis", $name, $type, $description, $limit, $category_id, $user_id);
    $stmt->execute();

    header("Location: categories.php");
    exit();
}

$stmt = $conn->prepare("SELECT category_id, name, type, description, spending_limit FROM category WHERE user_id=? ORDER BY category_id DESC");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

$editItem = null;

if (isset($_GET['edit'])) {

    $category_id = (int)$_GET['edit'];

    $stmt = $conn->prepare("SELECT * FROM category WHERE category_id=? AND user_id=?");
    $stmt->bind_param("is", $category_id, $user_id);
    $stmt->execute();

    $editItem = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Categories</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>

<body class="categories-page">

<?php include 'navbar.php'; ?>


<main class="categories-main">

<h2>Categories</h2>

<?php if ($message): ?>
<p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<div class="add-category">
<form method="POST">

<input type="text" name="name" placeholder="Category name" required>

<select name="type">
<option value="Income">Income</option>
<option value="Expense">Expense</option>
</select>

<input type="text" name="description" placeholder="Description">

<input type="number" step="0.01" name="spending_limit" placeholder="Spending limit (£)" min="0">

<button type="submit" name="add_category">Add</button>

</form>
</div>

<?php if ($editItem): ?>
<div class="edit-category">
<form method="POST">

<input type="hidden" name="category_id" value="<?php echo $editItem['category_id']; ?>">

<input type="text" name="name" value="<?php echo htmlspecialchars($editItem['name']); ?>">

<select name="type">
<option value="Income" <?php if($editItem['type']=="Income") echo "selected"; ?>>Income</option>
<option value="Expense" <?php if($editItem['type']=="Expense") echo "selected"; ?>>Expense</option>
</select>

<input type="text" name="description" value="<?php echo htmlspecialchars($editItem['description']); ?>">

<input type="number" step="0.01" name="spending_limit" value="<?php echo $editItem['spending_limit']; ?>">

<button type="submit" name="edit_category">Save</button>

</form>
</div>
<?php endif; ?>

<table class="categories-table">
<tr>
<th>Name</th>
<th>Type</th>
<th>Description</th>
<th>Limit (£)</th>
<th>Actions</th>
</tr>

<?php foreach ($categories as $c): ?>
<tr>
<td><?php echo htmlspecialchars($c['name']); ?></td>
<td><?php echo htmlspecialchars($c['type']); ?></td>
<td><?php echo htmlspecialchars($c['description']); ?></td>
<td>£<?php echo number_format($c['spending_limit'],2); ?></td>
<td>
<a href="categories.php?edit=<?php echo $c['category_id']; ?>">Edit</a>
<a href="categories.php?delete=<?php echo $c['category_id']; ?>" onclick="return confirm('Delete this category?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>

</table>

</main>

</body>
</html>