<?php
header('Content-Type: application/json');
require_once __DIR__ . '/auth.php';

if (!isAdmin()) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

$role_id = isset($_GET['role_id']) ? (int)$_GET['role_id'] : 0;
$role_name = isset($_GET['role_name']) ? mysqli_real_escape_string($conn, $_GET['role_name']) : '';
$permissions = isset($_GET['permissions']) ? mysqli_real_escape_string($conn, $_GET['permissions']) : '';

if ($role_id <= 0 || empty($role_name)) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit;
}

$sql = "UPDATE role SET role_name='$role_name', permissions='$permissions' WHERE role_id=$role_id";
if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>