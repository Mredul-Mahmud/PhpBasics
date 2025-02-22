<?php 
session_start();
require 'DbConnection.php';

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);
    $profilePicture = $_FILES['profilePicture']['name'] ?? '';
    $tempname = $_FILES['profilePicture']['tmp_name'] ?? '';
    $folder = "../uploads/" . $profilePicture;

    $flag = true;
    
    if (empty($name)) {
        $_SESSION['nameErr'] = "Name cannot be empty";
        $flag = false;
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $_SESSION['nameErr'] = "Only letters and white space allowed";
        $flag = false;
    }
    
    if (empty($email)) {
        $_SESSION['emailErr'] = "Email cannot be empty";
        $flag = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['emailErr'] = "Invalid email format";
        $flag = false;
    }
    
    if (empty($password)) {
        $_SESSION['passwordErr'] = "Password cannot be empty";
        $flag = false;
    } elseif (strlen($password) < 6) {
        $_SESSION['passwordErr'] = "Password must be at least 6 characters long";
        $flag = false;
    }
    
    if ($flag) {
        $conn = getConnection(); 

        if (!$conn) {
            $_SESSION['error'] = "Database connection failed.";
            header('Location: registration.php');
            exit();
        }

        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // SQL query to insert user into the database
        $sql = "INSERT INTO Auth (name, email, password, profilePicture) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $profilePicture);
            $status = $stmt->execute();
            
            if ($status) {
                if (!empty($profilePicture) && move_uploaded_file($tempname, $folder)) {
                    $_SESSION['uploaded_file'] = $folder;
                }
                $_SESSION['success'] = "Registration successful!";
                header('Location: Login.php');
            } else {
                $_SESSION['error'] = "Database error. Please try again.";
                header('Location: registration.php');
            }
        } else {
            $_SESSION['error'] = "Something went wrong with the query.";
            header('Location: registration.php');
        }
    } else {
        header('Location: registration.php');
    }
} else {
    header('Location: registration.php');
}
?>
