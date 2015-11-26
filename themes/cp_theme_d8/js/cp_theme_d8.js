(function ($) {
  'use strict';
  Drupal.behaviors.cp_theme_d8 = {
    attach: function(context, settings) {
      
    	$(function() {
    		
    		if ($(window).width() < 800) {
    		
    			$('#cp_theme_d8_menu .has_subnodes > a').attr('title', 'Close menu');
    			
	    		$('#cp_theme_d8_menu .is_topnode').once('cp_theme_d8').click(
	    				
	    			function(event) {
	    				
	    				$(this).toggleClass('tap');
	    			
	    			}
	    		);
		    	
    		}
    	});
    	
    }
  };
}(jQuery));



/**



**/