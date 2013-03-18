<?php

/**
 * @file
 * General options page.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */
boilerplate_admin_page_save_handle();
$page = array(
  'title' => __('Sample options page'),
  'description' => __('This is sample page description.'),
  'content' => __('This is sample page content.'),
  'form' => TRUE,
  'fieldset' => array(
    
    /**
     * General admin settings.
     */
    array(
      'title' => __('Plugin settings'),
      'tabs' => array(
        array(
          'title' => __('Tab one'),
          'trigger' => 'tab-one',
        ),
        array(
          'title' => __('Tab two'),
          'trigger' => 'tab-two',
        ),
      ),
      'fields' => array(
        // Tab one content.
        array(
          'id' => 'textfield',
          'type' => 'text',
          'label' => __('Text field'),
          'help' => __('Text field help text.'),
          'class' => 'boilerplate-tab-content tab-one',
          'value' => get_option(BOILERPLATE_SHORTNAME . 'textfield'),
        ),
        array(
          'id' => 'select',
          'type' => 'select',
          'label' => __('Select'),
          'help' => __('Select help text.'),
          'class' => 'boilerplate-tab-content tab-one',
          'value' => get_option(BOILERPLATE_SHORTNAME . 'select'),
          'options' => array(
            1 => 'Option 1',
            2 => 'Option 2',
            3 => 'Option 3',
            4 => 'Option 4',
            5 => 'Option 5',
          ),
        ),
        array(
          'id' => 'radios',
          'type' => 'radios',
          'label' => __('Radios'),
          'help' => __('Radios help text.'),
          'class' => 'boilerplate-tab-content tab-one',
          'value' => get_option(BOILERPLATE_SHORTNAME . 'radios'),
          'options' => array(
            1 => __('Option 1'),
            2 => __('Option 2'),
            3 => __('Option 3'),
            4 => __('Option 4'),
            5 => __('Option 5'),
          ),
        ),
        array(
          'id' => 'checkbox',
          'type' => 'checkbox',
          'label' => __('Checkbox'),
          'help' => __('Checkbox help text.'),
          'class' => 'boilerplate-tab-content tab-one',
          'value' => get_option(BOILERPLATE_SHORTNAME . 'checkbox'),
        ),
        array(
          'id' => 'file',
          'type' => 'file',
          'label' => __('File field'),
          'help' => __('File help text.'),
          'class' => 'boilerplate-tab-content tab-one',
          'value' => get_option(BOILERPLATE_SHORTNAME . 'file'),
        ),
        array(
          'id' => 'textarea',
          'type' => 'textarea',
          'label' => __('Text area'),
          'help' => __('Textarea help text.'),
          'class' => 'boilerplate-tab-content tab-one',
          'value' => get_option(BOILERPLATE_SHORTNAME . 'textarea'),
        ),
        // Tab two content.
        array(
          'id' => 'textfield_tab2',
          'type' => 'text',
          'label' => __('Text field'),
          'help' => __('Text field help text.'),
          'class' => 'boilerplate-tab-content tab-two',
          'value' => get_option(BOILERPLATE_SHORTNAME . 'textfield_tab2'),
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
boilerplate_generate_admin_page($page);
