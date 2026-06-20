<?php
ini_set('display_errors', 0);                   //delete users 
header('Content-Type: application/json');

require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/auth.php';

function json_error($msg) {
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}



if (!isAdmin()) json_error('Access denied');

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($user_id <= 0) json_error('Invalid user ID');
if ($user_id == $_SESSION['user_id']) json_error('Cannot delete yourself');


$sql = "DELETE FROM user WHERE user_id = $user_id";
if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true]);
} else {
    json_error(mysqli_error($conn));
}


?>