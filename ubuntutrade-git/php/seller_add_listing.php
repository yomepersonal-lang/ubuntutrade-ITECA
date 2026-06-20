<?php
session_start();
include 'db_connect.php';
include 'auth.php';
if (!isSeller()) {
    echo "Access denied";
    exit;
}
$title = mysqli_real_escape_string($conn, $_POST['title']);
$desc = mysqli_real_escape_string($conn, $_POST['description']);
$price = (float)$_POST['price'];
$cat = mysqli_real_escape_string($conn, $_POST['category']);
$image = $_FILES['image']['name'];
$target = "../uploads/" . basename($image);
move_uploaded_file($_FILES['image']['tmp_name'], $target);
$sql = "INSERT INTO listing (seller_id, title, description, price, category, image_url) 
        VALUES ({$_SESSION['user_id']}, '$title', '$desc', $price, '$cat', '$image')";
mysqli_query($conn, $sql);
header("Location: ../seller/dashboard.html");
?>