<?php
/* Boundry Managemetn Form*/
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
// Create factories
//------------------------------------------------------------------
$criteriaFactory = new CriteriaFactory($pdo);
$criteriaSet = $criteriaFactory->getCriteriaByUser($auth_user);

$yearFactory = new YearFactory($pdo);
$yearSet = $yearFactory->getYearsByUser($auth_user);

//------------------------------------------------------------------
// Boundry form processing
//------------------------------------------------------------------
if($action == 'view') {
    // Display form
    require_once (VIEW_ROOT.'/manual_input.php');
}

if($action == 'ajax') {
    // Check display type
    $form = isset($_GET['form']) ? $_GET['form'] : 'full';
    $form = isset($_POST['form']) ? $_POST['form'] : $form;

    // Print a single row (desktop)
    if($form == 'full') {
        echo get_input_row_full($criteriaSet, $yearSet);
    }

    // Print a single row (mobile)
    if($form == 'mobile') {
        echo get_input_row_mobile($criteriaSet, $yearSet);
    }

    // Exit here so we don't log this request
    exit();
}