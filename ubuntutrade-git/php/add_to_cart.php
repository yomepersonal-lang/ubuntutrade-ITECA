<?php
session_start();
$id = $_GET['id'];
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$_SESSION['cart'][$id] = 1; // quantity = 1 for simplicity
header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>