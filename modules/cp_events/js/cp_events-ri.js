(function ($) {
  'use strict';
  Drupal.behaviors.cp_events = {
    attach: function(context, settings) {
      
    	setCorrectHeight();
    	setArchivedSameLine();
    	
    	$(window).resize(function () {
    		setCorrectHeight();
    		setArchivedSameLine();
    	});
    		
    }
  };
}(jQuery));

function setCorrectHeight() {
	jQuery('.full-event .text img').each( function() {	
		jQuery(this).parent().css({'min-height': jQuery(this).height()});
	});
}

function setArchivedSameLine() {
	var posV = 0;
	jQuery('.block-list-of-archived-cp-events').each(function(i, e) { 
		if (jQuery(e).offset().top > posV) { posV = jQuery(e).offset().top; } 
	});
	
	jQuery('.block-list-of-archived-cp-events').each(function(i, e) { 
		if (jQuery(e).offset().top < posV) {
			jQuery(e).css({'position':'relative', 'top':posV - jQuery(e).offset().top}); 
		}	
	});
}