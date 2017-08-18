(function ($) {
  'use strict';
  Drupal.behaviors.cp_contacts = {
    attach: function(context, settings) {
      
    }
  };
}(jQuery));

if (jQuery(window).width() > 1199) {
	placeMaxSize();
	fixElementsMaxHeight()
	
} else {
	placeMinSize();
	fixElementsMinHeight();
}


jQuery(window).resize(function () {
	if (jQuery(window).width() > 1199) {
		placeMaxSize();
		fixElementsMaxHeight()
		
	} else {
		placeMinSize();
		fixElementsMinHeight();
	}
});	

function placeMaxSize() {
	jQuery('#cp_contacts .contact').css({'float': 'left', 'width': '33.3333%'});
}

function placeMinSize() {
	jQuery('#cp_contacts .contact').css({'float': 'none', 'width': 'auto'});
}

function fixElementsMaxHeight() {
	var max = 0;
	var margin = 40;
	
	if (jQuery('.contact > div').hasClass('data_to_right')) { margin = 0; }
	
	jQuery('.contact').each(function(i, e) { 
		if (jQuery(e).outerHeight() > max) {
			max = jQuery(e).outerHeight() + margin;
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