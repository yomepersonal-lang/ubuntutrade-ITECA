<?php
session_start();
include 'db_connect.php';


if ( !isset($_SESSION['user_id'])) 
{
    echo "Not logged in";
    exit;
}

$seller_id=(int)$_POST['seller_id'];
$rating=(int)$_POST['rating'];
$comment=mysqli_real_escape_string($conn, $_POST['comment']);
$buyer_id=$_SESSION['user_id'];


$sql="INSERT INTO review (reviewer_id, seller_id, rating, comment) 
        VALUES ($buyer_id, $seller_id, $rating, '$comment')";
mysqli_query($conn, $sql);


$product_id=(int)$_POST['product_id'];
header("Location: ../product.html?id=$product_id");

?>