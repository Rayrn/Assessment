<?php
/* Boundry Managemetn Form*/
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

// Check if the user is allowed to access this page
if(!$auth_user) {
    header('Location: '.WEB_ROOT);
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
    require_once (VIEW_ROOT.'/import_csv.php');
}

if($action == 'process') {
    // Instantiate error string
    $error_str = '';

    // Fetch file
    $csv = isset($_FILES['upload']) ? $_FILES['upload'] : FALSE;

    if($csv) {
        // Check file type
        $filetype = explode('.', $csv['name']);
        $filetype = strtolower(end($filetype));

        if($filetype != 'csv') {
            $error_str .= "Filetype must be .csv<br/>";
        }
    }

    // File is okay, lets process it
    if($error_str == '') {
        // Convert to array
        $children = processCSV($csv);

        // Check that criteria match those found in DB (and convert to lower case)
        $child = $children[0];

        if(!isset($child['name'])) {
            $error_str .= "Child name column missing<br/>";
        }

        foreach($criteriaSet as $criteria) {
            if(!isset($child[strtolower($criteria->title)])) {
                $error_str .= "Criteria {$criteria->title} column missing <br/>";
            }
        }

        if(!isset($child['year'])) {
            $error_str .= "Child year column missing <br/>";
        }


        // Format is okay
        if($error_str == '') {
            // Check each row
            foreach($children as $index=>$child) {
                $index_human = $index + 2; // Base 0 + header row
                if($child['name'] == '') {
                    $error_str .= "Child name data (row {$index_human})<br/>";
                }

                foreach($criteriaSet as $criteria) {
                    if($child[strtolower($criteria->title)] == '') {
                        $error_str .= "Criteria {$criteria->title} data missing (row {$index_human})<br/>";
                    }
                }

                if($child['year'] == '') {
                    $error_str .= "Child year data missing (row {$index_human})<br/>";
                }

                // All okay, update year to use id
                $year = $yearFactory->getYearByTitle($auth_user, $child['year']);
                if(!$year) {
                    $error_str .= "No match found for year {$child['year']} (row {$index_human})<br/>";
                } else {
                    $child['year'] = $year->id;
                }

                // Save updated child
                $children[$index] = $child;
            }

            // Data is sound
            if($error_str == '') {
                // Process children
                foreach($children as $index=>$child) {
                    $children[$index] = processChild($auth_user, $child);
                }

                // Display data
                require_once (VIEW_ROOT.'/results.php');
            } else {
                $action = 'error';
            }
        } else {
            $action = 'error';
        }
    } else {
        $action = 'error';
    }
}

if($action == 'error' && $error_str != '') {
    // Display form
    require_once (VIEW_ROOT.'/import_csv.php');
}