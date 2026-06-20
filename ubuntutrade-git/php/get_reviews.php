<?php
header('Content-Type: application/json');
include 'db_connect.php';

$seller_id = isset($_GET['seller_id']) ? (int)$_GET['seller_id'] : 0;
if (!$seller_id && isset($_GET['id'])) {
    // if they pass listing_id, resolve seller
    $listing_id = (int)$_GET['id'];
    $res = mysqli_query($conn, "SELECT seller_id FROM listing WHERE listing_id=$listing_id");
    if ($r = mysqli_fetch_assoc($res)) $seller_id = $r['seller_id'];
}
if (!$seller_id) {
    echo json_encode([]);
    exit;
}
$sql = "SELECT r.*, u.full_name FROM review r 
        JOIN user u ON r.reviewer_id = u.user_id 
        WHERE r.seller_id = $seller_id 
        ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $sql);
$reviews = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}
echo json_encode($reviews);
?>