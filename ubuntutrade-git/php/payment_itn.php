<?php
// payment_itn.php - called by PayFast after payment is made
require_once 'db_connect.php';
require_once 'payfast_config.php';

// Must respond quickly
header('HTTP/1.0 200 OK');
flush();

$pfError = false;
$pfErrMsg = '';
$pfParamString = '';

$pfHost = PF_SANDBOX ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';

// Get the data sent by PayFast
$pfData = $_POST;

// Remove slashes if magic quotes are on (old servers)
foreach ($pfData as $key => $val) {
    $pfData[$key] = stripslashes($val);
}

// Build parameter string for signature verification
foreach ($pfData as $key => $val) {
    if ($key !== 'signature') {
        $pfParamString .= $key . '=' . urlencode($val) . '&';
    }
}
$pfParamString = rtrim($pfParamString, '&');

// Add passphrase to the signature string if set
$pfTempParamString = $pfParamString;
if (!empty(PF_PASSPHRASE)) {
    $pfTempParamString .= '&passphrase=' . urlencode(trim(PF_PASSPHRASE));
}
$signature = md5($pfTempParamString);

// Verify signature
if ($pfData['signature'] !== $signature) {
    file_put_contents('payfast_errors.txt', date('Y-m-d H:i:s') . " - Invalid signature\n", FILE_APPEND);
    exit('Invalid Signature');
}

// Check payment status
if ($pfData['payment_status'] === 'COMPLETE') {
    $payment_id = $pfData['pf_payment_id'];
    $amount = $pfData['amount'];
    $order_ids = explode(',', $pfData['m_payment_id']);
    
    // Update each order
    foreach ($order_ids as $order_id) {
        mysqli_query($conn, "UPDATE orders SET payment_status='paid', order_status='paid' WHERE order_id='$order_id'");
        mysqli_query($conn, "INSERT INTO payment (order_id, amount, gateway_txn_id, escrow_released) 
                             VALUES ('$order_id', '$amount', '$payment_id', 0)");
    }
    
    // Log success for debugging
    file_put_contents('payfast_log.txt', date('Y-m-d H:i:s') . " - Payment completed for orders: " . $pfData['m_payment_id'] . "\n", FILE_APPEND);
}
?>