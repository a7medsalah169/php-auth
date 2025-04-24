<?php
// handlers/handle_reset.php

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($token) || empty($new_password) || empty($confirm_password)) {
        die('All fields are required.');
    }

    if ($new_password !== $confirm_password) {
        die('Passwords do not match.');
    }

    // Check if token is valid and not expired
    $stmt = $conn->prepare("SELECT id, token_expire FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        die('Invalid or expired token.');
    }

    $stmt->bind_result($user_id, $expires_at);
    $stmt->fetch();

    if (strtotime($expires_at) < time()) {
        die('Token has expired.');
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update user's password and clear token fields
    $stmt = $conn->prepare("UPDATE users SET password = ?, token = NULL, token_expire = NULL WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $user_id);
    $stmt->execute();

    echo "Password has been reset successfully. You can now <a href='/public/index.php'>login</a>.";
} else {
    http_response_code(405);
    echo "Method not allowed.";
}
