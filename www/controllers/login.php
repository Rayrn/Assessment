<?php
/* Login Form Processing */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

// Check if the user is allowed to access this page
if($auth_user) {
    header('Location: '.WEB_ROOT);
    exit();
}

//------------------------------------------------------------------
// Action processing
//------------------------------------------------------------------
// Fetch Action
$action = isset($_GET['action']) ? $_GET['action'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : $action;

//------------------------------------------------------------------
// Login form processing
//------------------------------------------------------------------
// Fetch Name/Email
$email = isset($_GET['email']) ? $_GET['email'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : $email;
$password = isset($_GET['password']) ? $_GET['password'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : $password;

// Format for display
$email_display = htmlentities($email);

if($action == '') {
    // Display form
    require_once (VIEW_ROOT.'/login.php');
}

if($action == 'login') {
    // Instantiate error string
    $error_str = '';

    if(!Validation::email($email)) {
        $email = '';
        $error_str .= "Email address invalid<br/>";
    }

    // Fetch user based on email (so we can retreive salt)
    $userFactory = new UserFactory($pdo);
    $user = $userFactory->getUserByAttribute('', $email);

    // If the user email doesn't match then skip pw check
    if(!$user) {
        $error_str .= 'User not found<br/>';
    } else {
        // Check that salted/hashed pw matches what we brought back
        if(!password_verify($password, $user->password)) {
            $error_str .= 'Incorrect password entered<br/>';
        } 

        // Check that the user has not been marked as inactive/banned/etc
        if($user->status != '2') {
            $error_str .= 'Account marked as inactive, please contact a system adminstrator<br/>';
        }
    }

    if($error_str != '') {
        // Display form
        require_once (VIEW_ROOT.'/login.php');
    } else {
        // Save user details to $_SESSION
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;

        Security::updateSession($pdo, $user);

        // Log request
        require_once APP_ROOT.'/inc/log.php';

        // Send to index
        header('Location: '.WEB_ROOT);
        exit();
    }
}