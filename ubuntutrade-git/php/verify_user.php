<?php
header('Content-Type: application/json');
include 'db_connect.php';

$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);

$sql = "SELECT user_id FROM user WHERE email='$email' AND phone='$phone'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No account found with these credentials.']);
}
?>