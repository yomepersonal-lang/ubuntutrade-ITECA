<?php
// place_order.php - creates orders from cart and sends user to PayFast
session_start();
require_once 'db_connect.php';
require_once 'auth.php';
require_once 'payfast_config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    echo "You must be logged in to checkout. <a href='../login.html'>Login</a>";
    exit;
}

// Check if cart is empty
if (empty($_SESSION['cart'])) {
    echo "Your cart is empty. <a href='../index.html'>Go back</a>";
    exit;
}

$buyer_id = $_SESSION['user_id'];
$order_ids = [];
$total_amount = 0;

// Get buyer details from database
$user_query = mysqli_query($conn, "SELECT full_name, email FROM user WHERE user_id = $buyer_id");
$user = mysqli_fetch_assoc($user_query);
$full_name = $user['full_name'];
$email = $user['email'];

// Split full name into first and last (simple way)
$name_parts = explode(' ', $full_name, 2);
$first_name = $name_parts[0];
$last_name = isset($name_parts[1]) ? $name_parts[1] : '';

// Create one order per product (simplified)
foreach ($_SESSION['cart'] as $listing_id => $qty) {
    // Get product price
    $price_result = mysqli_query($conn, "SELECT price, title FROM listing WHERE listing_id = $listing_id");
    $product = mysqli_fetch_assoc($price_result);
    $item_total = $product['price'] * $qty;
    $total_amount += $item_total;
    
    // Insert order
    $sql = "INSERT INTO orders (buyer_id, listing_id, total_amount, order_status, payment_status) 
            VALUES ($buyer_id, $listing_id, $item_total, 'pending', 'unpaid')";
    mysqli_query($conn, $sql);
    $order_ids[] = mysqli_insert_id($conn);
}

// Clear the cart
unset($_SESSION['cart']);

// Store order IDs in session for later confirmation (optional)
$_SESSION['last_order_ids'] = $order_ids;

// Prepare data for PayFast
$data = [
    'merchant_id' => PF_MERCHANT_ID,
    'merchant_key' => PF_MERCHANT_KEY,
    'return_url' => PF_RETURN_URL,
    'cancel_url' => PF_CANCEL_URL,
    'notify_url' => PF_NOTIFY_URL,
    'name_first' => $first_name,
    'name_last' => $last_name,
    'email_address' => $email,
    'm_payment_id' => implode(',', $order_ids), // send all order IDs
    'amount' => number_format($total_amount, 2, '.', ''),
    'item_name' => 'UbuntuTrade Order',
    'item_description' => 'Payment for goods on UbuntuTrade'
];

// Generate security signature (required by PayFast)
$signature_string = '';
foreach ($data as $key => $val) {
    if ($val !== '') {
        $signature_string .= $key . '=' . urlencode(trim($val)) . '&';
    }
}
$signature_string = rtrim($signature_string, '&');
if (!empty(PF_PASSPHRASE)) {
    $signature_string .= '&passphrase=' . urlencode(trim(PF_PASSPHRASE));
}
$data['signature'] = md5($signature_string);

// Output HTML form that auto-submits to PayFast
echo '<!DOCTYPE html>';
echo '<html><head><title>Redirecting to PayFast...</title></head><body>';
echo '<form action="' . PF_PAYMENT_URL . '" method="post" id="payfast_form">';
foreach ($data as $name => $value) {
    echo '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '">';
}
echo '</form>';
echo '<p>Please wait, redirecting to PayFast payment page...</p>';
echo '<script>document.getElementById("payfast_form").submit();</script>';
echo '</body></html>';
?>