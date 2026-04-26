<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $description = trim($_POST['description']);

    if ($name !== "") {
        $stmt = $conn->prepare("INSERT INTO category (name, type, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $type, $description);
        $stmt->execute();
        $message = "Category added";
    }
}

if (isset($_GET['delete'])) {
    $category_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM category WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    header("Location: categories.php");
    exit();
}

if (isset($_POST['edit_category'])) {
    $category_id = intval($_POST['category_id']);
    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $description = trim($_POST['description']);

    $stmt = $conn->prepare("UPDATE category SET name=?, type=?, description=? WHERE category_id=?");
    $stmt->bind_param("sssi", $name, $type, $description, $category_id);
    $stmt->execute();
    header("Location: categories.php");
    exit();
}

$result = $conn->query("SELECT * FROM category ORDER BY category_id DESC");
$categories = $result->fetch_all(MYSQLI_ASSOC);

$editItem = null;

if (isset($_GET['edit'])) {
    $category_id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM category WHERE category_id=$category_id");
    $editItem = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Categories</title>
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>
<body class="categories-page">

<nav class="navbar">
<div class="dashboard-logo">
<a href="dashboard.php"><img src="logo.png"></a>
</div>
<ul class="dashboard-nav-links">
<li><a href="dashboard.php">Dashboard</a></li>
<li><a href="account.php">Account</a></li>
<li><a href="expenses.php">Expenses</a></li>
<li><a href="budgets.php">Budgets</a></li>
<li><a href="income.php">Income</a></li>
<li><a class="active" href="categories.php">Categories</a></li>
<li><a href="monthly_summary.php">Monthly Summary</a></li>
</ul>
</nav>

<main class="categories-main">

<h2>Categories</h2>

<?php if ($message): ?>
<p><?php echo $message; ?></p>
<?php endif; ?>

<div class="add-category">
<form method="POST">
<input type="text" name="name" placeholder="Category name" required>
<select name="type">
<option value="Income">Income</option>
<option value="Expense">Expense</option>
</select>
<input type="text" name="description" placeholder="Description">
<button type="submit" name="add_category">Add</button>
</form>
</div>

<?php if ($editItem): ?>
<div class="edit-category">
<form method="POST">
<input type="hidden" name="category_id" value="<?php echo $editItem['category_id']; ?>">
<input type="text" name="name" value="<?php echo htmlspecialchars($editItem['name']); ?>" required>
<select name="type">
<option value="Income" <?php if($editItem['type']=="Income") echo "selected"; ?>>Income</option>
<option value="Expense" <?php if($editItem['type']=="Expense") echo "selected"; ?>>Expense</option>
</select>
<input type="text" name="description" value="<?php echo htmlspecialchars($editItem['description']); ?>">
<button type="submit" name="edit_category">Save</button>
</form>
</div>
<?php endif; ?>

<table class="categories-table">
<tr>
<th>Name</th>
<th>Type</th>
<th>Description</th>
<th>Actions</th>
</tr>

<?php foreach ($categories as $c): ?>
<tr>
<td><?php echo htmlspecialchars($c['name']); ?></td>
<td><?php echo htmlspecialchars($c['type']); ?></td>
<td><?php echo htmlspecialchars($c['description']); ?></td>
<td>
<a href="categories.php?edit=<?php echo $c['category_id']; ?>">Edit</a>
<a href="categories.php?delete=<?php echo $c['category_id']; ?>" onclick="return confirm('Delete this category?')">Delete</a>
</td>
</tr>
<?php endforeach; ?>

</table>

</main>

<footer class="index-footer">
    <div class="index-footer-container">
        <div class="index-footer-column">
            <img src="logo.png" class="footer-logo">
            <p>© 2026 Buff Budgets. All rights reserved.</p>
        </div>

        <div class="index-footer-column">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="account.php">Account</a></li>
                <li><a href="expenses.php">Expenses</a></li>
                <li><a href="income.php">Income</a></li>
                <li><a href="budgets.php">Budgets</a></li>
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
