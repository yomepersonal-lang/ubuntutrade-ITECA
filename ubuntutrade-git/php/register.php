<?php
session_start();
include 'db_connect.php';

$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = (int)$_POST['role']; // 2=buyer, 3=seller

// check if email exists
$check = mysqli_query($conn, "SELECT user_id FROM user WHERE email='$email'");
if (mysqli_num_rows($check) > 0) {
    header("Location: ../register.html?error=emailexists");
    exit;
}

$sql = "INSERT INTO user (email, phone, password_hash, full_name, role_id) 
        VALUES ('$email', '$phone', '$password', '$full_name', $role)";
if (mysqli_query($conn, $sql)) {
    header("Location: ../login.html?msg=registered");
} else {
    header("Location: ../register.html?error=db");
}
?>