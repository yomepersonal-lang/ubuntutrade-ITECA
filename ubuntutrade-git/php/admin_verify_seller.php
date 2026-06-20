<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'auth.php';

if (!isAdmin()) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

$request_id = isset($_POST['request_id']) ? (int)$_POST['request_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : ''; // 'approve' or 'reject'

if ($request_id <= 0 || !in_array($action, ['approve', 'reject'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$admin_id = $_SESSION['user_id'];

// Get the user_id from the request
$req_query = mysqli_query($conn, "SELECT user_id FROM verification_requests WHERE request_id=$request_id");
$req_data = mysqli_fetch_assoc($req_query);
if (!$req_data) {
    echo json_encode(['success' => false, 'error' => 'Request not found']);
    exit;
}
$user_id = $req_data['user_id'];

// Update the verification request
$status = ($action === 'approve') ? 'approved' : 'rejected';
$sql = "UPDATE verification_requests SET status='$status', reviewed_at=NOW(), reviewed_by=$admin_id WHERE request_id=$request_id";
mysqli_query($conn, $sql);

// Update user's verification status
if ($action === 'approve') {
    mysqli_query($conn, "UPDATE user SET verification_status='verified' WHERE user_id=$user_id");
    echo json_encode(['success' => true, 'message' => 'Seller verified successfully']);
} else {
    mysqli_query($conn, "UPDATE user SET verification_status='rejected' WHERE user_id=$user_id");
    echo json_encode(['success' => true, 'message' => 'Verification request rejected']);
}
?>