(function ($) {
  'use strict';
  Drupal.behaviors.cp_movies = {
    attach: function(context, settings) {
      
    	setLatestMovieMaxWidth();
		
		$(window).resize(function () {
			setLatestMovieMaxWidth();
		});
    		
    }
  };
}(jQuery));


function setLatestMovieMaxWidth() {
	if (jQuery('#block-latestcpmovie').parent().width() > 900) {
		jQuery('#block-latestcpmovie').css({'width':jQuery('#block-latestcpmovie').parent().width()/2});
		jQuery('#block-latestcpmovie video').css({'width':'450px'});
		
	} else if(jQuery('#block-latestcpmovie').parent().width() > 450) {
		jQuery('#block-latestcpmovie').css({'width':'450px'});
		jQuery('#block-latestcpmovie video').css({'width':'450px'});
		
	} else {
		jQuery('#block-latestcpmovie').css({'width':jQuery('#block-latestcpmovie').parent().width()});
		jQuery('#block-latestcpmovie video').css({'width':jQuery('#block-latestcpmovie').parent().width()});
	}
	
	jQuery('#block-latestcpmovie .cp_movies').css({'background-color':'rgb(246, 246, 242)'});   
}