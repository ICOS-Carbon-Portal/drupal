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
