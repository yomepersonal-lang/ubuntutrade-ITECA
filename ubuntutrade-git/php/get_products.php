<?php
header('Content-Type: application/json');
include 'db_connect.php';

$sql = "SELECT l.listing_id, l.title, l.price, l.image_url, l.category, l.status,
               u.full_name as seller_name, u.phone as seller_phone, 
               u.id_verified, u.verification_status 
        FROM listing l 
        JOIN user u ON l.seller_id = u.user_id 
        WHERE l.status = 'active' OR l.status = 'sold'
        ORDER BY l.created_at DESC";
$result = mysqli_query($conn, $sql);
$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['is_verified'] = ($row['verification_status'] === 'verified' || $row['id_verified'] == 1);
    $products[] = $row;
}
echo json_encode($products);
?>