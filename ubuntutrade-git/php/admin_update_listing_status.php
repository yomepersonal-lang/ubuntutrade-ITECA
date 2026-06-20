<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/auth.php';

if (!isAdmin()) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

$listing_id = isset($_GET['listing_id']) ? (int)$_GET['listing_id'] : 0;
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

if ($listing_id <= 0 || !in_array($status, ['active', 'sold', 'inactive'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit;
}

$sql = "UPDATE listing SET status = '$status' WHERE listing_id = $listing_id";
if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>