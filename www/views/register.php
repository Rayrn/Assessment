<?php
/* Registration Form Display */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

// Load page head
require_once APP_ROOT.'/inc/clean_page_begin.php';
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel-heading">
               <div class="panel-title text-center">
                    <h1 class="title text-warning"><?php echo SITE_BRAND; ?></h1>
                    <hr />
                </div>
            </div>
        </div>

        <div class="col-md-6 col-md-offset-3">
            <div class="row ">
                <div class="col-xs-12">
                    <form method="post">
                        
                        <?php if(isset($error_str) && $error_str != '') { ?>
                            <h4 class="text-danger text-center"><?php echo $error_str; ?></h4>
                        <?php } ?>
                        
                        <input type="hidden" name="action" value="register"/>

                        <div class="form-group">
                            <label for="first_name" class="cols-xs-2 control-label text-warning">First name</label>
                            <div class="cols-xs-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" name="first_name" id="first_name"  placeholder="Enter your first name" required value="<?php echo $first_name_display; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="last_name" class="cols-xs-2 control-label text-warning">Last name</label>
                            <div class="cols-xs-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" name="last_name" id="last_name"  placeholder="Enter your last name" required value="<?php echo $last_name_display; ?>"/>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="email" class="cols-xs-2 control-label text-warning">Email</label>
                            <div class="cols-xs-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" name="email" id="email"  placeholder="Enter your email" required value="<?php echo $email_display; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_1" class="cols-xs-2 control-label text-warning">Password</label>
                            <div class="cols-xs-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock fa" aria-hidden="true"></i></span>
                                    <input type="password" class="form-control" name="password_1" id="password_1"  placeholder="Enter your password" required/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_2" class="cols-xs-2 control-label text-warning">Password (confirmation)</label>
                            <div class="cols-xs-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock fa" aria-hidden="true"></i></span>
                                    <input type="password" class="form-control" name="password_2" id="password_2"  placeholder="Enter your password" required/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-warning btn-lg btn-block">Register</button>
                        </div>
                    </form>
                </div><!-- /.col-xs-12 -->
            </div><!-- /.row -->
        </div><!-- /.col-md-6 col-md-offset-3 -->
    </div><!-- /.row -->
</div>

<?php
// Load page end
require_once APP_ROOT.'/inc/page_end.php';