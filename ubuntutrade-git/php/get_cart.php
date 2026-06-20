<?php
session_start();
header('Content-Type: application/json');
include 'db_connect.php';

$cart = [];
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $result = mysqli_query($conn, "SELECT listing_id, title, price FROM listing WHERE listing_id IN ($ids)");
    while ($row = mysqli_fetch_assoc($result)) {
        $cart[] = $row;
    }
}
echo json_encode($cart);
?>