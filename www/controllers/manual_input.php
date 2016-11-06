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
$criteriaFactory = new CriteriaFactory($pdo);
$criteriaSet = $criteriaFactory->getCriteriaByUser($auth_user);

$yearFactory = new YearFactory($pdo);
$yearSet = $yearFactory->getYearByUser($auth_user);

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

if($action == 'process') {
    // Fetch Data
    $names = isset($_GET['name']) ? $_GET['name'] : array();
    $names = isset($_POST['name']) ? $_POST['name'] : $names;

    $criteriaData = array();
    foreach ($criteriaSet as $criteria) {
        $criteriaTitle = $criteria->title;
        $criteriaTitleVV = $criteriaTitle.'_variable_variable';

        $$criteriaTitleVV = isset($_GET[$criteria->id]) ? $_GET[$criteria->id] : array();
        $$criteriaTitleVV = isset($_POST[$criteria->id]) ? $_POST[$criteria->id] : $$criteriaTitleVV;

        $criteriaData[$criteriaTitle] = $$criteriaTitleVV;
    }

    $years = isset($_GET['year']) ? $_GET['year'] : array();
    $years = isset($_POST['year']) ? $_POST['year'] : $years;

    // Convert from row to column data
    $children = array();
    foreach ($names as $index=>$value) {
        $row = array();

        $row['Name'] = $names[$index];
            
        foreach ($criteriaSet as $criteria) {
            $row[$criteria->title] = $criteriaData[$criteria->title][$index];
        }

        $row['Year'] = $years[$index];

        $children[] = $row;
    }

    // Process children
    foreach($children as $index=>$child) {
        $children[$index] = processChild($child);
    }

    // Display data
    require_once (VIEW_ROOT.'/output.php');
}

if($action == 'download') {
    
}