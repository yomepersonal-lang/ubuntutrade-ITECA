<?php                                       //login code
session_start();
include 'db_connect.php';

$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];


$result = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");

if ($row = mysqli_fetch_assoc($result)) {

    if (password_verify($password, $row['password_hash'])) {
        $_SESSION['user_id'] =$row['user_id'];
        $_SESSION['full_name']= $row['full_name'];
        $_SESSION['role_id']= $row['role_id'];

        
        if ($_SESSION['role_id'] == 1) {            // admin goes to their panel
            header("Location: ../admin/index.html");
        } else {
            header("Location: ../index.html");
        }
        exit;

    }


}
header("Location: ../login.html?error=invalid");
?>