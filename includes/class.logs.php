<?php

/**
 * @file
 * Custom table list class.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */

// Load base class if it doesn't exist.
if(!class_exists('WP_List_Table')) require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

// Plugin class.
class Laguna_Logs extends WP_List_Table {
  
  /**
   * Construct function.
   */
  public function __construct() {
    global $status;
    global $page;
    // Set parent defaults.
    parent::__construct(array(
      'ajax'     => FALSE,
      'singular' => 'log',
      'plural'   => 'logs',
    ));
  }
  
  /**
   * Define column methods.
   */
  public function column_default($item, $column_name) {
    switch ($column_name) {
      case 'time':
      case 'type':
      case 'output':
        return $item[$column_name];
      default:
        // Debug.
        // return print_r($item, TRUE);
    }
  }

  /**
   * Define columns.
   */
  public function get_columns() {
    return array(
      'time'   => __('Time'),
      'type'   => __('Type'),
      'output' => __('Output'),
    );
  }
  
  /**
   * Sortable settings.
   */
  public function get_sortable_columns() {
    return array(
      'time' => array('time', TRUE),
      'type' => array('type', FALSE),
    );
  }
  
  /**
   * Message displayed to the admin when there are no results.
   */
  public function no_items() {
    _e('There are no logs in the database at the moment.');
  }

  /**
   * Prepare the data.
   */
  public function prepare_items() {
    
    // Number of items per page.
    $per_page = 25;
    
    // Column headers.
    $columns  = $this->get_columns();
    $hidden   = array();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array($columns, $hidden, $sortable);
    
    /**
     * Prepare the data.
     */
    global $wpdb;
    // Order by field.
    $orderby = 'time';
    if (isset($_GET['orderby']) && array_key_exists($_GET['orderby'], $sortable)) {
      $orderby = $_GET['orderby'];
    }
    // Order type.
    $order = 'DESC';
    if (isset($_GET['order']) && in_array($_GET['order'], array('asc', 'desc'))) {
      $order = strtoupper($_GET['order']);
    }

    // Get the data.
    $filter = $this->laguna_current_filter();
    if (!empty($filter)) {
      $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}laguna_log WHERE `type` = '%s' ORDER BY `{$orderby}` {$order}", array($filter)), ARRAY_A);
    }
    else {
      $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}laguna_log ORDER BY `{$orderby}` {$order}", array()), ARRAY_A);
    }

    foreach ($data as $id => $log) {
      // Construct sample action links.
      $format = get_option(LAGUNA_SHORTNAME . 'date_format');
      $data[$id]['time'] = date($format, $log['time']);
      $data[$id]['output'] = '<pre>' . $data[$id]['output'] . '</pre>';
    }

    /**
     * Sorting.
     */
    function usort_reorder($a, $b) {
      $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'time';
      $order   = (!empty($_REQUEST['order']))   ? $_REQUEST['order']   : 'desc';
      $result  = strcmp($a[$orderby], $b[$orderby]);
      return ($order === 'asc') ? $result : -$result;
    }
    usort($data, 'usort_reorder');
    
    
    // Pagination.
    $current_page = $this->get_pagenum();
    
    // Total number of items, necessary for pagination.
    $total_items = count($data);
    
    // Get only items for current page.
    $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);
    
    // Done - add it to items.
    $this->items = $data;
    
    // Register pagination options.
    $this->set_pagination_args(array(
      'total_items' => $total_items,
      'per_page'    => $per_page,
      'total_pages' => ceil($total_items / $per_page),
    ));
  }
  
  /**
   * Override default table stylesheet classes.
   */
  public function get_table_classes() {
    return array('wp-list-table', 'widefat', 'fixed', 'logs', 'laguna-admin');
  }

  /**
   * Custom method: gets currently selected log filter.
   */
  public function laguna_current_filter() {
    $filter = '';
    $valid_filters = laguna_get_log_types();
    if (isset($_GET['filter']) && in_array($_GET['filter'], $valid_filters)) {
      $filter = $_GET['filter'];
    }

    return $filter;
  }

  /**
   * Add extra information to the header/footer of the list table.
   */
  public function extra_tablenav($which) {
    switch ($which) {
      case 'top':
        $view_log_page = laguna_options_page_path('view-log');
        laguna_get_view('admin.filter-logs', array(
          'delete_logs_url' => $view_log_page . '&delete=1',
          'log_filters'     => laguna_get_log_types(),
          'current_filter'  => $this->laguna_current_filter(),
          'filter_logs_url' => $view_log_page . '&filter=',
        ));
        break;

      case 'bottom':
        break;
    }
  }

}
