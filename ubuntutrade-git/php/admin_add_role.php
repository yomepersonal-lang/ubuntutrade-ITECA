<?php
header('Content-Type: application/json');
require_once __DIR__ . '/auth.php';

if (!isAdmin()) 
{
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

$role_name=isset($_GET['role_name'])?mysqli_real_escape_string($conn, $_GET['role_name']):'';
$permissions=isset($_GET['permissions'])?mysqli_real_escape_string($conn, $_GET['permissions']):'';

if(empty($role_name))
     {
    echo json_encode(['success'=> false, 'error'=> 'Role name is required']);
    exit;
}

$sql="INSERT INTO role (role_name, permissions) VALUES ('$role_name', '$permissions')";
if (mysqli_query($conn, $sql)) 
{
    echo json_encode(['success' =>true]);
} else {
    echo json_encode(['success'=> false, 'error' => mysqli_error($conn)]);
}
?>