<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS -->
</head>
<body>
    <h2>Login</h2>
    <form action="loginController.php" method="post">
        <label for="email"><b>Email:</b></label>
        <input type="email" name="email" id="email" required>
        <span><?php echo $_SESSION['emailErr'] ?? ''; ?></span><br>

        <label for="password"><b>Password:</b></label>
        <input type="password" name="password" id="password" required>
        <span><?php echo $_SESSION['passwordErr'] ?? ''; ?></span><br>

        <input type="submit" name="login" value="Login">
    </form>

    <span><?php echo $_SESSION['loginErr'] ?? ''; ?></span><br>
    <a href="Registrationbonus.php">Don't have an account? Register here</a>

    <?php session_unset(); ?>
</body>
</html>
