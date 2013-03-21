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

// Constant variables used in the plugin.
require dirname(__FILE__) . '/includes/constants.php';

// Helper functions.
require dirname(__FILE__) . '/includes/helper.common.php';
require dirname(__FILE__) . '/includes/helper.custom.php';

// Log items table class.
require dirname(__FILE__) . '/includes/class.logs.php';

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
    add_action('admin_bar_menu', array(&$this, 'admin_bar_menu'));
    add_action('wp_after_admin_bar_render', array(&$this, 'render_menu'));
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
    $table_name_custom_debug   = $wpdb->prefix . 'custom_debug';
    
    // Check if the tables already exist.
    if ($wpdb->get_var("SHOW TABLES LIKE '" . $table_name_custom_debug . "'") != $table_name_custom_debug) {
      // Table SQL
      $table_custom_debug = "CREATE TABLE IF NOT EXISTS `{$table_name_custom_debug}` (
                              `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
                              `time` int(11) NOT NULL COMMENT 'UNIX timestamp of when the event was logged.',
                              `type` varchar(128) NOT NULL COMMENT 'Type of the logged message.',
                              `output` text COMMENT 'Logged output.',
                              PRIMARY KEY (`ID`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Debug logging for custom plugin development.' AUTO_INCREMENT=1 ;";
      
      // Get the upgrade PHP and create the tables.
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($table_custom_debug);
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
    add_submenu_page($this->namespace . '/admin-pages/overview.php', __('View log'), __('View log'), 'manage_options', $this->namespace . '/admin-pages/overview.php');
    add_submenu_page($this->namespace . '/admin-pages/overview.php', __('Settings'), __('Settings'), 'manage_options', $this->namespace . '/admin-pages/settings.php');

  }

  /**
   * Add debugging link to the admin navigation bar.
   */
  public function admin_bar_menu() {
    global $wp_admin_bar;
    $wp_admin_bar->add_menu(array(
      'id' => 'custom-debug',
      'parent' => 'top-secondary',
      'title' => __('Logging'),
    ));
  }

  /**
   * Render our menu.
   */
  public function render_menu() {
    global $wpdb;
    // Get recent logs.
    $rows_to_display = get_option(CUSTOM_DEBUG_SHORTNAME . 'rows_to_display');
    $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}custom_debug ORDER BY `time` DESC LIMIT 0, %d", array($rows_to_display));
    $logs = $wpdb->get_results($query);
    custom_debug_get_view('custom-debug');
  }

}

// Initiate our plugin.
new Custom_Debug();
