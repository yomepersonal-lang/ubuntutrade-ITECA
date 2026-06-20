<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'auth.php';

if (!isAdmin()) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
if ($user_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid user ID']);
    exit;
}

// Don't allow unverifying self (admin)
if ($user_id == $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'error' => 'Cannot unverify yourself']);
    exit;
}

// Check if user is a seller
$check = mysqli_query($conn, "SELECT role_id FROM user WHERE user_id=$user_id");
$user = mysqli_fetch_assoc($check);
if ($user['role_id'] != 3 && $user['role_id'] != 1) {
    echo json_encode(['success' => false, 'error' => 'User is not a seller']);
    exit;
}

// Update user to not verified
$sql = "UPDATE user SET verification_status='not_requested', id_number=NULL WHERE user_id=$user_id";
if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true, 'message' => 'Seller unverified']);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>