<?php
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'auth.php';

if (!isAdmin()) {
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$sql = "SELECT vr.*, u.full_name, u.email, u.phone 
        FROM verification_requests vr 
        JOIN user u ON vr.user_id = u.user_id 
        WHERE vr.status = 'pending' 
        ORDER BY vr.requested_at ASC";
$result = mysqli_query($conn, $sql);
$requests = [];
while ($row = mysqli_fetch_assoc($result)) {
    $requests[] = $row;
}
echo json_encode($requests);
?>