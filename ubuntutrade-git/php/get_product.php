<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';

$id = (int)$_GET['id'];
$sql = "SELECT l.*, u.full_name as seller_name, u.phone as seller_phone, 
               u.id_verified, u.user_id as seller_id, u.verification_status 
        FROM listing l 
        JOIN user u ON l.seller_id = u.user_id 
        WHERE l.listing_id = $id";
$result = mysqli_query($conn, $sql);
if ($row = mysqli_fetch_assoc($result)) {
    $row['is_verified'] = ($row['verification_status'] === 'verified' || $row['id_verified'] == 1);
    $row['can_buy'] = isset($_SESSION['user_id']) && $_SESSION['user_id'] != $row['seller_id'] && $row['status'] !== 'sold';
    $row['logged_in'] = isset($_SESSION['user_id']);
    $row['current_user_id'] = $_SESSION['user_id'] ?? 0;
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Product not found']);
}
?>