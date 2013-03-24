<div id="laguna-table-header">

  <select id="laguna-log-filters">
    <option><?php _e('-- View all --'); ?></option>
    <?php foreach ($log_filters as $log_filter): ?>
    <option <?php print ($current_filter == $log_filter) ? 'selected="selected"' : ''; ?> value="<?php print $log_filter; ?>"><?php print $log_filter; ?></option>
    <?php endforeach; ?>
  </select>

  <a id="laguna-filter-trigger" data-href="<?php print $filter_logs_url; ?>" class="button"><?php _e('Filter logs'); ?></a>

  <a href="<?php print $delete_logs_url; ?>" class="button" onclick="return confirm('<?php _e('Are you sure you want to delete all logs?'); ?>');">
    <?php _e('Delete all logs'); ?>
  </a>

</div>
