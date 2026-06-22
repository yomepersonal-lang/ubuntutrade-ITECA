<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';
include 'auth.php';

if (!isSeller()) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

$user_check = mysqli_query($conn, "SELECT verification_status FROM user WHERE user_id=$user_id");
$user_data = mysqli_fetch_assoc($user_check);
$verification_status = $user_data['verification_status'] ?? 'not_requested';

// Get listings
$sql = "SELECT * FROM listing WHERE seller_id=$user_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$listings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['verification_status'] = $verification_status;
    $listings[] = $row;
}
echo json_encode($listings);
?>