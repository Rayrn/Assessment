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
                    <h1 class="title text-warning">Manage Criteria</h1>
                    <hr />
                </div>
            </div>
        </div>

        <div class="col-md-6 col-md-offset-3">
            <div class="row ">
                <div class="col-xs-12">
                    <?php if(isset($error_str) && $error_str != '') { ?>
                        <h4 class="text-danger text-center"><?php echo $error_str; ?></h4>
                    <?php } ?>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="col-xs-8">Title</th>
                                <th class="col-xs-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($criteriaSet)) {
                                foreach($criteriaSet as $criteria) : ?>
                                    <tr>
                                        <form method="get">
                                            <input type="hidden" name="action" value="updateCriteria">
                                            <input type="hidden" name="id" value="<?php echo $criteria->id; ?>">

                                            <td>
                                                <input type="text" class="form-control" name="title" id="title"  placeholder="Enter criteria title" value="<?php echo $criteria->title; ?>" />
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control" name="status" onchange="submit()">
                                                        <?php
                                                        $options_array = array('2' => 'Active', '3' => 'Suspended');
                                                        foreach($options_array as $value=>$description) {
                                                            $selected = $criteria->status == $value ? 'selected' : '';
                                                            echo "<option value='$value' $selected>$description</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div><!-- /.form-group -->
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach;
                            } else { ?>
                                <tr>
                                    <td colspan="2"><span class="text-muted">No criteria found</span></td>
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