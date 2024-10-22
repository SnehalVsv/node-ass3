<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        <a href="categories.php">Manage Categories</a>
        <a href="product.php">Manage Products</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
