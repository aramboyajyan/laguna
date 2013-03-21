<!-- #custom-debug-wrap -->
<div id="custom-debug-wrap" style="display: none;">

  <?php if (count($logs)): ?>

  <table>

    <thead>
      <th><?php print __('Time'); ?></th>
      <th><?php print __('Type'); ?></th>
      <th><?php print __('Output'); ?></th>
    </thead>

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
<!-- /#custom-debug-wrap -->
