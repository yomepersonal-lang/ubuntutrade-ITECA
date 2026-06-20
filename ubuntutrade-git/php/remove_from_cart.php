<?php
session_start();
$id = $_GET['id'];
unset($_SESSION['cart'][$id]);
header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>