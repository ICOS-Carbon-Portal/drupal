(function ($) {
  'use strict';
  Drupal.behaviors.cp_blogs = {
    attach: function(context, settings) {
      
        setCorrectWidth();
        
        $(window).resize(function () {
        	setCorrectWidth();
        });
                
    }
  };
}(jQuery));

function setCorrectWidth() {
	var contw = jQuery('.region-content').width();
	jQuery('.cp_blog_teased .picture img').css({'width': contw / 2});
	jQuery('.cp_blog_teased .teaser').css({'width': contw / 2});
}