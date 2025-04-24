<?php
include '../config/db.php';
include '../handlers/send.php'; 
header('Content-Type: application/json');
$first = htmlspecialchars(trim($_POST['first_name']), ENT_QUOTES, 'UTF-8');
$last = htmlspecialchars(trim($_POST['last_name']), ENT_QUOTES, 'UTF-8');
$username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password']);
$subject = "Activate Your Account";

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid email format."]);
    exit;
}

// Validate password
if (empty($password) || strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(["error" => "Password must be at least 8 characters."]);
    exit;
}

// Check if email or username exists using prepared statements
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    http_response_code(400);
    echo json_encode(["error" => "Email or username already exists."]);
    exit;
}

// Hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);
$token = bin2hex(random_bytes(32)); // secure activation token

// Insert user using prepared statement
$insert = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password, activation_token) VALUES (?, ?, ?, ?, ?, ?)");
$insert->bind_param("ssssss", $first, $last, $username, $email, $hashed, $token);

if ($insert->execute()) {
    // Send activation link
    $message = "Hello :$first,\nActivate your account: http://localhost/php-auth/handlers/activate.php?token=$token";
    if (sendMail($email, $subject, $message)) {
        http_response_code(200);
        echo json_encode([
            "message" => "Account created. Activation link sent to email.",
            "activation_url" => "http://localhost/php-auth/handlers/activate.php?token=$token"
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to send activation email."]);
    }
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to create user."]);
}
?>
