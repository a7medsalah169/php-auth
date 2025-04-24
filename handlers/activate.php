<?php
include '../config/db.php';
echo "hac";
$token = $_GET['token'];

// Check if the token is empty
if (empty($token)) {
    http_response_code(400); 
    echo "Invalid activation link.";
    exit;
}

// Prepare SQL query with a placeholder to prevent SQL injection
$sql = "SELECT * FROM users WHERE activation_token = ?";
$stmt = $conn->prepare($sql);

// Bind parameters and execute the query
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

// Check if a matching user was found
if ($result->num_rows === 1) {
    // Prepare the update query to activate the user account
    $update_sql = "UPDATE users SET is_active = 1, activation_token = NULL WHERE activation_token = ?";
    $update_stmt = $conn->prepare($update_sql);
    
    // Bind parameters and execute the update
    $update_stmt->bind_param("s", $token);
    if ($update_stmt->execute()) {
        http_response_code(200); 
        echo "Account activated!";
        header("Location: ../public/index.php");
    } else {
        http_response_code(500); // Internal Server Error
        echo "There was an error activating your account. Please try again later.";
    }
} else {
    http_response_code(404); // Not Found
    echo "Invalid or expired activation link.";
}

// Close prepared statements and the connection
$stmt->close();
$update_stmt->close();
$conn->close();
?>
