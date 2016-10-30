<?php
/* Logout Form Processing */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

session_destroy();

// Display page
require_once (VIEW_ROOT.'/logout.php');