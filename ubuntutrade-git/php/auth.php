<?php

session_start();
require_once __DIR__ . '/db_connect.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1;
}

function isSeller() {
    return isset($_SESSION['role_id']) && ($_SESSION['role_id'] == 3 || $_SESSION['role_id'] == 1);
}

// Check if user has a specific permission
function hasPermission($permission) {
    global $conn;
    if (!isset($_SESSION['user_id'])) return false;
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT r.permissions FROM user u JOIN role r ON u.role_id = r.role_id WHERE u.user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (!$row || empty($row['permissions'])) return false;
    $perms = explode(',', $row['permissions']);
    return in_array($permission, $perms) || in_array('full_access', $perms);
}
