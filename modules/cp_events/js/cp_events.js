(function ($) {
  'use strict';
  Drupal.behaviors.cp_events = {
    attach: function(context, settings) {
      
    	setCorrectHeight();
    	
    	$(window).resize(function () {
    		setCorrectHeight();
    	});
    		
    }
  };
}(jQuery));

function setCorrectHeight() {
	jQuery('.full-event .text img').each( function() {	
		jQuery(this).parent().css({'min-height': jQuery(this).height()});		
	});
}