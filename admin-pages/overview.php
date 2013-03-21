<?php

/**
 * @file
 * Debugging overview page. Lists all items from the log.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */

// Check if the logs should be deleted.
if (isset($_GET['delete']) && $_GET['delete']) {
  luna_delete_all_logs();
  wp_redirect(luna_options_page_path('overview'));
  exit();
}

// Create an instance of our user list class.
$log_table = new Luna_Logs();
// Fetch, prepare and sort.
$log_table->prepare_items();
?>
<div class="wrap">
  
  <div id="icon-edit" class="icon32"><br></div>

  <h2><?php _e('Debugging overview'); ?></h2>

  <!-- #luna-users-table -->
  <form id="luna-users-table" method="get">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <?php $log_table->display() ?>
  </form>
  <!-- /#luna-users-table -->

</div>
