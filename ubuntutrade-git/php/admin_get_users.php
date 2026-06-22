<?php
header('Content-Type: application/json');
require_once __DIR__ . '/auth.php';

if (!isAdmin()) {
    echo json_encode(['error' => 'Access denied. Admin only.']);
    exit;
}

$sql = "SELECT u.user_id, u.full_name, u.email, u.phone, u.role_id, r.role_name, 
               u.verification_status, u.id_number
        FROM user u 
        JOIN role r ON u.role_id = r.role_id
        ORDER BY u.user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['error' => 'Database query failed: ' . mysqli_error($conn)]);
    exit;
}

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}
echo json_encode($users);
?>