<?php

/**
 * @file
 * Debugging plugin for custom development.
 *
 * Plugin and custom plugin framework created by: Topsitemakers.
 * http://www.topsitemakers.com/
 */

/**
 * Plugin name: Custom debugging
 * Description: Debugging plugin for custom development. Allows developers to log any information from the code and easily access it through the admin navbar.
 * Author: Topsitemakers
 * Author URI: http://www.topsitemakers.com/
 * Version: 1.0
 */

// Sanity check.
if (!defined('ABSPATH')) die('Direct access is not allowed.');

// Helper functions.
require dirname(__FILE__) . '/includes/helper.common.php';
require dirname(__FILE__) . '/includes/helper.custom.php';

// Constant variables used in the plugin.
require dirname(__FILE__) . '/includes/constants.php';

/**
 * Main plugin class.
 */
class Custom_Debug {

  // Plugin name; to be used throughout this class
  // Has to be the same as the plugin folder name.
  var $namespace = 'custom_debug';

  /**
   * Constructor.
   */
  function __construct() {
    // Localization.
    load_plugin_textdomain($this->namespace . '-locale', FALSE, dirname(plugin_basename(__FILE__)) . '/lang');
    // Actions.
    add_action('init', array(&$this, 'init'));
    add_action('admin_init', array(&$this, 'admin_init'));
    add_action('admin_menu', array(&$this, 'admin_menu'));
    add_action('wp_ajax_nopriv_' . $this->namespace . '_ajax', array(&$this, 'ajax'));
    add_action('wp_ajax_' . $this->namespace . '_ajax', array(&$this, 'ajax'));
    // Actions used for recreating the session. Make sure the callback for
    // recreating the session is called last upon login/logout.
    add_action('init', array(&$this, 'recreate_session'));
    add_action('wp_login', array(&$this, 'recreate_session'), 100);
    add_action('wp_logout', array(&$this, 'recreate_session'), 100);
    // Registers.
    register_activation_hook(__FILE__, array(&$this, 'install'));
  }

  /**
   * Plugin installation.
   */
  public function install() {

    global $wpdb;
    
    // Define table names.
    $table_name_sample   = $wpdb->prefix . 'boilerplate_sample';
    
    // Check if the tables already exist.
    if ($wpdb->get_var("SHOW TABLES LIKE '" . $table_name_sample . "'") != $table_name_sample) {
      // Table SQL
      $table_sample = "CREATE TABLE " . $table_name_sample . "(
                        sid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        time INT NOT NULL,
                        text VARCHAR(8) NOT NULL);";
      
      // Get the upgrade PHP and create the tables.
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($table_sample);
    }
    
    // Setup default values of the variables.
    add_option(CUSTOM_DEBUG_SHORTNAME . 'rows_to_display', '15');

  }

  /**
   * Front-end init.
   */
  public function init() {
    
    // Front-end styles.
    wp_enqueue_style($this->namespace . '-style', plugins_url($this->namespace . '/assets/css/front.css'));
    // Front-end scripts.
    wp_enqueue_script($this->namespace . '-script', plugins_url($this->namespace . '/assets/js/front.js'), array('jquery'));
    
    // Hook our cron.
    if (!wp_next_scheduled($this->namespace . '_execute_cron')) {
      wp_schedule_event(current_time('timestamp'), 'every_minute', $this->namespace . '_execute_cron');
    }

  }

  /**
   * Admin init.
   */
  public function admin_init() {

    // Admin styles.
    wp_enqueue_style($this->namespace . '-style-admin', plugins_url($this->namespace . '/assets/css/admin.css'));
    wp_enqueue_style('thickbox');
    // Admin scripts.
    wp_enqueue_script($this->namespace . '-script-admin', plugins_url($this->namespace . '/assets/js/admin.js'), array('jquery'));
    wp_enqueue_script('media-upload');

  }

  /**
   * Start and/or recreate the session.
   */
  public function recreate_session() {
    if (session_id()) {
      session_destroy();
      session_start();
    }
    else {
      session_start();
    }
  }

  /**
   * Define links for administrators.
   */
  public function admin_menu() {

    // Main settings page.
    add_menu_page(__('Debugging'), __('Debugging'), 'manage_options', $this->namespace . '/admin-pages/overview.php');

    // Subpages.
    add_submenu_page($this->namespace . '/admin-pages/overview.php', __('Overview'), __('Overview'), 'manage_options', $this->namespace . '/admin-pages/overview.php');
    add_submenu_page($this->namespace . '/admin-pages/overview.php', __('Settings'), __('Settings'), 'manage_options', $this->namespace . '/admin-pages/settings.php');

  }

  /**
   * AJAX callback.
   */
  public function ajax() {
    
    // Check if the nonces match.
    if (!wp_verify_nonce($_POST['nonce'], $this->namespace . '-post-nonce')) die('Disallowed action.');

    // Check the operation.
    $op = filter_input(INPUT_POST, 'op', FILTER_SANITIZE_STRING);
    if (!$op) die('Disallowed operation.');

    // Perform the actions.
    global $wpdb;
    switch ($op) {

      // Sample AJAX callback action.
      case 'settings':
        break;

      // Default handler.
      default:
        die('Disallowed action.');

    }

    // Required by WP.
    exit;

  }

}

// Initiate our plugin.
new Custom_Debug();
