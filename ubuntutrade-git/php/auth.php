<?php
// auth.php – session and role helpers
// This file is meant to be included, not called directly
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
// No HTML output here