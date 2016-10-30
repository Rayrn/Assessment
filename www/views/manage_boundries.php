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
                    <h1 class="title text-warning">Manage Boundries</h1>
                    <hr />
                </div>
            </div>
        </div>

        <div class="col-md-6 col-md-offset-3">
            <div class="row ">
                <div class="col-xs-12">
                    <form method="get" class="pull-right">
                        <select class="form-control" name="year" onchange="submit()">
                            <?php
                            foreach($yearSet as $year) {
                                $selected = $year->id == $sel_year ? 'selected' : '';
                                echo "<option value='{$year->id}' $selected>{$year->title}</option>";
                            }
                            ?>
                        </select>
                    </form>
                </div><!-- /.col-xs-12 -->
            </div><!-- /.row -->

            <div class="row ">
                <div class="col-xs-12">
                    <?php if(isset($error_str) && $error_str != '') { ?>
                        <h4 class="text-danger text-center"><?php echo $error_str; ?></h4>
                    <?php } ?>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="col-xs-1">Grouping</th>
                                <th class="col-xs-6">Title</th>
                                <th class="col-xs-2">Lower</th>
                                <th class="col-xs-2">Higher</th>
                                <th class="col-xs-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($boundrySet)) {
                                foreach($boundrySet as $boundry) : ?>
                                    <tr>
                                        <form method="get">
                                            <input type="hidden" name="action" value="updateBoundry">
                                            <input type="hidden" name="boundry_id" value="<?php echo $boundry->id; ?>">
                                            <input type="hidden" name="limit_id" value="<?php echo isset($boundry->limitData->id) ? $boundry->limitData->id : ''; ?>">
                                            <td>
                                                <table class="boundry-grouping">
                                                    <?php foreach($boundry->groupingSets as $set) { ?>
                                                        <tr>
                                                            <td><?php echo implode(' ', $set); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="title" id="title"  placeholder="Enter boundry title" value="<?php echo $boundry->title; ?>" />
                                                </div><!-- /.form-group -->
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="lower" id="lower" value="<?php echo isset($boundry->limitData->lower_limit) ? $boundry->limitData->lower_limit : ''; ?>" />
                                                </div><!-- /.form-group -->
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="upper" id="upper"  value="<?php echo isset($boundry->limitData->upper_limit) ? $boundry->limitData->upper_limit : ''; ?>" />
                                                </div><!-- /.form-group -->
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-success" title="Save" alt="Save">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach;
                            } else { ?>
                                <tr>
                                    <td colspan="2"><span class="text-muted">No boundries found</span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div><!-- /.col-xs-12 -->
            </div><!-- /.row -->
        </div><!-- /.col-md-6 col-md-offset-3 -->
    </div><!-- /.row -->
</div>

<?php
// Load page end
require_once APP_ROOT.'/inc/page_end.php';