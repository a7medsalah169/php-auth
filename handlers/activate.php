<?php
include '../config/db.php';

$token = $_GET['token'];

$sql = "SELECT * FROM users WHERE activation_token='$token'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    mysqli_query($conn, "UPDATE users SET is_active=1, activation_token=NULL WHERE activation_token='$token'");
    echo "Account activated! <a href='../public/index.php'>Login now</a>";
} else {
    echo "Invalid or expired activation link.";
}
