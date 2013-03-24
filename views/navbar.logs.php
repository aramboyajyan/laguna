<!-- #laguna-logs-wrap -->
<div id="laguna-logs-wrap" style="display: none;">

  <!-- #laguna-logs-wrap-inner -->
  <div id="laguna-logs-wrap-inner">

    <?php if (count($logs)): ?>

    <table>

      <thead>
        <th><?php print __('Time'); ?></th>
        <th><?php print __('Type'); ?></th>
        <th><?php print __('Output'); ?></th>
      </thead>

      <tfoot>
        <tr>
          <td colspan="3">
            <?php _e('Total log entries'); ?>: <?php print $total_entries; ?>.
            <a href="<?php print $all_entries_url; ?>"><?php _e('View all log entries.'); ?></a>
            <a class="button" href="<?php print $delete_logs_url; ?>" onclick="return confirm('<?php _e('Are you sure you want to delete all logs?'); ?>');">
              <?php _e('Delete all logs'); ?>
            </a>
          </td>
        </tr>
      </tfoot>

      <tbody>
        <?php foreach ($logs as $log): ?>
        <tr>
          <td><?php print $log->time; ?></td>
          <td><?php print $log->type; ?></td>
          <td><?php print $log->output; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>

    </table>

    <?php else: ?>

    <p><?php _e('There are no logs recorded yet.'); ?></p>

    <?php endif; ?>

  </div>
  <!-- /#laguna-logs-wrap-inner -->

</div>
<!-- /#laguna-logs-wrap -->
