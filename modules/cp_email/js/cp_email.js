(function ($) {
 'use strict';
 Drupal.behaviors.cp_email = {
  attach: function(context, settings) {
	  
	if (settings.human_control_key) {
		jQuery('.cp-email-form').hide();
	}
	
  }
 };
}(jQuery));

var verifyHumanControl = function(response) {
	if (response) {    
		jQuery('.block-cp-email .content .human_control_element').remove();
		jQuery('.block-cp-email .content .human_control_element_label').remove();
		jQuery('.cp-email-form').show();
    }
}       