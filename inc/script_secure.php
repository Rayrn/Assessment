<?php
/* Security file */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

// Log script start time
$script_start = microtime(true); //Use floating point notation

// Start session (if needed)
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
}

// Setup autoload
spl_autoload_register(function ($class_name) {
    require_once CLASS_ROOT.'/'.$class_name.'.php';
});

// Load DB
require_once 'db_connect.php';

// Check if user is authorised
$auth_user = Security::isAuth($pdo);