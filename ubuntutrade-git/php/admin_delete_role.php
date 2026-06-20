<?php
header('Content-Type: application/json');
require_once __DIR__ . '/auth.php';

if (!isAdmin()) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

$role_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($role_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid role ID']);
    exit;
}

// prevent deleting built-in roles (optional)
if ($role_id <= 3) {
    echo json_encode(['success' => false, 'error' => 'Cannot delete system roles (Admin, Buyer, Seller)']);
    exit;
}

$sql = "DELETE FROM role WHERE role_id = $role_id";
if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>