<?php
include '../config/db.php';
include '../handlers/send.php'; 
$email = $_POST['email'];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit;
}

// Check if the user exists in the database
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No account found with that email.";
    exit;
}

$user = $result->fetch_assoc();

// Generate a 6-digit reset code
$reset_code = rand(100000, 999999);

// Set expiration time (e.g., 15 minutes from now)
$reset_code_expire = date('Y-m-d H:i:s', strtotime('+15 minutes')); // 15 minutes expiration

// Save the reset code and expiration to the database
$update_sql = "UPDATE users SET reset_code = ?, reset_code_expire = ? WHERE email = ?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("sss", $reset_code, $reset_code_expire, $email);
$stmt->execute();

// Send the reset code via email
$subject = "Password Reset Code";
$message = "Your password reset code is: $reset_code";

if (sendMail($email, $subject, $message)) {
    header("Location: ../verify2fa.php?email=" . urlencode($email));
    exit;
} else {
    echo "Error sending the reset code. Please try again.";
}
?>
