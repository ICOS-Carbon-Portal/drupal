(function ($) {
 'use strict';
 Drupal.behaviors.cp_email = {
  attach: function(context, settings) {
	  
	if (settings.human_control_key) {
		jQuery('.cp-email-form').hide();
	
		jQuery('.block-cp-email .content').once('cp_email').prepend('<p class="human_control_element_label">'+ settings.human_control_label+'</p>');
		jQuery('.block-cp-email .content').append('<div id="human_control_element_' + settings.human_control_key + '" class="human_control_element"></div>');
	        
		loadHumanControl(settings.human_control_key);
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

var loadHumanControl = function(key) {
    grecaptcha.render('human_control_element_' + key, {
      'sitekey' : key,
      'callback' : verifyHumanControl,
    });
}