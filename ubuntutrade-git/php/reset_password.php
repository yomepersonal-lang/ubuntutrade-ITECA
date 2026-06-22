<?php
header('Content-Type: application/json');
include 'db_connect.php';

$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$new_password = $_POST['new_password'];


if (strlen($new_password) < 6) {
    echo json_encode(['success' => false, 'error' => 'Password must be at least 6 characters.']);
    exit;
}

$check = mysqli_query($conn, "SELECT user_id FROM user WHERE email='$email' AND phone='$phone'");
if (mysqli_num_rows($check) == 0) {
    echo json_encode(['success' => false, 'error' => 'User not found.']);
    exit;
}

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$sql = "UPDATE user SET password_hash='$hashed_password' WHERE email='$email' AND phone='$phone'";
if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . mysqli_error($conn)]);
}
?>