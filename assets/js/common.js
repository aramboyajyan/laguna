
/**
 * @file
 * Plugin front-end JS.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */
jQuery.noConflict();
jQuery(document).ready(function($) {

  var lagunaLogToggle = $('#wp-admin-bar-laguna'),
      lagunaLogWrap   = $('#laguna-logs-wrap');
  lagunaLogToggle.click(function() {
    lagunaLogWrap.toggle();
    lagunaLogToggle.toggleClass('active');
  });

});
