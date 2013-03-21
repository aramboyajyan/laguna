<?php

/**
 * @file
 * Main helper functions used in the plugin.
 *
 * These functions are used for admin pages and view rendering.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */

/**
 * Handle saving of admin settings data - mass.
 */
if (!function_exists('luna_admin_page_save_handle')):
function luna_admin_page_save_handle($message = FALSE) {
  if ($_POST) {
    foreach ($_POST as $id => $value) update_option(LUNA_SHORTNAME . $id, $value);
    $message = $message ? $message : '<p>' . __('Options saved successfully.') . '</p>';
    print '<div id="message" class="updated">' . $message . '</div>';
  }
}
endif;

/**
 * Include ("render") a view.
 */
if (!function_exists('luna_get_view')):
function luna_get_view($view, $variables = array(), $return = FALSE) {
  if (is_array($variables) && count($variables)) {
    extract($variables);
  }
  // Get the view content.
  ob_start();
  require plugin_dir_path(__FILE__) . '/../views/' . $view . '.php';
  $output = ob_get_contents();
  ob_end_clean();
  // Print it out or return the value.
  if ($return) {
    return $output;
  }
  print $output;
}
endif;

/**
 * Get the list of all active users. To be used for admin settings.
 */
if (!function_exists('luna_get_list_of_users')):
function luna_get_list_of_users() {
  global $wpdb;
  $users_results = $wpdb->get_results("SELECT `ID`, `user_login` FROM $wpdb->users WHERE `user_status` = 0 ORDER BY `user_login` ASC");
  $users = array(0 => __('-- Please select --'));
  foreach ($users_results as $user) {
    $users[$user->ID] = $user->user_login . ' (ID: ' . $user->ID . ')';
  }
  
  return $users;
}
endif;

/**
 * Get the list of all published posts. To be used for admin settings.
 * Can optionally filter the posts according to a specific post type.
 */
if (!function_exists('luna_get_list_of_posts')):
function luna_get_list_of_posts($type = 'post') {
  global $wpdb;
  $posts_results = $wpdb->get_results($wpdb->prepare("SELECT `ID`, `post_title` FROM $wpdb->posts WHERE `post_type` = '%s' AND `post_status` = 'publish' ORDER BY `post_title` ASC", array($type)));
  $posts = array(0 => __('-- Please select --'));
  foreach ($posts_results as $post) {
    $posts[$post->ID] = $post->post_title . ' (ID: ' . $post->ID . ')';
  }

  return $posts;
}
endif;

/**
 * Log output to the database.
 */
if (!function_exists('luna_log')):
function luna_log($output, $type = 'log', $pre = FALSE) {
  global $wpdb;
  
  // If output is an array/object, convert it to a string.
  if (is_array($output) || is_object($output)) {
    $output = print_r($output, TRUE);
  }

  // Wrap everything in <pre>tags</pre>.
  if ($pre) {
    $output = '<pre>' . $output . '</pre>';
  }
  
  // For ease of use and further processing, $log name has to be lowercase and
  // without spaces or any special characters. Make sure the format is proper.
  $type = $type ? $type : 'log';
  $type = preg_replace('/[^A-Za-z0-9]_-/', '', $type);
  
  // Log the event.
  $query = $wpdb->prepare("INSERT INTO {$wpdb->prefix}luna (`time`, `type`, `output`) VALUES (%d, '%s', '%s')", array(
    current_time('timestamp'),
    $type,
    $output,
  ));
  $wpdb->query($query);
}
endif;

/**
 * Check if an option exists in the database.
 *
 * This is used for reactivating the plugin and avoiding to rewrite existing
 * settings on the site. The reason regular get_option() won't work properly
 * in this case is when you have booleans as values - if the value is set to
 * FALSE, the response will be the same as if the value does not exist in the
 * database at all.
 */
if (!function_exists('luna_option_exists')):
function luna_option_exists($option_name) {
  global $wpdb;
  $query = $wpdb->prepare("SELECT `option_id` FROM $wpdb->options WHERE `option_name` = '%s'", array($option_name));

  return $wpdb->get_var($query);
}
endif;

/**
 * Get all types of logs that exist in the database.
 */
if (!function_exists('luna_get_log_types')):
function luna_get_log_types() {
  global $wpdb;
  // Default type.
  $types_list = array(LUNA_DEFAULT_LOG_TYPE);
  $query = $wpdb->prepare("SELECT `type` FROM {$wpdb->prefix}luna WHERE `type` != '%s' GROUP BY `type` ORDER BY `type` ASC", array(LUNA_DEFAULT_LOG_TYPE));
  $types = $wpdb->get_results($query);
  foreach ($types as $type) {
    $types_list[] = $type->type;
  }

  return $types_list;
}
endif;

/**
 * Delete all saved logs from the database. Optionally delete all logs of a
 * specific type.
 */
if (!function_exists('luna_delete_all_logs')):
function luna_delete_all_logs($type = FALSE) {
  global $wpdb;
  // Delete all logs of a specific type.
  if ($type) {
    $query = $wpdb->prepare("DELETE FROM {$wpdb->prefix}luna WHERE `type` = '%s'", array($type));
  }
  else {
    $query = $wpdb->prepare("DELETE FROM {$wpdb->prefix}luna");
  }
  $wpdb->query($query);
}
endif;

/**
 * Get path to an options page.
 */
if (!function_exists('luna_options_page_path')):
function luna_options_page_path($page, $absolute = TRUE) {
  $options_page = '/wp-admin/admin.php?page=luna/admin-pages/' . $page . '.php';
  
  return $absolute ? get_site_url() . $options_page : $options_page;
}
endif;

/**
 * Displays a message for administrator.
 */
if (!function_exists('luna_display_message')):
function luna_display_message($message, $class = 'update') {
  $messages = array();
  if (isset($_SESSION['luna_admin_messages']) && is_array($_SESSION['luna_admin_messages'])) {
    $messages = $_SESSION['luna_admin_messages'];
  }
  // Make sure to pass TRUE as the last argument for luna_get_view() function;
  // that will return the output of the view instead of printing it out to the
  // screen.
  $messages[$class][] = $message;
  // Add message to the list.
  $_SESSION['luna_admin_messages'] = $messages;
  luna_log($_SESSION['luna_admin_messages']);
}
endif;
