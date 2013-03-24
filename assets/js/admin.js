
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
  $('.laguna-uploader').click(function() {
    $('.laguna-active-field').removeClass('laguna-active-field');
    $(this).prev('input[type=text]').addClass('laguna-active-field');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
  });
  // Inserting image URL.
  window.send_to_editor = function(html) {
    imgurl = $('img',html).attr('src');
    $('.laguna-active-field').val(imgurl).removeClass('laguna-active-field');
    tb_remove();
  }
  
  /**
   * Fieldset tabs.
   */
  $('.laguna-tab-trigger').click(function(){
    var parentFieldset = $(this).parents('.laguna-fieldset-div');
    parentFieldset.find('.laguna-tab-trigger').removeClass('active');
    parentFieldset.find('.laguna-tab-content').hide();
    $(this).addClass('active');
    $('.' + $(this).attr('rel')).show();
  });
  $('.laguna-fieldset-div').each(function(){
    tabInCurrentFieldset = $(this).find('.laguna-tab-trigger');
    if (tabInCurrentFieldset.length) {
      tabInCurrentFieldset.eq(0).click();
    }
  });
  
  /**
   * Filter logs button.
   */
  var lagunaFilterLogTrigger = $('#laguna-filter-trigger'),
      lagunaFilterLogSelect  = $('#laguna-log-filters');
  lagunaFilterLogTrigger.click(function() {
    var selectedFilter = lagunaFilterLogSelect.find('option:selected').val();
    window.location.href = lagunaFilterLogTrigger.attr('data-href') + selectedFilter;
    return false;
  });

});
