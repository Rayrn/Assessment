<?php
/* Boundry Managemetn Form*/
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

// Check if the user is allowed to access this page
if(!$auth_user) {
    header("Location: ".WEB_ROOT);
    exit();
}

//------------------------------------------------------------------
// Action processing
//------------------------------------------------------------------
// Fetch Action
$action = isset($_GET['action']) ? $_GET['action'] : 'view';
$action = isset($_POST['action']) ? $_POST['action'] : $action;

//------------------------------------------------------------------
// Create factories
//------------------------------------------------------------------
$boundryFactory = new BoundryFactory($pdo);
$boundrySet = $boundryFactory->getBoundryByUser($auth_user);

$yearFactory = new YearFactory($pdo);
$yearSet = $yearFactory->getYearByUser($auth_user);

//------------------------------------------------------------------
// Check year
//------------------------------------------------------------------
$sel_year = isset($_GET['sel_year']) ? $_GET['sel_year'] : '';
$sel_year = isset($_POST['sel_year']) ? $_POST['sel_year'] : $sel_year;
if($sel_year == 'reset') { unset($_SESSION['sel_year']); $sel_year = ''; }
if(isset($_SESSION['sel_year']) && $sel_year == '') { $sel_year = $_SESSION['sel_year']; }
if($sel_year != '') { $_SESSION['sel_year'] = $sel_year; }

//------------------------------------------------------------------
// Boundry form processing
//------------------------------------------------------------------
if($action == 'view') {
    // Display form
    require_once (VIEW_ROOT.'/manage_boundries.php');
}

if($action == 'updateBoundry') {
    // Fetch Boundry details
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $id = isset($_POST['id']) ? $_POST['id'] : $id;
    $boundry = $boundryFactory->getBoundry($id);

    // Fetch update details
    $title = isset($_GET['title']) ? $_GET['title'] : $boundry->title;
    $title = isset($_POST['title']) ? $_POST['title'] : $title;

    // Instantiate error string
    $error_str = '';

    if(!$boundry) {
        $error_str .= "Boundry not found<br/>";
    }

    if(!Validation::notBlank($title)) {
        $title = '';
        $error_str .= "Title can't be empty<br/>";
    }

    if($error_str != '') {
        // Display form
        require_once (VIEW_ROOT.'/manage_boundries.php');
    } else {
        // Write new values
        $boundry->set('title', $title);
        $boundry->set('status', $status);
        $boundryFactory->updateBoundry($boundry, $auth_user);

        // Log request
        require_once APP_ROOT.'/inc/log.php';

        // Send to index
        header ('Location: /manage_boundries');
        exit();
    }
}