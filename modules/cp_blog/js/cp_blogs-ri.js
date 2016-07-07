(function ($) {
  'use strict';
  Drupal.behaviors.cp_blogs = {
    attach: function(context, settings) {
      
    	setMinHeight();
    	
        $(window).resize(function () {
        	setMinHeight();
        });
                
    }
  };
}(jQuery));

function setMinHeight() {
	if (jQuery('.cp_blog .text').height() < jQuery('.cp_blog .picture').height()) {
		var h = jQuery('.cp_blog .date').height() + jQuery('.cp_blog .heading').height() + jQuery('.cp_blog .picture').height() + 20;
		jQuery('.cp_blog').css({'height':h});
	}
}