<html>
    <head>
        <title>forget password</title>
        <link rel="stylesheet" href="../assets/css/auth.css">
    </head>
    <body>
        <h1>Forgot Your Password?</h1>
        <form action="../handlers/handle_forgot.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Send Code</button>
        </form>
        <p>Remembered it? <a href="index.php">Back to login</a>.</p>
    </body>
</html>
