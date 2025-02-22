<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS -->
</head>
<body>
    <form action="registrationBonusController.php" method="post" enctype="multipart/form-data">
        <h1>Register</h1>
        
        <label for="profilePicture"><b>Profile Picture:</b></label>
        <input type="file" name="profilePicture" id="profilePicture">
        <span><?php echo $_SESSION['profilePictureErr'] ?? ''; ?></span><br>
        <?php if(isset($_SESSION['uploaded_file'])): ?>
            <p><img src="<?php echo $_SESSION['uploaded_file']; ?>" width="100" alt="Profile Picture"></p>
        <?php endif; ?>

        <label for="name"><b>Name:</b></label>
        <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $_SESSION['name'] ?? ''; ?>">
        <span><?php echo $_SESSION['nameErr'] ?? ''; ?></span><br>

        <label for="email"><b>Email:</b></label>
        <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $_SESSION['email'] ?? ''; ?>">
        <span><?php echo $_SESSION['emailErr'] ?? ''; ?></span><br>

        <label for="password"><b>Password:</b></label>
        <input type="password" name="password" id="password" placeholder="Password">
        <span><?php echo $_SESSION['passwordErr'] ?? ''; ?></span><br>

        <input type="submit" name="submit" value="Register">
        <span class="success"><?php echo $_SESSION['success'] ?? ''; ?></span><br>

        <a href="index.php">Back</a><br>
        <a href="Login.php">Login Here</a>
    </form>
</body>
</html>
