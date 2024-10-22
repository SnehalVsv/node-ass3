<?php
require 'db.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found!";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];
    $category_id = $_POST['category_id'];

    if (!empty($_FILES['product_image']['name'])) {
        $image = $_FILES['product_image']['name'];
        $image_tmp = $_FILES['product_image']['tmp_name'];
        move_uploaded_file($image_tmp, "images/$image");

        $stmt = $conn->prepare("UPDATE product SET productName = ?, price = ?, description = ?, category = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sdsisi", $product_name, $product_price, $product_description, $category_id, $image, $product_id);
    } else {
        $stmt = $conn->prepare("UPDATE product SET productName = ?, price = ?, description = ?, category = ? WHERE id = ?");
        $stmt->bind_param("sdsii", $product_name, $product_price, $product_description, $category_id, $product_id);
    }

    if ($stmt->execute()) {
        header("Location: product.php");
        exit();
    } else {
        echo "Error updating product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    display: flex;
    min-height: 100vh;
    margin: 0;
    font-family: 'Arial', sans-serif;
    background-color: #00ccff; /* Light background for better contrast */
}

.sidebar {
    width: 250px;
    background-color: #212529; /* Darker shade for a sleek look */
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


        <h2>Update Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $product['productName']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="product_price" class="form-label">Product Price</label>
                <input type="number" class="form-control" id="product_price" name="product_price" value="<?php echo $product['price']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="product_description" class="form-label">Product Description</label>
                <textarea class="form-control" id="product_description" name="product_description"><?php echo $product['description']; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php
                    $categories = $conn->query("SELECT * FROM category");
                    while ($category = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>" <?php if ($product['category'] == $category['id']) echo 'selected'; ?>>
                            <?php echo $category['categoryName']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="product_image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="product_image" name="product_image">
                <small>Current Image: <img src="uploads/<?php echo $product['image']; ?>" alt="Product Image" width="100"></small>
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</body>
</html>
