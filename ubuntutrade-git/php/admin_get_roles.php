<?php
header('Content-Type: application/json');
require_once __DIR__ . '/auth.php';

if (!isAdmin()) {
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM role ORDER BY role_id");
if (!$result) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}
$roles = [];
while ($row = mysqli_fetch_assoc($result)) {
    $roles[] = $row;
}
echo json_encode($roles);
?>