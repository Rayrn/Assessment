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
                    <h1 class="title text-warning">Results</h1>
                    <hr />
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <?php if(isset($error_str) && $error_str != '') { ?>
                <h4 class="text-danger text-center"><?php echo $error_str; ?></h4>
            <?php } ?>

            <?php if(!empty($children)) { ?>
                <form method="post" class="pull-right" action="results">
                    <input type="hidden" name="action" value="download">

                    <?php foreach($children as $child) : ?>
                        <input type="hidden" name="name[]" value="<?php echo $child['name']; ?>" />
                        <?php foreach($criteriaSet as $criteria) { ?>
                            <input type="hidden" name="<?php echo $criteria->id; ?>[]" value="<?php echo $child[strtolower($criteria->title)]; ?>" />
                        <?php } ?>
                        <input type="hidden" name="year[]" value="<?php echo $child['year']->id; ?>" />
                    <?php endforeach; ?>

                    <button type="submit" class="btn btn-success" title="Download" alt="Download">
                        <i class="fa fa-download"></i>&nbsp;Download to CSV
                    </button>
                </form>

                <table class="table table-striped hidden-xs" id="full-input">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Results</th>
                            <th>Year</th>
                            <th>Band</th>
                            <th>Lower</th>
                            <th>Upper</th>
                        </tr>
                    </thead>
                    <tbody>
                        <input type="hidden" name="action" value="process">
                        <!-- Print output -->
                        <?php foreach($children as $child) {
                            echo get_output_row_full($criteriaSet, $child);
                        } ?>
                    </tbody>
                </table>

                <table class="table table-striped visible-xs" id="mobile-input">
                    <tbody>
                        <input type="hidden" name="action" value="process">
                        <!-- Print output -->
                        <?php foreach($children as $child) {
                            echo get_output_row_mobile($criteriaSet, $child);
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <table class="table table-striped">
                    <tr>
                        <td><span class="text-muted">No input found</span></td>
                    </tr>
                </table>
            <?php } ?>
        </div><!-- /.col-xs-12 -->
    </div><!-- /.row -->
</div>

<?php
// Load page end
require_once APP_ROOT.'/inc/page_end.php';