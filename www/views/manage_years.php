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
                    <h1 class="title text-warning">Manage Years</h1>
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
                                <th class="col-xs-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($yearSet)) {
                                foreach($yearSet as $year) : ?>
                                    <tr>
                                        <form method="post">
                                            <input type="hidden" name="action" value="updateYear">
                                            <input type="hidden" name="id" value="<?php echo $year->id; ?>">

                                            <td>
                                                <input type="text" class="form-control" name="title" id="title"  placeholder="Enter year title" value="<?php echo $year->title; ?>" />
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <select class="form-control" name="status" onchange="submit()">
                                                        <?php
                                                        $options_array = array('2' => 'Active', '3' => 'Suspended');
                                                        foreach($options_array as $value=>$description) {
                                                            $selected = $year->status == $value ? 'selected' : '';
                                                            echo "<option value='$value' $selected>$description</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div><!-- /.form-group -->

                                                <?php if($year->status == '3') { ?>
                                                    <a href="?action=updateYear&id=<?php echo $year->id; ?>&status=7" class="btn btn-lg btn-danger" title="Delete" alt="Delete">
                                                        <i class="fa fa-warning"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-success" title="Save" alt="Save">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach;
                            } else { ?>
                                <tr>
                                    <td colspan="2"><span class="text-muted">No years found</span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div><!-- /.col-xs-12 -->
            </div><!-- /.row -->
        </div><!-- /.col-md-6 col-md-offset-3 -->

        <div class="col-md-6 col-md-offset-3">
            <div class="row">
                <div class="col-xs-12">
                    <button type="button" class="btn btn-success pull-right" data-toggle="collapse" data-target="#add-year-form">Add new year</button>
                </div>
                <div class="col-xs-12 collapse" id="add-year-form">
                    <form method="post">
                        
                        <?php if(isset($error_str) && $error_str != '') { ?>
                            <h4 class="text-danger text-center"><?php echo $error_str; ?></h4>
                        <?php } ?>
                        
                        <input type="hidden" name="action" value="addYear"/>

                        <div class="form-group">
                            <label for="title" class="cols-xs-2 control-label text-warning">Title</label>
                            <div class="cols-xs-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-clipboard fa" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" name="title" id="title"  placeholder="Enter year title" required/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title" class="cols-xs-2 control-label text-warning">Status</label>
                            <div class="cols-xs-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-clipboard fa" aria-hidden="true"></i></span>
                                    <select class="form-control" disabled>
                                        <option value="2">Active</option>
                                        <option value="3">Suspended</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-lg btn-block">Add</button>
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