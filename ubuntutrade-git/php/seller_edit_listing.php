<?php
session_start();
include 'db_connect.php';
include 'auth.php';

if (!isSeller()) {
    echo "Access denied. You must be a seller.";
    exit;
}


$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title = isset($_POST['title']) ? mysqli_real_escape_string($conn, $_POST['title']) : '';
$desc = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
$price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
$status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : 'active';
$cat = isset($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : '';

if ($id <= 0) {
    header("Location: ../seller/dashboard.html?error=invalid_id");
    exit;
}

if ($price <= 0) {
    header("Location: ../seller/edit_listing.php?id=$id&error=invalid_price");
    exit;
}

// Check if the listing belongs to this seller (security check)
$check = mysqli_query($conn, "SELECT seller_id FROM listing WHERE listing_id=$id");
$row = mysqli_fetch_assoc($check);
if (!$row || $row['seller_id'] != $_SESSION['user_id']) {
    echo "You don't have permission to edit this listing.";
    exit;
}

$sql = "UPDATE listing 
        SET title='$title', description='$desc', price=$price, status='$status', category='$cat' 
        WHERE listing_id=$id AND seller_id={$_SESSION['user_id']}";

if (mysqli_query($conn, $sql)) {
    header("Location: ../seller/dashboard.html?success=updated");
} else {
    header("Location: ../seller/edit_listing.php?id=$id&error=db");
}
exit;
?>