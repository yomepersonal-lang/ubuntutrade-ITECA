<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';
include 'auth.php';

if (!isAdmin()) {
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$sql = "SELECT o.order_id, o.total_amount, o.created_at,
               l.title as product_title,
               buyer.full_name as buyer_name,
               seller.full_name as seller_name
        FROM orders o
        JOIN listing l ON o.listing_id = l.listing_id
        JOIN user buyer ON o.buyer_id = buyer.user_id
        JOIN user seller ON l.seller_id = seller.user_id
        ORDER BY o.created_at DESC";

$result = mysqli_query($conn, $sql);
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}
echo json_encode($orders);
?>