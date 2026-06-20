<?php
define('PF_SANDBOX', true);  // true = test mode, false = live

// Your PayFast Sandbox credentials (get from https://sandbox.payfast.co.za)
define('PF_MERCHANT_ID', 'Sandbox ID');     //Sandbox ID
define('PF_MERCHANT_KEY', 'Sandbox Key'); //Sandbox Key
define('PF_PASSPHRASE', 'passphrase'); //passphrase

// Your website URL
$base_url = 'https://ubuntutrade.page.gd';
define('PF_RETURN_URL', $base_url . '/php/payment_success.php');
define('PF_CANCEL_URL', $base_url . '/php/payment_cancel.php');
define('PF_NOTIFY_URL', $base_url . '/php/payment_itn.php');

// PayFast payment endpoint
if (PF_SANDBOX) {
    define('PF_PAYMENT_URL', 'https://sandbox.payfast.co.za/eng/process');
} else {
    define('PF_PAYMENT_URL', 'https://www.payfast.co.za/eng/process');
}
?>
