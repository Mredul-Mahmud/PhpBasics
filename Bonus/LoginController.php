<?php 
session_start();
require 'DbConnection.php';

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);

    $flag = true;

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
    }

    if ($flag) {
        $conn = getConnection(); 

        if (!$conn) {
            $_SESSION['error'] = "Database connection failed.";
            header('Location: login.php');
            exit();
        }

        $sql = "SELECT id, name, email, password FROM Auth WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {

                    $_SESSION['username'] = $user['name'];
                    $_SESSION['user_id'] = $user['id'];

                    // Redirect to the home page
                    header("Location: home.php");
                } else {
                    $_SESSION['loginErr'] = "Invalid email or password";
                    header('Location: login.php');
                }
            } else {
                $_SESSION['loginErr'] = "Invalid email or password";
                header('Location: login.php');
            }

            $stmt->close();
        } else {
            $_SESSION['error'] = "Something went wrong with the query.";
            header('Location: login.php');
        }

        $conn->close();
    } else {
        header('Location: login.php');
    }
} else {
    header('Location: login.php');
}
?>
