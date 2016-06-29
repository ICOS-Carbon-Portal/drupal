(function ($) {
  'use strict';
  Drupal.behaviors.cp_media = {
    attach: function(context, settings) {
      
    	setLatestMovieMaxWidth();
		
		$(window).resize(function () {
			setLatestMovieMaxWidth();
		});
    		
    }
  };
}(jQuery));


function setLatestMovieMaxWidth() {
	
//	if (jQuery('#block-latestcpmediamovie video').width() > jQuery('#block-latestcpmediamovie').width()) {
//		jQuery('#block-latestcpmediamovie video').css({'width':jQuery('#block-latestcpmediamovie').width()});
//	}
	
	jQuery('#block-latestcpmediamovie video').css({'width':'450px'});
	jQuery('#block-latestcpmediamovie .cp_media').css({'width':'450px'});
	
}