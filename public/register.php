
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="../assets/css/auth.css">
    </head>
    
    <body>
        <h1>Create Your New account</h1>
        <div>
            <form action="../handlers/handle_register.php" method="POST">
                <input type="text" name="first_name" placeholder="First Name" required><br>
                <input type="text" name="last_name" placeholder="Last Name" required><br>
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit">signup</button>
            </form>
        </div>
        <p>Already have an account? <a href="index.php">Log in here</a>.</p>
    </body>
</html>



