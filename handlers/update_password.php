<?php
include '../config/db.php';

$email = $_POST['email'];
$pass = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($pass !== $confirm) {
    echo "Passwords do not match.";
    exit;
}

$hashed = password_hash($pass, PASSWORD_BCRYPT);

$stmt = $conn->prepare("UPDATE users SET password = ?, reset_code = NULL, reset_code_expire = NULL WHERE email = ?");
$stmt->bind_param("ss", $hashed, $email);
$stmt->execute();

echo "Password has been updated. <a href='../index.php'>Login here</a>";
header("Location: ../public/index.php");
exit;
?>
