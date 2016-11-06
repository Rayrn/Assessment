<?php
/* Criteria Managemetn Form*/
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
$criteriaFactory = new CriteriaFactory($pdo);

// Fetch list of criteria
$criteriaSet = $criteriaFactory->getCriteriaByUser($auth_user, 'saved');

//------------------------------------------------------------------
// Criteria form processing
//------------------------------------------------------------------
if($action == 'view') {
    // Display form
    require_once (VIEW_ROOT.'/manage_criteria.php');
}

if($action == 'updateCriteria') {
    // Fetch Criteria details
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $id = isset($_POST['id']) ? $_POST['id'] : $id;
    $criteria = $criteriaFactory->getCriteria($id);

    // Fetch update details
    $title = isset($_GET['title']) ? $_GET['title'] : $criteria->title;
    $title = isset($_POST['title']) ? $_POST['title'] : $title;
    $status = isset($_GET['status']) ? $_GET['status'] : $criteria->status;
    $status = isset($_POST['status']) ? $_POST['status'] : $status;

    // Instantiate error string
    $error_str = '';

    if(!$criteria) {
        $error_str .= "Criteria not found<br/>";
    }

    if(!Validation::notBlank($title)) {
        $title = '';
        $error_str .= "Title can't be empty<br/>";
    }

    if($error_str != '') {
        // Display form
        require_once (VIEW_ROOT.'/manage_criteria.php');
    } else {
        // Write new values
        $criteria->set('title', $title);
        $criteria->set('status', $status);
        $criteriaFactory->updateCriteria($criteria, $auth_user);

        // Log request
        require_once APP_ROOT.'/inc/log.php';

        // Send to index
        header ('Location: /manage_criteria');
        exit();
    }
}