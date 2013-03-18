<?php

/**
 * @file
 * Custom helper functions used in the plugin.
 *
 * All plugin-specific helper functions should be added here.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */

/**
 * Log output to the database.
 */
function custom_debug_watchdog($output, $type = 'log') {
  global $wpdb;
  $query = $wpdb->prepare("INSERT INTO {$wpdb->prefix}_custom_debug (`time`, `type`, `output`) VALUES (%d, '%s', '%s')", array(
    current_time('timestamp'),
    $type,
    $output,
  ));
  $wpdb->query($query);
}
