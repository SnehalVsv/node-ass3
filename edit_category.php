<?php
session_start();
require 'db.php'; 

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM category WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    } else {
        echo "Category not found!";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];

    $stmt = $conn->prepare("UPDATE category SET categoryName = ? WHERE id = ?");
    $stmt->bind_param("si", $category_name, $category_id);

    if ($stmt->execute()) {
        header("Location: categories.php"); 
        exit();
    } else {
        echo "Error updating category.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Category</title>
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


        <h2>Update Category</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" style="width: 500px;" value="<?php echo $category['categoryName']; ?>" required>
            </div>
           
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
</body>
</html>
