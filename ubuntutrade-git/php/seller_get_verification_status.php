<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    echo json_encode(['status' => 'not_logged_in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT verification_status FROM user WHERE user_id=$user_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
echo json_encode(['status' => $row['verification_status'] ?? 'not_requested']);
?>