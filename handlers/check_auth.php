<?php
include '../config/db.php';

if (!isset($_COOKIE['auth_token'])) {
    http_response_code(403);
    exit("Access Denied: You are not authorized to access this page..");
}

$token = $_COOKIE['auth_token'];
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE token = ?");
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    http_response_code(403);
    exit("Access Denied: You are not authorized to access this page..");
}

$user = mysqli_fetch_assoc($result);
$currentTime = date("Y-m-d H:i:s");

if ($currentTime > $user['token_expire']) {
    http_response_code(403);
    exit("Session expired. Please log in again.");
}


