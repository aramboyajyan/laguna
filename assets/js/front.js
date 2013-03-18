
/**
 * @file
 * Plugin front-end JS.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */
jQuery.noConflict();
jQuery(document).ready(function($) {

  var customDebugToggle = $('#wp-admin-bar-custom-debug'),
      customDebugWrap   = $('#custom-debug-wrap');
  customDebugToggle.click(function() {
    customDebugWrap.toggle();
  }).toggleClass('active');

});
