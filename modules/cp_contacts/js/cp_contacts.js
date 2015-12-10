(function ($) {
  'use strict';
  Drupal.behaviors.cp_contacts = {
    attach: function(context, settings) {
      
    	if ($(window).width() > 1299) {
			placeMaxSize();
			
		} else {
			placeMinSize();
		}
    	
    	
    	$(window).resize(function () {
        	if ($(window).width() > 1199) {
    			placeMaxSize();
    			
    		} else {
    			placeMinSize();
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