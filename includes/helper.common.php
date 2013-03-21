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
if (!function_exists('custom_debug_admin_page_save_handle')):
function custom_debug_admin_page_save_handle($message = FALSE) {
  if ($_POST) {
    foreach ($_POST as $id => $value) update_option(CUSTOM_DEBUG_SHORTNAME . $id, $value);
    $message = $message ? $message : '<p>' . __('Options saved successfully.') . '</p>';
    print '<div id="message" class="updated">' . $message . '</div>';
  }
}
endif;

/**
 * Include ("render") a view.
 */
if (!function_exists('custom_debug_get_view')):
function custom_debug_get_view($view) {
  include(plugin_dir_path(__FILE__) . '/../views/' . $view . '.php');
}
endif;

/**
 * Get the list of all active users. To be used for admin settings.
 */
if (!function_exists('custom_debug_get_list_of_users')):
function custom_debug_get_list_of_users() {
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
if (!function_exists('custom_debug_get_list_of_posts')):
function custom_debug_get_list_of_posts($type = 'post') {
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
function custom_debug_watchdog($output, $type = 'log') {
  global $wpdb;
  $query = $wpdb->prepare("INSERT INTO {$wpdb->prefix}custom_debug (`time`, `type`, `output`) VALUES (%d, '%s', '%s')", array(
    current_time('timestamp'),
    $type,
    $output,
  ));
  $wpdb->query($query);
}
