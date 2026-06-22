<?php

define('PF_SANDBOX', true);  

define('PF_MERCHANT_ID', '****');     //Sandbox ID
define('PF_MERCHANT_KEY', '****'); // Sandbox Key
define('PF_PASSPHRASE', '****');

$base_url = 'https://ubuntutrade.page.gd';
define('PF_RETURN_URL', $base_url . '/php/payment_success.php');
define('PF_CANCEL_URL', $base_url . '/php/payment_cancel.php');
define('PF_NOTIFY_URL', $base_url . '/php/payment_itn.php');

if (PF_SANDBOX) {
    define('PF_PAYMENT_URL', 'https://sandbox.payfast.co.za/eng/process');
} else {
    define('PF_PAYMENT_URL', 'https://www.payfast.co.za/eng/process');
}
?>
