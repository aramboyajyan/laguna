<select id="custom-debug-filter-logs">
  <option><?php _e('-- View all --'); ?></option>
  <?php foreach ($types as $type): ?>
  <option value="<?php print $type; ?>"><?php print $type; ?></option>
  <?php endforeach; ?>
</select>
<a class="button"><?php _e('Filter logs'); ?></a>
<a class="button"><?php _e('Delete all logs'); ?></a>
