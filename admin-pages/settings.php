<?php

/**
 * @file
 * General options page.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */
custom_debug_admin_page_save_handle();
$page = array(
  'title' => __('Custom debugging options'),
  'form' => TRUE,
  'fieldset' => array(
    
    /**
     * General admin settings.
     */
    array(
      'title' => __('Debugging information on front end'),
      'fields' => array(
        // Tab one content.
        array(
          'id' => 'rows_to_display',
          'type' => 'select',
          'label' => __('Rows to display'),
          'help' => __('Select how many rows should be displayed in the debugging bar displayed from the admin bar.'),
          'options' => array(5 => 5, 10 => 10, 15 => 15, 20 => 20, 30 => 30, 50 => 50),
          'value' => get_option(CUSTOM_DEBUG_SHORTNAME . 'rows_to_display'),
        ),
        array(
          'id' => 'submit_email_general',
          'class' => 'last',
          'type' => 'submit',
          'value' => __('Save settings'),
        ),
      ),
    ),
    // General admin settings end.
    
  ),
);
custom_debug_generate_admin_page($page);
