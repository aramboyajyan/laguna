<?php

/**
 * @file
 * Debugging overview page. Lists all items from the log.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */

// Create an instance of our user list class.
$log_table = new Custom_Debug_Logs();
// Fetch, prepare and sort.
$log_table->prepare_items();
// Get log types.
$types = custom_debug_get_log_types();
?>
<div class="wrap">
  
  <div id="icon-edit" class="icon32"><br></div>

  <h2><?php _e('Debugging overview'); ?></h2>

  <div id="custom-debug-log-controls">
    <select id="">
      <option><?php _e('-- View all --'); ?></option>
      <?php foreach ($types as $type): ?>
      <option value="<?php print $type; ?>"><?php print $type; ?></option>
      <?php endforeach; ?>
    </select>
    <a class="button"><?php _e('Filter logs'); ?></a>
    <a class="button"><?php _e('Delete all logs'); ?></a>
  </div>

  <!-- #custom-debug-users-table -->
  <form id="custom-debug-users-table" method="get">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <?php $log_table->display() ?>
  </form>
  <!-- /#custom-debug-users-table -->

</div>
