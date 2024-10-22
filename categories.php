<?php
session_start();
require 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = $_POST['category_name'];

    $stmt = $conn->prepare("INSERT INTO category (categoryName) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM category WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
}

$query = "SELECT * FROM category";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    display: flex;
    min-height: 100vh;
    margin: 0;
    font-family: 'Arial', sans-serif;
    background-color: #f1f3f5; /* Light background for better contrast */
}

.sidebar {
    width: 250px;
    background-color: #00ccff; /* Darker shade for a sleek look */
    color: white;
    position: fixed;
    height: 100%;
    display: flex;
    flex-direction: column;
    padding-top: 20px;
    transition: width 0.3s ease; /* Smooth transition for width changes */
}

.sidebar a {
    color: #dee2e6; /* Softer white for less contrast */
    text-decoration: none;
    padding: 15px 20px;
    display: block;
    border-radius: 4px; /* Rounded edges for link boxes */
    transition: background-color 0.3s ease, padding-left 0.3s ease; /* Smooth hover effects */
}

.sidebar a:hover {
    background-color: #495057;
    padding-left: 30px; /* Slight padding increase on hover */
    color: #ffffff; /* Brighter text on hover */
}

.sidebar a.active {
    background-color: #495057; /* Active link indicator */
    color: #ffffff;
    font-weight: bold;
}

.content {
    margin-left: 250px;
    padding: 20px;
    width: calc(100% - 250px); /* Responsive width for content */
    transition: margin-left 0.3s ease; /* Smooth transition if sidebar width changes */
}

@media (max-width: 768px) {
    .sidebar {
        width: 200px; /* Smaller sidebar for mobile */
    }
    .content {
        margin-left: 200px; /* Adjust content margin for smaller sidebar */
        width: calc(100% - 200px);
    }
}

    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-center p-3">Admin Dashboard</h3>
        <a href="dashboard.php">Dashboard Home</a>
        <a href="categories.php">Manage Categories</a>
        <a href="product.php">Manage Products</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h2 class="mb-4">Manage Categories</h2>

        <form action="" method="POST" class="mb-4">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control " id="category_name" name="category_name" style="width: 500px;" required>
            </div>
           
            <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
        </form>

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['categoryName']; ?></td>
                            <td>
                                <a href="edit_category.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Update</a>
                                <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
