<?php
// Environment
define('ENVIRONMENT', 'Prod');                           // What environment are we in (DEV/TEST/PROD)

// File System
define('APP_ROOT', dirname(__FILE__));                  // where am I installed
define('CLASS_ROOT', APP_ROOT.'/classes');              // Where to find php classes
define('ERROR_ROOT', APP_ROOT.'/www/error_pages');      // Where to find error pages
define('CONTROL_ROOT', APP_ROOT.'/www/controllers');    // Where to find controllers
define('VIEW_ROOT', APP_ROOT.'/www/views');             // Where to find views

// Where Am I?
$cfg_prot = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on' ? 'https' : 'http' ;
$cfg_webdir = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__));
$cfg_webdir = str_replace('\\', '/', $cfg_webdir);

define('WEB_DIR', $cfg_webdir.'/');
define('WEB_ROOT', $cfg_prot.'://'.$_SERVER['HTTP_HOST'].$cfg_webdir);

// Web
define('STYLE_ROOT', WEB_ROOT.'/assets/css');	// Where to find css files

// User Authentication
define('TIMEOUT_MINS', '180');                          // maximum time between actions before login is required

// Branding
define('SITE_BRAND', 'Aestimium');

// Site Toggles
define('ALLOW_REG', TRUE);                              // Allow/Disallow new users to register