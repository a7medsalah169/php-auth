<!-- 
 /* Handle user login and authentication */
-->

<?php
include '../config/db.php';
header('Content-Type: application/json');

$email = $_POST['email'];
$password = $_POST['password'];
// check for emty inputs
if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["error" => "Email and password are required."]);
    exit;
}

// Find user by email
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid email or password."]);
    exit;
}

$user = mysqli_fetch_assoc($result);

// ✅ FIRST check if password is correct
if (!password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid email or password."]);
    exit;
}

// ✅ THEN check if the account is activated
if (!empty($user['activation_token'])) {
    http_response_code(403);
    echo json_encode(["error" => "Account not activated."]);
    exit;
}

// ✅ Start session and login
session_start();
// $_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];

// Generate and set token
$token = bin2hex(random_bytes(32));
$expiry = date("Y-m-d H:i:s", time() + 3600); // +1 hour from now

setcookie("auth_token", $token, time() + 3600, "/", "", false, true);

$stmt = mysqli_prepare($conn, "UPDATE users SET token = ?, token_expire = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "ssi", $token, $expiry, $user['id']);
mysqli_stmt_execute($stmt);

// ✅ Return success response
http_response_code(200);
echo json_encode([
    "message" => "Login successful.",
    "user" => [
        "id" => $user['id'],
        "username" => $user['username'],
        "first_name" => $user['first_name'],
        "last_name" => $user['last_name']
    ]
]);
header("Location: ../public/dashboard.php");

exit;
?>