<?php
include '../config/db.php';

$email = $_POST['email'];
$code = $_POST['code'];

$query = "SELECT reset_code, reset_code_expire FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && $user['reset_code'] === $code && strtotime($user['reset_code_expire']) > time()) {
    header("Location: ../public/reset.php?email=" . urlencode($email));
    exit;
} else {
    echo "Invalid or expired code.";
}
?>
