<?php
session_start();
include 'db_connect.php';
include 'auth.php';

if (!isLoggedIn() || empty($_SESSION['last_orders'])) {
    header("Location: ../index.html");
    exit;
}
foreach ($_SESSION['last_orders'] as $order_id) {
    mysqli_query($conn, "UPDATE orders SET payment_status='escrow_held', order_status='paid' WHERE order_id=$order_id");
    mysqli_query($conn, "INSERT INTO payment (order_id, amount, gateway_txn_id, escrow_released) 
                         SELECT order_id, total_amount, 'SIM_" . time() . "', 0 FROM orders WHERE order_id=$order_id");
}
unset($_SESSION['last_orders']);
header("Location: ../payment_success.html");
?>