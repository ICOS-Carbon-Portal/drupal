(function ($) {
  'use strict';
  Drupal.behaviors.cp_media = {
    attach: function(context, settings) {
      
    	setLatestMovieMaxWidth();
    		
    }
  };
}(jQuery));


function setLatestMovieMaxWidth() {
	
	if (jQuery('#block-latestcpmediamovie video').width() > jQuery('#block-latestcpmediamovie').width()) {
		jQuery('#block-latestcpmediamovie video').css({'width':jQuery('#block-latestcpmediamovie').width()});
	}
	
}