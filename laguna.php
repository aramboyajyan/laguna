<?php

/**
 * @file
 * Plugin deverlopment framework for custom WordPress plugins.
 *
 * Plugin and custom plugin framework created by: Topsitemakers.
 * http://www.topsitemakers.com/
 */

/**
 * Plugin name: Laguna Framework
 * Description: Custom plugin framework that contains many parts WordPress is missing for proper coding of custom plugins. Logging, custom admin pages, several security measures, view rendering, flash messaging and more.
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
require dirname(__FILE__) . '/includes/helper.form.php';

// Log items table class. Allow overriding from other plugins if necessary.
if (!class_exists('Laguna_Logs')) {
  require dirname(__FILE__) . '/includes/class.logs.php';
}

/**
 * Main plugin class.
 */
class Laguna {

  // Plugin name; to be used throughout this class has to be the same as the
  // plugin folder name.
  var $namespace = 'laguna';

  /**
   * Constructor.
   */
  function __construct() {
    // Actions.
    add_action('init', array(&$this, 'init'));
    add_action('admin_init', array(&$this, 'admin_init'));
    add_action('admin_menu', array(&$this, 'admin_menu'));
    add_action('admin_notices', array(&$this, 'admin_notices'));
    add_action('admin_bar_menu', array(&$this, 'admin_bar_menu'));
    add_action('wp_after_admin_bar_render', array(&$this, 'render_menu'));
    
    // Actions used for recreating the session. Make sure the callback for
    // recreating the session is called last upon login/logout.
    add_action('init', array(&$this, 'recreate_session'));
    add_action('wp_login', array(&$this, 'recreate_session'), 100);
    add_action('wp_logout', array(&$this, 'recreate_session'), 100);

    // Registers.
    register_activation_hook(__FILE__, array(&$this, 'install'));

    // Filters.
    // Change message displayed upon unsuccessful user login. This is a
    // security measure to prevent potential attackers find out which part of
    // the credentials are not correct. The callback function will display a
    // configurable generic message to the user.
    if (get_option(LAGUNA_SHORTNAME . 'login_errors_enabled')) {
      add_filter('login_errors', array(&$this, 'login_errors'));
    }
    // Remove WordPress version from the HTML output.
    add_filter('the_generator', array(&$this, 'the_generator'));
  }

  /**
   * Plugin installation.
   */
  public function install() {

    global $wpdb;
    
    // Define table names.
    $table_name_laguna   = $wpdb->prefix . 'laguna_log';
    
    // Check if the tables already exist.
    if ($wpdb->get_var("SHOW TABLES LIKE '" . $table_name_laguna . "'") != $table_name_laguna) {
      // Table SQL
      $table_laguna = "CREATE TABLE IF NOT EXISTS `{$table_name_laguna}` (
                        `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
                        `ip_address` varchar(100) NOT NULL COMMENT 'IP address of the computer who triggered the log entry.',
                        `time` int(11) NOT NULL COMMENT 'UNIX timestamp of when the event was logged.',
                        `type` varchar(128) NOT NULL COMMENT 'Type of the logged message.',
                        `output` text COMMENT 'Logged output.',
                        PRIMARY KEY (`ID`)
                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Debug logging for custom plugin development.' AUTO_INCREMENT=1 ;";

      // Get the upgrade PHP and create the tables.
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($table_laguna);
    }
    
    /**
     * Setup default values of the variables.
     *
     * The reason we are double checking if the value is set, is to prevent
     * overwriting the settings when the plugin is already installed and is
     * just disabled and enabled again.
     */
    // Number of rows to be displayed.
    if (!laguna_option_exists(LAGUNA_SHORTNAME . 'rows_to_display')) {
      add_option(LAGUNA_SHORTNAME . 'rows_to_display', '15');
    }
    // Enable by default the login errors override. If necessary this can be
    // changed from admin panel.
    if (!laguna_option_exists(LAGUNA_SHORTNAME . 'login_errors_enabled')) {
      add_option(LAGUNA_SHORTNAME . 'login_errors_enabled', TRUE);
    }
    // Default text displayed on unsuccessful login.
    if (!laguna_option_exists(LAGUNA_SHORTNAME . 'login_error_text')) {
      add_option(LAGUNA_SHORTNAME . 'login_error_text', 'Username and/or password is incorrect. Please try again.');
    }
    // Default date format.
    if (!laguna_option_exists(LAGUNA_SHORTNAME . 'date_format')) {
      add_option(LAGUNA_SHORTNAME . 'date_format', 'F d Y, H:i:s');
    }

  }

  /**
   * General init.
   */
  public function init() {
    
    // Common styles.
    wp_enqueue_style($this->namespace . '-style-common', plugins_url($this->namespace . '/assets/css/common.css'));
    // Common scripts.
    wp_enqueue_script($this->namespace . '-script-common', plugins_url($this->namespace . '/assets/js/common.js'), array('jquery'));
    
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
    // if (!session_id()) {
      // session_start();
    // }
  }

  /**
   * Define links for administrators.
   */
  public function admin_menu() {

    // Main settings page.
    add_menu_page(__('Developer'), __('Developer'), 'manage_options', $this->namespace . '/admin-pages/view-log.php');

    // Subpages.
    add_submenu_page($this->namespace . '/admin-pages/view-log.php', __('View log'), __('View log'), 'manage_options', $this->namespace . '/admin-pages/view-log.php');
    add_submenu_page($this->namespace . '/admin-pages/view-log.php', __('Settings'), __('Settings'), 'manage_options', $this->namespace . '/admin-pages/settings.php');

  }

  /**
   * Add debugging link to the admin navigation bar.
   */
  public function admin_bar_menu() {
    global $wp_admin_bar;
    $wp_admin_bar->add_menu(array(
      'id' => 'laguna',
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
    $rows_to_display = get_option(LAGUNA_SHORTNAME . 'rows_to_display');
    $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}laguna_log ORDER BY `time` DESC LIMIT 0, %d", array($rows_to_display));
    $logs = $wpdb->get_results($query);
    // Format the time in logs.
    $format = get_option(LAGUNA_SHORTNAME . 'date_format');
    foreach ($logs as $id => $log) {
      $logs[$id]->time = date($format, $log->time);
    }
    // URL for the "See all entries" link.
    $all_entries_url = laguna_options_page_path('view-log');
    // URL for the "Delete all logs" button.
    $delete_logs_url = $all_entries_url . '&delete=1';
    // Count total number of log entries in the database.
    $total_entries_query = $wpdb->prepare("SELECT COUNT(`ID`) FROM {$wpdb->prefix}laguna_log", array());
    $total_entries   = $wpdb->get_var($total_entries_query);
    laguna_get_view('navbar.logs', array(
      'logs' => $logs,
      'delete_logs_url' => $delete_logs_url,
      'total_entries' => $total_entries,
      'all_entries_url' => $all_entries_url,
    ));
  }

  /**
   * Login errors override.
   */
  public function login_errors() {
    $login_error_text = get_option(LAGUNA_SHORTNAME . 'login_error_text');
    return __($login_error_text);
  }

  /**
   * Remove WordPress version from the HTML output.
   */
  public function the_generator() {
    return '';
  }

  /**
   * Show flash messages in admin area.
   */
  public function admin_notices() {
    // Check if there are any messages to be displayed.
    if (isset($_SESSION['laguna_admin_messages']) && is_array($_SESSION['laguna_admin_messages'])) {
      $messages_group = $_SESSION['laguna_admin_messages'];
      foreach ($messages_group as $class => $messages) {
        foreach ($messages as $message) {
          laguna_get_view('admin.notice', array('class' => $class, 'message' => $message));
        }
      }
    }
    // Remove all messages from session.
    unset($_SESSION['laguna_admin_messages']);
  }

}

// Initiate the plugin.
new Laguna();
