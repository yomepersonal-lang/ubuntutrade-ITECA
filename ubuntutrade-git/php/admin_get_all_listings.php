<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/auth.php';

if (!isAdmin()) {
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$sql = "SELECT l.listing_id, l.title, l.price, l.status, l.seller_id, u.full_name as seller_name
        FROM listing l
        JOIN user u ON l.seller_id = u.user_id
        ORDER BY l.listing_id DESC";
$result = mysqli_query($conn, $sql);
$listings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $listings[] = $row;
}
echo json_encode($listings);
?>