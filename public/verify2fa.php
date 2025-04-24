<html>
    <head>
        <title>Verify account</title>
        <link rel="stylesheet" href="../assets/css/auth.css">
    </head>
    <body>
    <?php $email = $_GET['email'] ?? ''; ?>

        <h2>Enter the 6-digit code sent to <?= htmlspecialchars($email) ?></h2>
        <form method="POST" action="../handlers/handle_2fa.php">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <input type="text" name="code" placeholder="6-digit code" required>
            <button type="submit">Verify Code</button>
        </form>

        <p><a href="index.php">Back to login</a></p>
    </body>
</html>