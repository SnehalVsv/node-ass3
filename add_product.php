<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productDescription = $_POST['product_description'];
    $categoryId = $_POST['category_id'];

    $imageName = $_FILES['product_image']['name'];
    $imageTemp = $_FILES['product_image']['tmp_name'];
    $imageFolder = 'images/';
    $imagePath = $imageFolder . basename($imageName);
    
    $newWidth = 300;  
    $newHeight = 300; 
    if (move_uploaded_file($imageTemp, $imagePath)) {
        resizeImage($imagePath, $newWidth, $newHeight);

        $query = "INSERT INTO product (productName, price, description, image, category) 
                  VALUES ('$productName', '$productPrice', '$productDescription', '$imageName', '$categoryId')";

        if ($conn->query($query)) {
            $_SESSION['message'] = "Product added successfully!";
        } else {
            $_SESSION['error'] = "Error adding product: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Failed to upload the image.";
    }

    header('Location: product.php');
    exit();
}

function resizeImage($file, $newWidth, $newHeight) {
    list($width, $height) = getimagesize($file);
    
    $imgRatio = $width / $height;
    if ($newWidth / $newHeight > $imgRatio) {
        $newWidth = $newHeight * $imgRatio;
    } else {
        $newHeight = $newWidth / $imgRatio;
    }

    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    $imageType = mime_content_type($file);
    if ($imageType == "image/jpeg") {
        $source = imagecreatefromjpeg($file);
    } elseif ($imageType == "image/png") {
        $source = imagecreatefrompng($file);
    } elseif ($imageType == "image/gif") {
        $source = imagecreatefromgif($file);
    } else {
        return false; 
    }

    imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if ($imageType == "image/jpeg") {
        imagejpeg($newImage, $file, 80); 
    } elseif ($imageType == "image/png") {
        imagepng($newImage, $file, 8); 
    } elseif ($imageType == "image/gif") {
        imagegif($newImage, $file);
    }

    imagedestroy($newImage);
    imagedestroy($source);
}

?>
