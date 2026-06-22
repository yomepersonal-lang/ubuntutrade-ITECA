<?php
session_start();
require_once 'db_connect.php';
require_once 'auth.php';
require_once 'payfast_config.php';

if (!isLoggedIn()) {
    die("You must be logged in to checkout. <a href='../login.html'>Login</a>");
}

if (empty($_SESSION['cart'])) {
    die("Your cart is empty. <a href='../index.html'>Go back</a>");
}

$buyer_id = $_SESSION['user_id'];
$order_ids = [];
$total_amount = 0;

//buyer details
$user_query = mysqli_query($conn, "SELECT full_name, email FROM user WHERE user_id = $buyer_id");
$user = mysqli_fetch_assoc($user_query);
$full_name = $user['full_name'];
$email = $user['email'];

$name_parts = explode(' ', $full_name, 2);
$first_name = $name_parts[0];
$last_name = isset($name_parts[1]) ? $name_parts[1] : '';

// Start transaction to keep data consistent
mysqli_begin_transaction($conn);

try {

    foreach ($_SESSION['cart'] as $listing_id => $qty) {

        $price_result = mysqli_query($conn, "SELECT price, title FROM listing WHERE listing_id = $listing_id");
        $product = mysqli_fetch_assoc($price_result);
        $item_total = $product['price'] * $qty;
        $total_amount += $item_total;
        
        $sql = "INSERT INTO orders (buyer_id, listing_id, total_amount, order_status, payment_status) 
                VALUES ($buyer_id, $listing_id, $item_total, 'pending', 'unpaid')";
        mysqli_query($conn, $sql);
        $order_id = mysqli_insert_id($conn);
        $order_ids[] = $order_id;

        $update_sql = "UPDATE listing SET status='sold' WHERE listing_id=$listing_id";
        if (!mysqli_query($conn, $update_sql)) {
            throw new Exception("Failed to mark listing as sold: " . mysqli_error($conn));
        }
    }

    // Commit transaction
    mysqli_commit($conn);

    // Clear the cart
    unset($_SESSION['cart']);

    $_SESSION['last_order_ids'] = $order_ids;

    //payfast data
    $pfData = [
        'merchant_id' => PF_MERCHANT_ID,
        'merchant_key' => PF_MERCHANT_KEY,
        'return_url' => PF_RETURN_URL,
        'cancel_url' => PF_CANCEL_URL,
        'notify_url' => PF_NOTIFY_URL,
        'name_first' => $first_name,
        'name_last' => $last_name,
        'email_address' => $email,
        'm_payment_id' => implode(',', $order_ids),
        'amount' => number_format($total_amount, 2, '.', ''),
        'item_name' => 'UbuntuTrade Order',
        'item_description' => 'Payment for goods on UbuntuTrade'
    ];

    // Generate signature (PayFast official method)
    $pfOutput = '';
    foreach ($pfData as $key => $val) {
        if ($val !== '') {
            $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
        }
    }
    $pfOutput = rtrim($pfOutput, '&');
    if (!empty(PF_PASSPHRASE)) {
        $pfOutput .= '&passphrase=' . urlencode(trim(PF_PASSPHRASE));
    }
    $pfData['signature'] = md5($pfOutput);

    // Output form that auto-submits to PayFast
    echo '<!DOCTYPE html>';
    echo '<html><head><title>Redirecting to PayFast...</title>';
    echo '<link rel="stylesheet" href="../css/style.css">';
    echo '</head><body>';
    echo '<div class="container" style="text-align:center;padding:60px 20px;">';
    echo '<h2>Processing your order...</h2>';
    echo '<p style="color:#666;">Please wait, redirecting to secure payment page.</p>';
    echo '<form action="' . PF_PAYMENT_URL . '" method="post" id="pf_form">';
    foreach ($pfData as $name => $value) {
        echo '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '">';
    }
    echo '</form>';
    echo '<p><small>If you are not redirected automatically, click the button below.</small></p>';
    echo '<button onclick="document.getElementById(\'pf_form\').submit();" class="btn">Pay Now</button>';
    echo '<script>document.getElementById("pf_form").submit();</script>';
    echo '</div></body></html>';

} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    die("Error processing order: " . $e->getMessage());
}
?>