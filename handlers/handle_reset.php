<?php
include '../config/db.php';

$email = $_POST['email'];
$pass = $_POST['password'];
$confirm = $_POST['confirm_password'];

// Check if any of the fields are empty
if (empty($email) || empty($pass) || empty($confirm)) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "All fields are required."]);
    exit;
}

// Check if passwords match
if ($pass !== $confirm) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Passwords do not match."]);
    exit;
}

// Hash the new password
$hashed = password_hash($pass, PASSWORD_BCRYPT);

// Prepare SQL to update password
$stmt = $conn->prepare("UPDATE users SET password = ?, reset_code = NULL, reset_code_expire = NULL WHERE email = ?");
$stmt->bind_param("ss", $hashed, $email);

// Execute the query
if ($stmt->execute()) {
    http_response_code(200); // OK
    echo json_encode(["message" => "Password has been updated. <a href='../index.php'>Login here</a>"]);
    header("Location: ../public/index.php");
    exit;
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(["message" => "There was an error updating your password. Please try again."]);
}
?>
