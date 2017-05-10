(function ($) {
  'use strict';
  Drupal.behaviors.cp_blogs = {
    attach: function(context, settings) {
      
        $(window).resize(function () {
        	
        });
                
    }
  };
}(jQuery));

jQuery('.cp_blog .text img').each(function() {
	if (jQuery(this).has('data-caption') && jQuery(this).attr('data-caption')) {
		var w = jQuery(this).width();
		if (w > 400) { w = 400; }
		jQuery(this).wrap('<div class="picture" style="float:right; padding:0;"></div>');
		jQuery(this).parent().append('<p style="text-align:center; padding-left:2rem;">' + jQuery(this).attr('data-caption') + '</p>');
		jQuery(this).width(w);
		jQuery(this).parent().width(w + 20);
	}
});

jQuery(window).resize(function () {
	jQuery('.cp_blog .text .picture').each(function() {
		var w = jQuery(this).children('img').width();
		jQuery(this).width(w + 20);
	});
});