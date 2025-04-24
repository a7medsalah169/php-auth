<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="../assets/css/auth.css">
    </head>
    
    <body>
        <h1>Welcome to the Login Page</h1>
        <div>
            <form action="../handlers/handle_login.php" method="POST">
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit">Login</button>
            </form>
        </div>
        <p><a href="forgot.php">Forgot Password?</a></p>

        <p>Don't have an account? <a href="register.php">Sign up here</a>.</p>
    </body>
</html>

