<?php
/********************
 * Begin Page Setup *
 ********************/
// Load site config
require_once 'config.php';

// Load script secure
require_once APP_ROOT.'/inc/script_secure.php';

/******************
 * End Page Setup *
 ******************/

/**********************
 * Begin Page Display *
 **********************/
// Format request URI to ensure / and .php appear only where expected
$page_request_parts = explode('?', $_SERVER['REQUEST_URI']);
$page_request_clean = basename($page_request_parts[0], '.php');
$page_request_sys = "/{$page_request_clean}.php";


// Check if requested page exists
if($_SERVER['REQUEST_URI'] == '' || $_SERVER['REQUEST_URI'] == '/') {
    if($auth_user) {
        header('Location: /home');
        exit();
    } else {
        header('Location: /login');
        exit();
    }
} else {
    // Check if file exists
    if(file_exists(APP_ROOT.CONTROL_ROOT.$page_request_sys)) {
        // Include controller
        require_once APP_ROOT.CONTROL_ROOT.$page_request_sys;
    } else {
        // Redirect to 404
        require_once APP_ROOT.ERROR_ROOT.'/404.php';
    }
}
/********************
 * End Page Display *
 ********************/

/******************
 * Log Performance*
 ******************/
require_once APP_ROOT.'/inc/log.php';
die();