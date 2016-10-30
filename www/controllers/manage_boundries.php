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

$boundryLimitFactory = new BoundryLimitFactory($pdo);

//------------------------------------------------------------------
// Check year
//------------------------------------------------------------------
$sel_year = isset($_GET['sel_year']) ? $_GET['sel_year'] : '';
$sel_year = isset($_POST['sel_year']) ? $_POST['sel_year'] : $sel_year;
if($sel_year == 'reset') { unset($_SESSION['sel_year']); $sel_year = ''; }
if(isset($_SESSION['sel_year']) && $sel_year == '') { $sel_year = $_SESSION['sel_year']; }
if($sel_year != '') { $_SESSION['sel_year'] = $sel_year; }

// If year is empty, select the first year that was returned
$sel_year = isset($yearSet[0]->id) ? $yearSet[0]->id : '';

// Fetch active year
$activeYear = $yearFactory->getYear($sel_year);

//------------------------------------------------------------------
// Check Limits
//------------------------------------------------------------------
foreach($boundrySet as $index=>$boundry) {
    $boundrySet[$index]->limitData = $boundryLimitFactory->getBoundryLimitByAll($boundry, $activeYear, $auth_user);

}

//------------------------------------------------------------------
// Boundry form processing
//------------------------------------------------------------------
if($action == 'view') {
    // Display form
    require_once (VIEW_ROOT.'/manage_boundries.php');
}

if($action == 'updateBoundry') {
    // Fetch Boundry details
    $boundry_id = isset($_GET['boundry_id']) ? $_GET['boundry_id'] : '';
    $boundry_id = isset($_POST['boundry_id']) ? $_POST['boundry_id'] : $boundry_id;
    $boundry = $boundryFactory->getBoundry($boundry_id);

    // Fetch Boundry Limit details
    $limit_id = isset($_GET['limit_id']) ? $_GET['limit_id'] : '';
    $limit_id = isset($_POST['limit_id']) ? $_POST['limit_id'] : $limit_id;
    $boundryLimit = $boundryLimitFactory->getBoundryLimit($limit_id);

    // Create a new limit record if we don't have one yet
    if(!$boundryLimit) {
        $boundryLimit = $boundryLimitFactory->newBoundryLimit($boundry, $activeYear, 0, 0, $auth_user);
    }

    // Fetch update details
    $title = isset($_GET['title']) ? $_GET['title'] : $boundry->title;
    $title = isset($_POST['title']) ? $_POST['title'] : $title;
    $lower_limit = isset($_GET['lower']) ? $_GET['lower'] : $boundryLimit->lower_limit;
    $lower_limit = isset($_POST['lower']) ? $_POST['lower'] : $lower_limit;
    $upper_limit = isset($_GET['upper']) ? $_GET['upper'] : $boundryLimit->upper_limit;
    $upper_limit = isset($_POST['upper']) ? $_POST['upper'] : $upper_limit;

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
        $boundryFactory->updateBoundry($boundry, $auth_user);

        // Write new values
        $boundryLimit->set('lower_limit', $lower_limit);
        $boundryLimit->set('upper_limit', $upper_limit);
        $boundryLimitFactory->updateBoundryLimit($boundryLimit, $auth_user);
        
        // Log request
        require_once APP_ROOT.'/inc/log.php';

        // Send to index
        header ('Location: /manage_boundries');
        exit();
    }
}