<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/auth.php';

if (!isAdmin()) 
{
    echo json_encode(['success'=>false, 'error'=> 'Access denied']);
    exit;
}

$listing_id=isset($_GET['listing_id'])?(int)$_GET['listing_id']:0;
if ($listing_id <= 0) 
{
    echo json_encode(['success'=> false, 'error' => 'Invalid listing ID']);
    exit;
}

$sql="DELETE FROM listing WHERE listing_id = $listing_id";
if (mysqli_query($conn, $sql)) 
{
    echo json_encode(['success'=>true]);
} 
else {
    echo json_encode(['success' => false, 'error' =>mysqli_error($conn)]);
}
?>