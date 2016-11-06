<?php
/* Registration Form Display */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

// Load page head
require_once APP_ROOT.'/inc/page_begin.php';
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel-heading">
               <div class="panel-title text-center">
                    <h1 class="title text-warning">Import CSV</h1>
                    <hr />
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <?php if(isset($error_str) && $error_str != '') { ?>
                <h4 class="text-danger text-center"><?php echo $error_str; ?></h4>
            <?php } ?>

            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="process">
                <div class="form-group">
                    <label for="upload" class="control-label text-warning">Upload file</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-upload" aria-hidden="true"></i></span>
                        <input type="file" class="form-control" name="upload" id="upload" />
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-lg btn-block hidden-xs" title="Save" alt="Save">
                    <i class="fa fa-check"></i>&nbsp;Save
                </button>
            </form>
        </div><!-- /.col-xs-12 -->
    </div><!-- /.row -->
</div>

<?php
// Load page end
require_once APP_ROOT.'/inc/page_end.php';