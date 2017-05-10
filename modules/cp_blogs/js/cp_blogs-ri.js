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

function setMinHeight() {
	if (jQuery('.cp_blog .text').height() < jQuery('.cp_blog .picture').height()) {
		var h = jQuery('.cp_blog .date').height() + jQuery('.cp_blog .title').height() + jQuery('.cp_blog .picture').height() + 20;
		jQuery('.cp_blog').css({'height':h});
	}
}

function shareTwitterBlog(blogId, blogTitle, siteHome) {
	var message = encodeURIComponent(blogTitle) + '%20-%20' + siteHome +  '/blog/' + blogId;
	var url = 'https://twitter.com/intent/tweet/?text=' + message;
	
	var windowFeatures = 'menubar=no, toolbar=no, location=no, resizable=yes, scrollbars=no, status=no, width=600, height=300';
	window.open(url, 'share_twitter_blog', windowFeatures);
}

function shareLinkedinBlog(blogId, siteHome) {
	var message = siteHome +  '/blog/' + blogId;
	var url = 'https://www.linkedin.com/uas/connect/user-signin?session_redirect=https%3A%2F%2Fwww%2Elinkedin%2Ecom%2Fcws%2Fshare%3Furl%3D' + message;
	
	var windowFeatures = 'menubar=no, toolbar=no, location=no, resizable=yes, scrollbars=no, status=no, width=600, height=300';
	window.open(url, 'share_linkedin_blog', windowFeatures);
}

function shareFacebookEvent(blogId, blogTitle, siteHome) {
	var message = 'u=' + siteHome +  '/blog/' + blogId + '&t=' + encodeURIComponent(blogTitle);
	var url = 'https://www.facebook.com/sharer/sharer.php?' + message;
	
	var windowFeatures = 'menubar=no, toolbar=no, location=no, resizable=yes, scrollbars=no, status=no, width=600, height=300';
	window.open(url, 'share_facebook_event', windowFeatures);
}

