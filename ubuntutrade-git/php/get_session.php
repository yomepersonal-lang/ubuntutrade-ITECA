<?php
session_start();
header('Content-Type: application/json');

$response = ['logged_in' => false];
if (isset($_SESSION['user_id'])) {
    $response = [
        'logged_in' => true,
        'full_name' => $_SESSION['full_name'],
        'user_id' => $_SESSION['user_id'],
        'role_id' => $_SESSION['role_id'],
        'is_seller' => ($_SESSION['role_id'] == 3 || $_SESSION['role_id'] == 1),
        'is_admin' => ($_SESSION['role_id'] == 1)
    ];
}
echo json_encode($response);
?>