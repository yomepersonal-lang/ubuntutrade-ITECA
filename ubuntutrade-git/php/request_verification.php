<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

// Only sellers can request verification
if (!isSeller()) {
    echo json_encode(['success' => false, 'error' => 'Only sellers can request verification']);
    exit;
}

$user_id = $_SESSION['user_id'];
$id_number = trim($_POST['id_number'] ?? '');

// Validate SA ID number - 13 digits
if (!preg_match('/^[0-9]{13}$/', $id_number)) {
    echo json_encode(['success' => false, 'error' => 'Invalid SA ID number. Must be 13 digits.']);
    exit;
}

// Check if user already has a pending request
$check = mysqli_query($conn, "SELECT request_id FROM verification_requests WHERE user_id=$user_id AND status='pending'");
if (mysqli_num_rows($check) > 0) {
    echo json_encode(['success' => false, 'error' => 'You already have a pending verification request.']);
    exit;
}

// Check if user is already verified
$user_check = mysqli_query($conn, "SELECT verification_status FROM user WHERE user_id=$user_id");
$user_data = mysqli_fetch_assoc($user_check);
if ($user_data['verification_status'] === 'verified') {
    echo json_encode(['success' => false, 'error' => 'You are already verified.']);
    exit;
}

// Insert verification request
$sql = "INSERT INTO verification_requests (user_id, id_number, status) VALUES ($user_id, '$id_number', 'pending')";
if (mysqli_query($conn, $sql)) {
    // Update user status to pending
    mysqli_query($conn, "UPDATE user SET verification_status='pending', id_number='$id_number' WHERE user_id=$user_id");
    echo json_encode(['success' => true, 'message' => 'Verification request submitted. Waiting for admin approval.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . mysqli_error($conn)]);
}
?>