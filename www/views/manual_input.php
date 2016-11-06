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
                    <h1 class="title text-warning">Manual Input</h1>
                    <hr />
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <form method="get" class="pull-right">
                <button type="button" class="btn btn-warning" title="Add" alt="Add" id="add-row">
                    <i class="fa fa-plus-circle"></i>&nbsp;Add additional row
                </button>
            </form>
        </div><!-- /.col-xs-12 -->

        <div class="col-xs-12">
            <?php if(isset($error_str) && $error_str != '') { ?>
                <h4 class="text-danger text-center"><?php echo $error_str; ?></h4>
            <?php } ?>

            <?php if(!empty($criteriaSet)) { ?>
                <form method="get">
                    <table class="table table-striped hidden-xs" id="full-input">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <?php foreach($criteriaSet as $criteria) : ?>
                                    <th><?php echo $criteria->title; ?></th>
                                <?php endforeach; ?>
                                <th>Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            <input type="hidden" name="action" value="process">
                            <!-- Print initial empty block -->
                            <?php echo get_input_row_full($criteriaSet, $yearSet); ?>

                            <tr id="spacer-row-full"><td><!-- Add a blank row to A) add space and B) preserve correct table striping --></td></tr>
                            <tr>
                                <td colspan="6">
                                    <button type="submit" class="btn btn-success btn-lg btn-block hidden-xs" title="Check" alt="Check">
                                        <i class="fa fa-check"></i>&nbsp;Save
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>

                <form method="get">
                    <table class="table table-striped visible-xs" id="mobile-input">
                        <tbody>
                            <input type="hidden" name="action" value="process">
                            <!-- Print initial empty block -->
                            <?php echo get_input_row_mobile($criteriaSet, $yearSet); ?>

                            <tr id="spacer-row-mobile"><td><!-- Add a blank row to A) add space and B) preserve correct table striping --></td></tr>
                            <tr>
                                <td>
                                    <button type="submit" class="btn btn-success btn-lg btn-block visible-xs" title="Check" alt="Check">
                                        <i class="fa fa-check"></i>&nbsp;Save
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            <?php } else { ?>
                <table class="table table-striped">
                    <tr>
                        <td><span class="text-muted">Criteria not set</span></td>
                    </tr>
                </table>
            <?php } ?>
        </div><!-- /.col-xs-12 -->
    </div><!-- /.row -->
</div>

<?php
// Load page end
require_once APP_ROOT.'/inc/page_end.php';