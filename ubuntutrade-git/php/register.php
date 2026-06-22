<?php
session_start();
include 'db_connect.php';

$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$role = (int)$_POST['role']; // 2=buyer, 3=seller

// Check if phone number is provided
if (empty($phone)) {
    header("Location: ../register.html?error=phonerequired");
    exit;
}

// Basic phone validation (at least 10 digits)
if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
    header("Location: ../register.html?error=phonerequired");
    exit;
}

// Check if passwords match
if ($password !== $confirm_password) {
    header("Location: ../register.html?error=passwordmismatch");
    exit;
}

// Check password length
if (strlen($password) < 6) {
    header("Location: ../register.html?error=passwordshort");
    exit;
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Check if email exists
$check = mysqli_query($conn, "SELECT user_id FROM user WHERE email='$email'");
if (mysqli_num_rows($check) > 0) {
    header("Location: ../register.html?error=emailexists");
    exit;
}

$sql = "INSERT INTO user (email, phone, password_hash, full_name, role_id) 
        VALUES ('$email', '$phone', '$password_hash', '$full_name', $role)";
if (mysqli_query($conn, $sql)) {
    header("Location: ../login.html?msg=registered");
} else {
    header("Location: ../register.html?error=db");
}
?>