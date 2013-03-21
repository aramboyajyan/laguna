
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
  $('.luna-uploader').click(function() {
    $('.luna-active-field').removeClass('luna-active-field');
    $(this).prev('input[type=text]').addClass('luna-active-field');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
  });
  // Inserting image URL.
  window.send_to_editor = function(html) {
    imgurl = $('img',html).attr('src');
    $('.luna-active-field').val(imgurl).removeClass('luna-active-field');
    tb_remove();
  }
  
  /**
   * Fieldset tabs.
   */
  $('.luna-tab-trigger').click(function(){
    var parentFieldset = $(this).parents('.luna-fieldset-div');
    parentFieldset.find('.luna-tab-trigger').removeClass('active');
    parentFieldset.find('.luna-tab-content').hide();
    $(this).addClass('active');
    $('.' + $(this).attr('rel')).show();
  });
  $('.luna-fieldset-div').each(function(){
    tabInCurrentFieldset = $(this).find('.luna-tab-trigger');
    if (tabInCurrentFieldset.length) {
      tabInCurrentFieldset.eq(0).click();
    }
  });
  
});
