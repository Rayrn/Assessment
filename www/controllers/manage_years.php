<?php
/* Year Managemetn Form*/
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

// Check if the user is allowed to access this page
if(!$auth_user) {
    header("Location: /");
    exit();
}

//------------------------------------------------------------------
// Action processing
//------------------------------------------------------------------
// Fetch Action
$action = isset($_GET['action']) ? $_GET['action'] : 'view';
$action = isset($_POST['action']) ? $_POST['action'] : $action;

//------------------------------------------------------------------
// Create factory
//------------------------------------------------------------------
$yearFactory = new YearFactory($pdo);

// Fetch list of year
$yearSet = $yearFactory->getYearByUser($auth_user, 'saved');

//------------------------------------------------------------------
// Year form processing
//------------------------------------------------------------------
if($action == 'view') {
    // Display form
    require_once (VIEW_ROOT.'/manage_years.php');
}

if($action == 'addYear') {
    // Fetch Criteria details
    $title = isset($_GET['title']) ? $_GET['title'] : '';
    $title = isset($_POST['title']) ? $_POST['title'] : $title;

    // Instantiate error string
    $error_str = '';
    if(!Validation::notBlank($title)) {
        $title = '';
        $error_str .= "Title can't be empty<br/>";
    }

    if($error_str != '') {
        // Display form
        require_once (VIEW_ROOT.'/login.php');
    } else {
        $yearFactory->newYear($title, $auth_user);

        // Log request
        require_once APP_ROOT.'/inc/log.php';

        // Send to index
        header ('Location: /manage_years');
        exit();
    }
}

if($action == 'updateYear') {
    // Fetch Year details
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $id = isset($_POST['id']) ? $_POST['id'] : $id;
    $year = $yearFactory->getYear($id);

    // Fetch update details
    $title = isset($_GET['title']) ? $_GET['title'] : $year->title;
    $title = isset($_POST['title']) ? $_POST['title'] : $title;
    $status = isset($_GET['status']) ? $_GET['status'] : $year->status;
    $status = isset($_POST['status']) ? $_POST['status'] : $status;

    // Instantiate error string
    $error_str = '';

    if(!$year) {
        $error_str .= "Year not found<br/>";
    }

    if(!Validation::notBlank($title)) {
        $title = '';
        $error_str .= "Title can't be empty<br/>";
    }

    if($error_str != '') {
        // Display form
        require_once (VIEW_ROOT.'/manage_years.php');
    } else {
        // Write new values
        $year->set('title', $title);
        $year->set('status', $status);
        $yearFactory->updateYear($year, $auth_user);

        // Log request
        require_once APP_ROOT.'/inc/log.php';

        // Send to index
        header ('Location: /manage_years');
        exit();
    }
}