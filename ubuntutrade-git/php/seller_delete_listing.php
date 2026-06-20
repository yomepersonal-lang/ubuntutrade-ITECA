<?php
session_start();
include 'db_connect.php';
include 'auth.php';
if (!isSeller()) exit;
$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM listing WHERE listing_id=$id AND seller_id={$_SESSION['user_id']}");
header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>