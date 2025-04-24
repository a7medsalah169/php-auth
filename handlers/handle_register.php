<?php
include '../config/db.php';
include '../handlers/send.php'; 
header('Content-Type: application/json');
$first = $_POST['first_name'];
$last = $_POST['last_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$subject = "Activate Your Account";

// chech if email or username is taken 
$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' OR username='$username'");
if (mysqli_num_rows($check) > 0) {
    http_response_code(400); // Bad request
    echo json_encode(["error" => "Email or username already exists."]);
    exit;
}
$hashed = password_hash($password, PASSWORD_DEFAULT);
$token = bin2hex(random_bytes(32)); // 32-character random string

// create the user 
$sql = "INSERT INTO users (first_name, last_name, username, email, password, activation_token)
        VALUES ('$first', '$last', '$username', '$email', '$hashed', '$token')";
// success signup
if (mysqli_query($conn, $sql)) {
    http_response_code(201); // Created
    echo json_encode([
        "message" => "Account created.",
        "activation_url" => "http://localhost/project/handlers/activate.php?token=$token"
    ]);
    $message = "Hello:$first \nActive your account from this link: http://localhost/project/handlers/activate.php?token=$token";
    // send link
    if (sendMail($email, $subject, $message)) {
        http_response_code(200); // OK
        echo "Active link has sent. Please check your email.";
        echo json_encode(["message" => "Active link has sent. Please check your email."]);
        // header("Location: http://localhost/project/handlers/activate.php?token=$token");
        exit;
    }
    
} else { 
    http_response_code(500); // Server error
    echo json_encode(["error" => "Failed to create user."]);
}



