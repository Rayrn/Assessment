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
$action = isset($_GET['action']) ? $_GET['action'] : 'process';
$action = isset($_POST['action']) ? $_POST['action'] : $action;

//------------------------------------------------------------------
// Create factories
//------------------------------------------------------------------
$criteriaFactory = new CriteriaFactory($pdo);
$criteriaSet = $criteriaFactory->getCriteriaByUser($auth_user);

$yearFactory = new YearFactory($pdo);
$yearSet = $yearFactory->getYearsByUser($auth_user);

//------------------------------------------------------------------
// Child form Processing
//------------------------------------------------------------------
if($action == 'process' || $action == 'download') {
    // Fetch Data
    $names = isset($_GET['name']) ? $_GET['name'] : array();
    $names = isset($_POST['name']) ? $_POST['name'] : $names;

    $criteriaData = array();
    foreach ($criteriaSet as $criteria) {
        $criteriaTitle = strtolower($criteria->title);
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

        $row['name'] = $names[$index];
            
        foreach ($criteriaSet as $criteria) {
            $row[strtolower($criteria->title)] = $criteriaData[strtolower($criteria->title)][$index];
        }

        $row['year'] = $years[$index];

        $children[] = $row;
    }

    // Process children
    foreach($children as $index=>$child) {
        $children[$index] = processChild($auth_user, $child);
    }
}

if($action == 'process') {
    // Display data
    require_once (VIEW_ROOT.'/results.php');
}

if($action == 'download') {
    // Download data
    downloadChildren($criteriaSet, $children);
}