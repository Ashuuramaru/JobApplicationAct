<?php
require_once 'dbConfig.php';
require_once 'models.php';
session_start();

// Insert New User
if (isset($_POST['insertNewUserBtn'])) {
    $username = trim($_POST['username']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            $insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));
            $_SESSION['message'] = $insertQuery['message'];
            $_SESSION['status'] = $insertQuery['status'];
            $redirect = $insertQuery['status'] === '200' ? 'login.php' : 'register.php';
        } else {
            $_SESSION['message'] = "Passwords do not match.";
            $_SESSION['status'] = "400";
            $redirect = 'register.php';
        }
    } else {
        $_SESSION['message'] = "Please fill in all fields.";
        $_SESSION['status'] = "400";
        $redirect = 'register.php';
    }
    header("Location: ../$redirect");
    exit();
}

// Login User
if (isset($_POST['loginUserBtn'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if (!empty($username) && !empty($password)) {
        $loginQuery = checkIfUserExists($pdo, $username);
        if ($loginQuery['status'] === '200' && password_verify($password, $loginQuery['userInfoArray']['password'])) {
            $_SESSION['username'] = $username;
            header("Location: ../index.php");
        } else {
            $_SESSION['message'] = $loginQuery['message'];
            $_SESSION['status'] = $loginQuery['status'];
            header("Location: ../login.php");
        }
    } else {
        $_SESSION['message'] = "Please fill in all fields.";
        $_SESSION['status'] = "400";
        header("Location: ../login.php");
    }
    exit();
}

// Logout User
if (isset($_GET['logoutUserBtn'])) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}

// Insert New Applicant
if (isset($_POST['insertUserBtn'])) {
    $result = insertNewApplicant(
        $pdo, 
        $_POST['first_name'], 
        $_POST['last_name'], 
        $_POST['email'], 
        $_POST['experience'], 
        $_POST['specialization'], 
        $_POST['degree'], 
        $_POST['contact']
    );

    $_SESSION['message'] = $result ? "Applicant added successfully!" : "Failed to add applicant.";
    header("Location: ../index.php");
}

// Edit Applicant
if (isset($_POST['editUserBtn'])) {
    $result = editApplicant(
        $pdo,
        $_POST['id'],
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['experience'],
        $_POST['specialization'],
        $_POST['degree'],
        $_POST['contact']
    );
    $_SESSION['message'] = $result['message'];
    header("Location: ../index.php");
    exit();
}

// Delete Applicant
if (isset($_GET['deleteUserBtn'])) {
    $result = deleteApplicant($pdo, $_GET['id']);
    $_SESSION['message'] = $result['message'];
    header("Location: ../index.php");
    exit();
}

?>
