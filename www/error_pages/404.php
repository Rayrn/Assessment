<?php
/* 404 Page Display */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

// Set response code 404
header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);

// Load page head (vary based on user status)
require_once $auth_user ? APP_ROOT.'/inc/page_begin.php' : APP_ROOT.'/inc/clean_page_begin.php';
?>
<div class="container text-center">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel-heading">
               <div class="panel-title text-center">
                    <h1 class="title text-warning"><?php echo SITE_BRAND; ?></h1>
                    <hr />
                </div>
            </div>
        </div><!-- /.col-xs-12 -->

        <div class="col-md-6 col-md-offset-3">
            <div class="row">
                <div class="col-xs-12">
                <h2>404 Not Found</h2>
                <p class="error-text">
                    Sorry, an error has occured, requested page not found!
                </p>
                <a href="<?php echo WEB_ROOT; ?>" class="btn btn-warning btn-lg btn-block"><span class="fa fa-home"></span>&nbsp;Take Me Home </a>
                </div><!-- /.col-xs-12 -->
            </div><!-- /.row -->
        </div><!-- /.col-md-6 col-md-offset-3 -->
    </div><!-- /.row -->

    </div><!-- /.row -->
</div><!-- /.container -->

<?php
// Load page end
require_once APP_ROOT.'/inc/page_end.php';