(function ($) {
  'use strict';
  Drupal.behaviors.cp_contacts = {
    attach: function(context, settings) {
      
    	if ($(window).width() > 1199) {
			placeMaxSize();
			fixElementsMaxHeight()
			
		} else {
			placeMinSize();
			fixElementsMinHeight();
		}
    	
    	
    	$(window).resize(function () {
        	if ($(window).width() > 1199) {
    			placeMaxSize();
    			fixElementsMaxHeight()
    			
    		} else {
    			placeMinSize();
    			fixElementsMinHeight();
    		}
    	});	
    }
  };
}(jQuery));


function placeMaxSize() {
	jQuery('#cp_contacts .contact').css({'float': 'left', 'width': '33.3333%'});
}

function placeMinSize() {
	jQuery('#cp_contacts .contact').css({'float': 'none', 'width': 'auto'});
}

function fixElementsMaxHeight() {
	var max = 0;
	
	jQuery('.contact').each(function(i, e) { 
		if (jQuery(e).outerHeight() > max) {
			max = jQuery(e).outerHeight();
		};
	});
	
	jQuery('.contact').each(function(i, e) { 
		jQuery(e).css({'height':max});
	});
}

function fixElementsMinHeight() {
	jQuery('.contact').each(function(i, e) { 
		jQuery(e).css({'height':'auto'});	
	});
}