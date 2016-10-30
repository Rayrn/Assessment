<?php 
/* Full page begin file  */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

/* Extends clean page begin with menu options */
require_once APP_ROOT.'/inc/clean_page_begin.php';
?>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#siteNav">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span> 
        </button>
        <div class="navbar-header">
            <a class="navbar-brand" href="/"><?php echo SITE_BRAND; ?></a>
        </div>
        <div class="collapse navbar-collapse" id="siteNav">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li <?php if($page_request_clean == 'manage_criteria') { echo 'class="active"'; } ?>><a href="manage_criteria">Manage Criteria</a>
                        <li <?php if($page_request_clean == 'manage_boundries') { echo 'class="active"'; } ?>><a href="manage_boundries">Manage Boundries</a>
                        <li <?php if($page_request_clean == 'manage_years') { echo 'class="active"'; } ?>><a href="manage_years">Manage Years</a>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Generate report
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li <?php if($page_request_clean == 'import_csv') { echo 'class="active"'; } ?>><a href="import_csv">Import CSV</a>
                        <li <?php if($page_request_clean == 'manual_input') { echo 'class="active"'; } ?>><a href="manual_input">Input manually</a>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout"><span class="fa fa-sign-out"></span>&nbsp;Logout</a></li>
            </ul>
        </div>
    </div>
</nav>