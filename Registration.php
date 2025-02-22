<?php 
session_start();

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);
    $profilePicture = $_FILES['profilePicture'];
    
    $flag = true;
    
    if (empty($name)) {
        $_SESSION['nameErr'] = "Name Cannot be Empty";
        $_SESSION['name'] = "";
        $flag = false;
    } else {
        $_SESSION['nameErr'] = "";
        $_SESSION['name'] = $name;
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $_SESSION['nameErr'] = "Only letters and white space allowed";
            $flag = false;
        }
    }
    
    if (empty($email)) {
        $_SESSION['emailErr'] = "Email Cannot be Empty";
        $_SESSION['email'] = "";
        $flag = false;
    } else {
        $_SESSION['emailErr'] = "";
        $_SESSION['email'] = $email;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['emailErr'] = "Invalid email format";
            $flag = false;
        }
    }
    
    if (empty($password)) {
        $_SESSION['passwordErr'] = "Password Cannot be Empty";
        $_SESSION['password'] = "";
        $flag = false;
    } else {
        $_SESSION['passwordErr'] = "";
        $_SESSION['password'] = $password;
        if (strlen($password) < 6) {
            $_SESSION['passwordErr'] = "Password must be at least 6 characters long";
            $flag = false;
        }
    }
    
    if ($profilePicture['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $max_size = 2 * 1024 * 1024; 
        
        if (!in_array($profilePicture['type'], $allowed_types)) {
            $_SESSION['profilePictureErr'] = "Only JPEG and PNG files are allowed";
            $flag = false;
        } else {
            $_SESSION['profilePictureErr'] = "";
        }
        
        if ($profilePicture['size'] > $max_size) {
            $_SESSION['profilePictureErr'] = "File size must not exceed 2MB";
            $flag = false;
        }
        
        if ($flag) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_path = $upload_dir . basename($profilePicture['name']);
            move_uploaded_file($profilePicture['tmp_name'], $file_path);
            $_SESSION['uploaded_file'] = $file_path;
            $_SESSION['profilePictureErr'] = "";
        }
    } elseif (!isset($_SESSION['uploaded_file'])) {
        $_SESSION['profilePictureErr'] = "Profile picture is required";
        $flag = false;
    }
    
    if ($flag) {
        $_SESSION['success'] = "Registration successful!";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Views/css/styles.css">
    <title>Registration</title>
</head>
<body class="Body">
    <form action="" method="post" enctype="multipart/form-data" class="PersonalInfoForm">
    <table>
        <tr>
            <td>
                <h1>Register</h1>
                <input class="btn" type="submit" name="logout" value="Logout"><br><br><br>
                <label class="PersonalInfoLabel" for="profilePicture"><b>Profile Picture:</b></label>
                <input type="file" name="profilePicture" id="profilePicture"><br>
                <span><?php echo $_SESSION['profilePictureErr'] ?? ''; ?></span><br>
                <?php if(isset($_SESSION['uploaded_file'])): ?>
                <p><img src="<?php echo $_SESSION['uploaded_file']; ?>" width="100" alt="Profile Picture"></p>
                <?php endif; ?>
                <label class="PersonalInfoLabel" for="name"><b>Name:</b></label>
                <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $_SESSION['name'] ?? ''; ?>"><br>
                <span><?php echo $_SESSION['nameErr'] ?? ''; ?></span><br>

                <label class="PersonalInfoLabel" for="email"><b>Email:</b></label>
                <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $_SESSION['email'] ?? ''; ?>"><br>
                <span><?php echo $_SESSION['emailErr'] ?? ''; ?></span><br>

                <label class="PersonalInfoLabel" for="password"><b>Password:</b></label>
                <input type="password" name="password" id="password" placeholder="Password"><br>
                <span><?php echo $_SESSION['passwordErr'] ?? ''; ?></span><br>
            </td>
        </tr>
    </table>
    <input class="btn" type="submit" name="submit" value="Register"><br><br>
    <span><?php echo $_SESSION['success'] ?? ''; ?></span><br>
    <a class="DDref" href="index.php">Back</a>
    </form>
</body>
</html>
