
/**
 * @file
 * Plugin admin JS.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */
jQuery.noConflict();
jQuery(document).ready(function($) {
  
  /**
   * Image uploader.
   */
  // Button trigger.
  $('.custom-debug-uploader').click(function() {
    $('.custom-debug-active-field').removeClass('custom-debug-active-field');
    $(this).prev('input[type=text]').addClass('custom-debug-active-field');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
  });
  // Inserting image URL.
  window.send_to_editor = function(html) {
    imgurl = $('img',html).attr('src');
    $('.custom-debug-active-field').val(imgurl).removeClass('custom-debug-active-field');
    tb_remove();
  }
  
  /**
   * Fieldset tabs.
   */
  $('.custom-debug-tab-trigger').click(function(){
    var parentFieldset = $(this).parents('.custom-debug-fieldset-div');
    parentFieldset.find('.custom-debug-tab-trigger').removeClass('active');
    parentFieldset.find('.custom-debug-tab-content').hide();
    $(this).addClass('active');
    $('.' + $(this).attr('rel')).show();
  });
  $('.custom-debug-fieldset-div').each(function(){
    tabInCurrentFieldset = $(this).find('.custom-debug-tab-trigger');
    if (tabInCurrentFieldset.length) {
      tabInCurrentFieldset.eq(0).click();
    }
  });
  
});
