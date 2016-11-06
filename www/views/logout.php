<?php
/* Logout Form Display */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

// Load page head
require_once APP_ROOT.'/inc/clean_page_begin.php';
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
                <h2>Goodbye!</h2>
                <p class="error-text">
                    See you soon!
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