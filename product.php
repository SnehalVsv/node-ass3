<?php
session_start();
require 'db.php';

$category_query = "SELECT * FROM category";
$categories = $conn->query($category_query);

$product_query = "SELECT p.*, c.categoryName 
                  FROM product p 
                  JOIN category c ON p.category = c.id";
$products = $conn->query($product_query);




if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Fetch the current product image to delete it
    $stmt = $conn->prepare("SELECT image FROM product WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $image_path = "images/" . $product['image'];

        // Delete the product
        $stmt = $conn->prepare("DELETE FROM product WHERE id = ?");
        $stmt->bind_param("i", $delete_id);

        if ($stmt->execute()) {
            // Remove the image file from the server
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            header("Location: product.php");
            exit();
        } else {
            echo "Error deleting product.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center p-3">Admin Dashboard</h3>
        <a href="dashboard.php">Dashboard Home</a>
        <a href="categories.php">Manage Categories</a>
        <a href="product.php">Manage Products</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2 class="mb-4">Manage Products</h2>

        <!-- Add Product Form -->
        <form action="add_product.php" method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="row">
        <div class="col-md-6">

            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" style="width: 500px;" required>
            </div>

            <div class="mb-3">
                <label for="product_price" class="form-label">Product Price</label>
                <input type="number" class="form-control" id="product_price" name="product_price" style="width: 500px;" required>
            </div>

            <div class="mb-3">
                <label for="product_description" class="form-label">Product Description</label>
                <textarea class="form-control" id="product_description" name="product_description" style="width: 500px;"></textarea>
            </div>
            </div>
            <div class="col-md-6">


            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-control" id="category_id" name="category_id" style="width: 500px;" required>
                    <option value="">Select Category</option>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo $category['categoryName']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="product_image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="product_image" name="product_image" style="width: 500px;" required>
            </div>
            </div>
            </div>


            <button type="submit" class="btn btn-primary">Add Product</button>


        </form>

        <!-- Products Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products->num_rows > 0): ?>
                    <?php while($row = $products->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['productName']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo $row['categoryName']; ?></td>
                            <td><img src="<?php echo $row['image']; ?>" alt="Product Image" width="50"></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Update</a>
                                <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
