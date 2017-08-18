(function ($) {
  'use strict';
  Drupal.behaviors.cp_events = {
    attach: function(context, settings) {
    	    		
    }
  };
}(jQuery));

fixEventsPicture();

if (jQuery(window).width() > 1100) {
	fixEventsPageMax();
	
} else {
	fixEventsPageMin();
	
}

jQuery(window).resize(function () {
	if (jQuery(window).width() > 1100) {
		fixEventsPageMax();
	} else {
		fixEventsPageMin();
	}
});

jQuery('.event_body_handler').click(function() {
	loadEventBody(jQuery(this).attr('id'));
});

jQuery('.tease_event .picture img').each(function() {
	if (jQuery(this).height() > 150) {
		jQuery(this).height(150); 
	}
});

jQuery('.full_event .text img').each(function() {
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
	jQuery('.full_event .text .picture').each(function() {
		var w = jQuery(this).children('img').width();
		jQuery(this).width(w + 20);
	});
});

function setCorrectHeight() {
	jQuery('.full_event .text img').each( function() {	
		jQuery(this).parent().css({'min-height': jQuery(this).height()});
	});
}

function setArchivedSameLine() {
	var posV = 0;
	jQuery('.block-list-of-archived-cp-events').each(function(i, e) { 
		if (jQuery(e).offset().top > posV) { posV = jQuery(e).offset().top; } 
	});
	
	jQuery('.block-list-of-archived-cp-events').each(function(i, e) { 
		if (jQuery(e).offset().top < posV) {
			jQuery(e).css({'position':'relative', 'top':posV - jQuery(e).offset().top}); 
		}	
	});
}

function fixEventsPicture() {
	if (jQuery('#cp_events_page .latest_news .picture').length) {
		if (jQuery('#cp_events_page .latest_news .picture').height() > 400) {
			jQuery('#cp_events_page .latest_news .picture').height(400);
			jQuery('#cp_events_page .latest_news .picture').width('auto');
		
		} else if(jQuery('#cp_events_page .latest_news .picture').width() > 500) {
			jQuery('#cp_events_page .latest_news .picture').width(500);
		}
	}		
}

function fixEventsPageMin() {
	jQuery('#cp_events_page .news_section .news_list').css({'position':'relative', 'left':0, 'bottom':0});
	jQuery('#cp_events_page .news_section .news_list').css({'margin-left':0, 'width':'99%', 'margin-top':20});
	
	jQuery('#cp_events_page .events_section').css({'margin-top':20});
	jQuery('#cp_events_page .events_section .events_list').css({'width':'100%'});
	jQuery('#cp_events_page .events_section #event_container').css({'position':'relative', 'left':0, 'bottom':0, 'width':'100%', 'height':'auto'});
	
	jQuery('#cp_events_page .news_section').css({'height':'auto'});
	jQuery('#cp_events_page .events_section').css({'height':'auto'});
}

function fixEventsPageMax() {
	var leftMargNS = 40;
	var rightMargNS = 10;
	var widthLN = jQuery('#cp_events_page .news_section .latest_news').width();
	var widthNL = jQuery('#content').width() - widthLN - leftMargNS - rightMargNS;
	var heightLN = jQuery('#cp_events_page .news_section .latest_news').height();
	
	var heightNL = jQuery('#cp_events_page .news_section .news_list').height();
	
	jQuery('#cp_events_page .news_section .news_list').css({'margin-left':leftMargNS, 'width':widthNL});
	
	if (heightLN > heightNL) {
		jQuery('#cp_events_page .news_section').css({'height':heightLN + 100});
	} else {
		jQuery('#cp_events_page .news_section').css({'height':heightNL + 100});
	}
	
	jQuery('#cp_events_page .news_section .news_list').css({'position':'relative', 'left':widthLN, 'bottom':heightLN + 22, 'width':widthNL, 'margin-top':0});
	
	jQuery('#cp_events_page .events_section').css({'margin-top':0});
	jQuery('#cp_events_page .events_section .events_list').css({'width':'33.33%'});
	
	var heightEL = jQuery('#cp_events_page .events_section .events_list').height();
	jQuery('#cp_events_page .events_section #event_container').css({'position':'relative', 'left':'33.33%', 'bottom':heightEL, 'width':'66.66%'});
	
	fixEventsPageEventsSection();
}

function loadEventBody(eventId) {
	
	if (! jQuery('#cp_events_page #event_' + eventId + ' .event_active').is(":visible")) {
	
		jQuery('.events_list .items .item').each(function() {
			jQuery(this).children('a').removeClass('event_selected');
			jQuery(this).children('.event_active').css({'display':'none'});
		});
		
		jQuery('#cp_events_page .events_section #event_container .event_body .edit-this').html('');
		jQuery('#cp_events_page .events_section #event_container .event_body .title').html('');
		jQuery('#cp_events_page .events_section #event_container .event_body .date').html('');
		jQuery('#cp_events_page .events_section #event_container .event_body .text').html('');
		jQuery('#cp_events_page .events_section #event_container .event_body .link').html('');
		jQuery('#cp_events_page .events_section #event_container .event_body .share').html('');
		
		var title = jQuery('#cp_events_page #event_' + eventId + ' .title').text();
		var date = jQuery('#cp_events_page #event_' + eventId + ' .date').text();
		var text = jQuery('#cp_events_page #event_' + eventId + ' .text').text();
		var linkUri = jQuery('#cp_events_page #event_' + eventId + ' .link_uri').text();
		var linkTitle = jQuery('#cp_events_page #event_' + eventId + ' .link_title').text();	
		var siteHome = jQuery('#cp_events_page .events_section #event_container .event_body .site_home').text();
		
		jQuery('#cp_events_page .events_section #event_container .event_body .edit-this').append('<a href="/node/' + eventId + '/edit?destination=node/' + eventId + '">Edit this</a>');
		jQuery('#cp_events_page .events_section #event_container .event_body .title').html( title );
		jQuery('#cp_events_page .events_section #event_container .event_body .date').html( date );
		jQuery('#cp_events_page .events_section #event_container .event_body .text').html( text );
		
		if (linkUri) {
			jQuery('#cp_events_page .events_section #event_container .event_body .link').append( '<a href="' + linkUri + '" class="event_body_button">' + linkTitle + '</a>' );
		}
		
		jQuery('#cp_events_page .events_section #event_container .event_body .share').append( 
				'<a href="javascript:void(0)" onclick="shareTwitterEvent(' + eventId + ', \'' + title + '\', \'' + siteHome + '\')" class="event_share_button twitter" event_id="' + eventId + '" event_title="' + title + '" site_home="' + siteHome + '"></a>' 
			);
		
		jQuery('#cp_events_page #event_' + eventId + ' a').addClass('event_selected');
		jQuery('#cp_events_page #event_' + eventId + ' .event_active').css({'display':'inline-block'});
		
		var posA = jQuery('#cp_events_page #event_' + eventId + ' a').position();
		var posE = jQuery('#cp_events_page #event_' + eventId + ' .event_active').position();
		var heightA = jQuery('#cp_events_page #event_' + eventId + ' a').height();
		var bottomA = (posE.top - (posA.top + (heightA / 2))) - 10;
		jQuery('#cp_events_page #event_' + eventId + ' .event_active').css({'margin-bottom':bottomA});
		
		if (jQuery(window).width() > 1099) {
			fixEventsPageEventsSection() ;
		}
	}
}

function fixEventsPageEventsSection() {
	var heightEL = jQuery('#cp_events_page .events_section .events_list').height();
	var heightEB = jQuery('#cp_events_page .events_section #event_container .event_body').height();
	var whiteSpace = 0;
	
	if (heightEB > heightEL) {
		jQuery('#cp_events_page .events_section').css({'height':heightEB + whiteSpace});
		jQuery('#cp_events_page .events_section #event_container').css({'height':heightEB + whiteSpace});
		
	} else {
		jQuery('#cp_events_page .events_section').css({'height':heightEL + whiteSpace});
		jQuery('#cp_events_page .events_section #event_container').css({'height':heightEL + whiteSpace});
	}	
}

function shareTwitterEvent(eventId, eventTitle, siteHome) {
	var message = encodeURIComponent(eventTitle) + '%20-%20' + siteHome +  '/event/' + eventId;
	var url = 'https://twitter.com/intent/tweet/?text=' + message;
	
	var windowFeatures = 'menubar=no, toolbar=no, location=no, resizable=yes, scrollbars=no, status=no, width=600, height=300';
	window.open(url, 'share_twitter_event', windowFeatures);
}

function shareLinkedinEvent(eventId, siteHome) {
	var message = siteHome +  '/event/' + eventId;
	var url = 'https://www.linkedin.com/uas/connect/user-signin?session_redirect=https%3A%2F%2Fwww%2Elinkedin%2Ecom%2Fcws%2Fshare%3Furl%3D' + message;
	
	var windowFeatures = 'menubar=no, toolbar=no, location=no, resizable=yes, scrollbars=no, status=no, width=600, height=300';
	window.open(url, 'share_linkedin_event', windowFeatures);
}

function shareFacebookEvent(eventId, eventTitle, siteHome) {
	var message = 'u=' + siteHome +  '/event/' + eventId + '&t=' + encodeURIComponent(eventTitle);
	var url = 'https://www.facebook.com/sharer/sharer.php?' + message;
	
	var windowFeatures = 'menubar=no, toolbar=no, location=no, resizable=yes, scrollbars=no, status=no, width=600, height=300';
	window.open(url, 'share_facebook_event', windowFeatures);
}
