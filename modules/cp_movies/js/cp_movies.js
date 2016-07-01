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
	
	jQuery('#block-latestcpmovie').css({'width':'450px'});
	jQuery('#block-latestcpmovie video').css({'width':'450px'});	
	
}