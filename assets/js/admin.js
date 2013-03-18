
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
  $('.boilerplate-uploader').click(function() {
    $('.boilerplate-active-field').removeClass('boilerplate-active-field');
    $(this).prev('input[type=text]').addClass('boilerplate-active-field');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
  });
  // Inserting image URL.
  window.send_to_editor = function(html) {
    imgurl = $('img',html).attr('src');
    $('.boilerplate-active-field').val(imgurl).removeClass('boilerplate-active-field');
    tb_remove();
  }
  
  /**
   * Fieldset tabs.
   */
  $('.boilerplate-tab-trigger').click(function(){
    var parentFieldset = $(this).parents('.boilerplate-fieldset-div');
    parentFieldset.find('.boilerplate-tab-trigger').removeClass('active');
    parentFieldset.find('.boilerplate-tab-content').hide();
    $(this).addClass('active');
    $('.' + $(this).attr('rel')).show();
  });
  $('.boilerplate-fieldset-div').each(function(){
    tabInCurrentFieldset = $(this).find('.boilerplate-tab-trigger');
    if (tabInCurrentFieldset.length) {
      tabInCurrentFieldset.eq(0).click();
    }
  });
  
});
