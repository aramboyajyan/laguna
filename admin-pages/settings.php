<?php

/**
 * @file
 * General options page.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */
luna_admin_page_save_handle();
$page = array(
  'title' => __('Custom debugging options'),
  'form' => TRUE,
  'fieldset' => array(
    
    /**
     * Logging settings.
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
          'value' => get_option(LUNA_SHORTNAME . 'rows_to_display'),
        ),
        array(
          'id' => 'debug_front_submit',
          'class' => 'last',
          'type' => 'submit',
          'value' => __('Save settings'),
        ),
      ),
    ),
    // Logging settings end.
    
    /**
     * Login errors.
     */
    array(
      'title' => __('Login errors'),
      'fields' => array(
        // Tab one content.
        array(
          'id' => 'login_errors_enabled',
          'type' => 'checkbox',
          'label' => __('Override login errors'),
          'help' => __('By default, WordPress tells the user which part of login information is incorrect. This is a potential security issue and it is better to replace that text with a generic error message. By enabling this feature, the message can be customized in the textarea below.'),
          'value' => get_option(LUNA_SHORTNAME . 'login_errors_enabled'),
        ),
        array(
          'id' => 'login_error_text',
          'type' => 'textarea',
          'label' => __('Error text'),
          'help' => __('This text will be displayed instead of default WordPress error messages on unsuccessful user login.'),
          'value' => get_option(LUNA_SHORTNAME . 'login_error_text'),
        ),
        array(
          'id' => 'login_errors_submit',
          'class' => 'last',
          'type' => 'submit',
          'value' => __('Save settings'),
        ),
      ),
    ),
    // Login errors end.

  ),
);
luna_generate_admin_page($page);
