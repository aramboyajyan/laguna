<?php

/**
 * @file
 * Helper functions used for working with forms.
 *
 * These functions are used for admin pages and view rendering.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */

/**
 * Display fields.
 *
 * If $admin_page is enabled, value will be fetched with get_option().
 */
if (!function_exists('laguna_field')):
function laguna_field($field, $print = TRUE, $admin_page = FALSE) {
  // Wrap.
  $output  = '<div class="form-item ' . $field['class'] . '">';
  
  // Label.
  if (isset($field['label'])) {
    $output .= '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
  }
  else {
    $output .= '<label class="spacer"></label>';
  }
  
  // Field value - if it's admin field, fetch it with get_option()
  // "no-save" fields are not saved and can be used for one-time processing.
  if (!$field['no-save']) {
    $field['value'] = ($admin_page && $field['type'] != 'submit' && !$field['editor']) ? get_option(LAGUNA_SHORTNAME . $field['id']) : $field['value'];
  }

  // Field wrapper.
  $output .= '<div class="field">';
  
  // Input field.
  switch ($field['type']) {
    case 'text':
      $output .= '<input type="text" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . $field['value'] . '" />';
      break;
      
    case 'email':
      $output .= '<input type="email" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . $field['value'] . '" />';
      break;
      
    case 'color':
      $output .= '<input type="color" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . $field['value'] . '" />';
      break;
      
    case 'date':
      $output .= '<input type="date" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . $field['value'] . '" />';
      break;
      
    case 'number':
      $output .= '<input type="number" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . $field['value'] . '" />';
      break;
      
    case 'textarea':
      $output .= '<textarea id="' . $field['id'] . '" name="' . $field['id'] . '">' . $field['value'] . '</textarea>';
      break;
      
    case 'select':
      $output .= '<select id="' . $field['id'] . '" name="' . $field['id'] . '">';
      foreach ($field['options'] as $value => $title) {
        $selected = ($field['value'] == $value) ? ' selected="selected"' : '';
        $output .= '<option value="' . $value . '"' . $selected . '>' . $title . '</option>';
      }
      $output .= '</select>';
      break;

    case 'radios':
      $count = 0;
      $output .= '<div class="laguna-radios-wrapper">';
      foreach ($field['options'] as $value => $label) {
        $selected = ($field['value'] == $value) ? ' checked="checked"' : '';
        $output .= '<label>';
        $output .= '<input type="radio" value="' . $value . '"' . $selected . ' name="' . $field['id'] . '">';
        $output .= '<span>' . $label . '</span>';
        $output .= '</label>';
      }
      $output .= '</div>';
      $count++;
      break;

    case 'file':
      $output .= '<div class="file-preview" id="' . $field['id'] . '-preview">';
      if ($field['value']) {
        $output .= '<img src="' . $field['value'] . '" alt="' . $field['label'] . '" />';
      } else {
        $output .= __('No image uploaded.');
      }
      $output .= '</div>';
      $output .= '<input type="text" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . $field['value'] . '" />';
      $output .= '<a class="laguna-uploader button">' . __('Upload image') . '</a>';
      break;

    case 'checkbox':
      $output .= '<input type="checkbox" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . $field['value'] . '" />';
      break;

    case 'title':
      $output .= '<h4>' . $field['value'] . '</h4>';
      break;

    case 'submit':
      $output .= '<input class="button" type="submit" id="' . $field['id'] . '" value="' . $field['value'] . '" />';
      break;
  }

  // Help.
  if (isset($field['help'])) $output .= '<div class="help">' . $field['help'] . '</div>';

  // Close field wrapper.
  $output .= '</div>';

  // Close wrap.
  $output .= '</div>';

  return $print ? print $output : $output;
}
endif;

/**
 * Closing for custom fields.
 */
if (!function_exists('laguna_field_close')):
function laguna_field_close($print = TRUE) {
  return $print ? print '<div class="form-item-clear"></div>' : '<div class="form-item-clear"></div>';
}
endif;

/**
 * Generate admin page.
 */
if (!function_exists('laguna_generate_admin_page')):
function laguna_generate_admin_page($page) {
  // Wrap everything for styling.
  $output  = '<div id="laguna-admin-page"><div class="wrap">';
  // Page title and tabs.
  $output .= '<h2>' . $page['title'] . '</h2>';
  // Page description.
  if (isset($page['description'])) $output .= '<h4>' . $page['description'] . '</h4>';
  // Page help text.
  if (isset($page['content'])) $output .= '<p>' . $page['content'] . '</p>';
  // Form.
  if (isset($page['form'])) $output .= '<form action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '" method="post">';
  // Page fields.
  if (isset($page['fieldset'])) {
    foreach ($page['fieldset'] as $fieldset) {
      $output .= '<div class="laguna-fieldset-div metabox-holder">';
      $output .= '<div class="postbox">';
      $output .= '<h3><span>' . $fieldset['title'];
      if (count($fieldset['tabs'])) {
        $output .= '<div class="laguna-tabs">';
        foreach ($fieldset['tabs'] as $tab) {
          $output .= '<a class="laguna-tab-trigger" rel="' . $tab['trigger'] . '">' . $tab['title'] . '</a>';
        }
        $output .= '</div>';
      }
      $output .= '</span></h3>';
      $output .= '<div class="inside">';
      foreach ($fieldset['fields'] as $field) $output .= laguna_field($field, FALSE, TRUE);
      $output .= laguna_field_close(FALSE);
      $output .= '</div>';
      $output .= '</div>';
      $output .= '</div>';
    }
  }
  // Close form.
  if (isset($page['form'])) $output .= '</form>';
  // Close wrappers.
  $output .= '</div></div>';
  print $output;
}
endif;
