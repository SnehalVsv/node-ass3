

<?php
session_start();
require 'db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"></body>

<style>
    body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #e0f7fa; /* Lighter teal background */
}

.login-box {
    background: #ffffff;
    padding: 25px;
    border-radius: 15px; /* Softer, rounder edges */
    box-shadow: 0 5px 15px rgba(0, 128, 128, 0.2); /* Stronger shadow with teal tint */
    max-width: 450px;
    width: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Added hover effect */
}

.login-box:hover {
    transform: scale(1.05); /* Slightly larger on hover */
    box-shadow: 0 10px 30px rgba(0, 128, 128, 0.3); /* Darker and larger shadow on hover */
}

.login-header {
    text-align: center;
    margin-bottom: 25px;
    color: #00796b; /* Darker teal for header text */
    font-weight: bold;
}

</style>
</head>
<body>
<div class="login-box">
    <h2 class="login-header">Admin Login</h2>
    <form action="login.php" method="post">
        <div class="mb-3 form-label">
            <label for="username" class="form-label">Username:</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <br>
        <div class="d-grid ">
            <input type="submit" value="Login" class="btn btn-success btn-block">
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        if ($password === $admin['password']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Admin not found!";
    }
}
?>
