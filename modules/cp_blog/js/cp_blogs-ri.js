(function ($) {
  'use strict';
  Drupal.behaviors.cp_blogs = {
    attach: function(context, settings) {
      
    	setPictureMaxWidth();
    	
        $(window).resize(function () {
        	setPictureMaxWidth();
        });
                
    }
  };
}(jQuery));

function setPictureMaxWidth() {
	var blogW = jQuery('.cp_blog').width();
	
	if (jQuery('.cp_blog .picture img').width() > blogW / 2) {
		jQuery('.cp_blog .picture img').css({'width':blogW /2});
	}
	
	jQuery('.cp_blog_earlier .picture img').each(function(i, e) {
		if (jQuery(e).width() > blogW / 2) {
			jQuery(e).css({'width':blogW /2});
		}
	});
}