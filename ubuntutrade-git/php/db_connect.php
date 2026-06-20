<?php
$host = "sql208.infinityfree.com";  // keep as is from control panel
$user = "if0_42107933";             // your database username
$pass = "4QSF3eh4lU";            // the password you set
$dbname = "if0_42107933_ubuntutrade_db"; // FULL name with prefix

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>