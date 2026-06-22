<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';
include 'auth.php';

if (!isSeller()) {
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$seller_id = $_SESSION['user_id'];

$sql = "SELECT o.order_id, o.total_amount, o.created_at,
               l.title as product_title,
               u.full_name as buyer_name, u.phone as buyer_phone
        FROM orders o
        JOIN listing l ON o.listing_id = l.listing_id
        JOIN user u ON o.buyer_id = u.user_id
        WHERE l.seller_id = $seller_id
        ORDER BY o.created_at DESC";

$result = mysqli_query($conn, $sql);
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}
echo json_encode($orders);
?>