<?php

/**
 * @file
 * Lists all items from the log.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */

// Check if the logs should be deleted.
if (isset($_GET['delete']) && $_GET['delete']) {
  laguna_delete_all_logs();
  wp_redirect(laguna_options_page_path('overview'));
  exit();
}

// Create an instance of our user list class.
$log_table = new Laguna_Logs();
// Fetch, prepare and sort.
$log_table->prepare_items();
?>
<div class="wrap">
  
  <div id="icon-edit" class="icon32"><br></div>

  <h2><?php _e('Debugging overview'); ?></h2>

  <!-- #laguna-logs-table -->
  <form id="laguna-logs-table" method="get">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <?php $log_table->display() ?>
  </form>
  <!-- /#laguna-logs-table -->

</div>
