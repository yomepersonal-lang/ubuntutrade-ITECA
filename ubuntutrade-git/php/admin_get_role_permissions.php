<?php
header('Content-Type: application/json');
require_once __DIR__ . '/auth.php';

if (!isAdmin()) {
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$role_id = isset($_GET['role_id']) ? (int)$_GET['role_id'] : 0;
if ($role_id <= 0) {
    echo json_encode(['error' => 'Invalid role ID']);
    exit;
}

$sql = "SELECT permissions FROM role WHERE role_id=$role_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
echo json_encode(['permissions' => $row['permissions'] ?? '']);
?>