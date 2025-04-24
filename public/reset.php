<html>
    <head>
        <title>reset password</title>
        <link rel="stylesheet" href="../assets/css/auth.css">
    </head>
    <body>
        
        <h1>Reset Your Password</h1>
        <?php $email = $_GET['email'] ?? ''; ?>

        <h2>Set a New Password</h2>
        <form method="POST" action="../handlers/update_password.php">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <input type="password" name="password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Reset Password</button>
        </form>

    </body>
</html>
