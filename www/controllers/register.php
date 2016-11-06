<?php
/* Registration Form Processing */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

// Check if the user is allowed to access this page
if($auth_user) {
    header('Location: '.WEB_ROOT);
    exit();
}

// Check if we are allowing registration
if(!ALLOW_REG) {
    $error_title = 'Registration Disabled';
    $error_text = 'Sorry, registration is currently turned off. Please contact your system adminstrator to re-enable this functionality.';

    // Display form
    require_once (ERROR_ROOT.'/generic.php');

    // Log request
    require_once APP_ROOT.'/inc/log.php';
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
$first_name = isset($_GET['first_name']) ? $_GET['first_name'] : '';
$first_name = isset($_POST['first_name']) ? $_POST['first_name'] : $first_name;
$last_name = isset($_GET['last_name']) ? $_GET['last_name'] : '';
$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : $last_name;
$email = isset($_GET['email']) ? $_GET['email'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : $email;
$password_1 = isset($_GET['password_1']) ? $_GET['password_1'] : '';
$password_1 = isset($_POST['password_1']) ? $_POST['password_1'] : $password_1;
$password_2 = isset($_GET['password_2']) ? $_GET['password_2'] : '';
$password_2 = isset($_POST['password_2']) ? $_POST['password_2'] : $password_2;

// Format for display
$first_name_display = htmlentities($first_name);
$last_name_display = htmlentities($last_name);
$email_display = htmlentities($email);

if($action == '') {
    // Display form
    require_once (VIEW_ROOT.'/register.php');
}

if($action == 'register') {
    // Instantiate error string
    $error_str = '';

    if(!Validation::first_name($first_name)) {
        $first_name = '';
        $error_str .= "First name invalid<br/>";
    }

    if(!Validation::last_name($first_name)) {
        $last_name = '';
        $error_str .= "Last name invalid<br/>";
    }

    if(!Validation::email($email)) {
        $email = '';
        $error_str .= "Email address invalid<br/>";
    }

    if(!Validation::password($password_1)) {
        $password_1 = '';
        $password_2 = '';
        $error_str .= "Password must be between 6 and 30 characters<br/>"; // Add more detail if needed
    }

    if(!Validation::match($password_1, $password_2)) {
        $password_1 = '';
        $password_2 = '';
        $error_str .= "Passwords must match<br/>"; // Add more detail if needed
    }

    if($error_str != '') {
        // Display form
        require_once (VIEW_ROOT.'/register.php');
    } else {
        // Save user to DB
        $userFactory = new UserFactory($pdo);
        $user = $userFactory->newUser($first_name, $last_name, $email, $password_1);

        // Create various DB fields
        $criteriaFactory = new CriteriaFactory($pdo);
        $criteriaFactory->newCriteria('Homework', $user);
        $criteriaFactory->newCriteria('Creating', $user);
        $criteriaFactory->newCriteria('Performing', $user);
        $criteriaFactory->newCriteria('Responding', $user);

        $boundryFactory = new BoundryFactory($pdo);
        $boundryFactory->newBoundry('4', 'Emerging (Lower Band)', $user);
        $boundryFactory->newBoundry('5', 'Emerging (Top Band)', $user);
        $boundryFactory->newBoundry('6', 'Developing (Low-Middle Band)', $user);
        $boundryFactory->newBoundry('7', 'Developing (Higher Band)', $user);
        $boundryFactory->newBoundry('8', 'Secure (Lower Band)', $user);
        $boundryFactory->newBoundry('9', 'Secure (Middle Band)', $user);
        $boundryFactory->newBoundry('10', 'Secure (Higher Band)', $user);
        $boundryFactory->newBoundry('11', 'Exceeding (Lower Band)', $user);
        $boundryFactory->newBoundry('12', 'Exceeding (Higher Band)', $user);

        $yearFactory = new YearFactory($pdo);
        $yearFactory->newYear('Year 7', $user);

        // Log request
        require_once APP_ROOT.'/inc/log.php';

        // Send to index
        header('Location: '.WEB_ROOT.'login?email='.$email);
        exit();
    }
}