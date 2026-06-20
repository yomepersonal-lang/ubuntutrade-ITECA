<?php
session_start();
include 'db_connect.php';
include 'auth.php';
if (!isSeller()) exit;
$id = (int)$_POST['id'];
$title = mysqli_real_escape_string($conn, $_POST['title']);
$desc = mysqli_real_escape_string($conn, $_POST['description']);
$price = (float)$_POST['price'];
$status = mysqli_real_escape_string($conn, $_POST['status']);
$sql = "UPDATE listing SET title='$title', description='$desc', price=$price, status='$status' 
        WHERE listing_id=$id AND seller_id={$_SESSION['user_id']}";
mysqli_query($conn, $sql);
header("Location: ../seller/dashboard.html");
?>