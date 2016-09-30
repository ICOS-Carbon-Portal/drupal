(function ($) {
  'use strict';
  Drupal.behaviors.cp_events = {
    attach: function(context, settings) {
      
    	/**
    	setCorrectHeight();
    	setArchivedSameLine();
    	
    	$(window).resize(function () {
    		setCorrectHeight();
    		setArchivedSameLine();
    	});
    	**/
    	
    	if ($(window).width() > 1100) {
    		fixEventsPageMax();
		} else {
			fixEventsPageMin();
		}
    	
    	$(window).resize(function () {
			if ($(window).width() > 1100) {
				fixEventsPageMax();
    		} else {
    			fixEventsPageMin();
    		}
		});
    	
    	
    	$('.event_body_handler').click(function(){
    		loadEventBody($(this).attr('id'));
    	});
    		
    }
  };
}(jQuery));

function setCorrectHeight() {
	jQuery('.full-event .text img').each( function() {	
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


function fixEventsPageMin() {
	jQuery('#cp_events_page .news_section .news_list').css({'position':'relative', 'left':0, 'bottom':0});
	jQuery('#cp_events_page .news_section .news_list').css({'margin-left':0, 'width':'99%', 'margin-top':20});
	
	jQuery('#cp_events_page .events_section').css({'margin-top':20});
	jQuery('#cp_events_page .events_section .events_list').css({'width':'100%'});
	jQuery('#cp_events_page .events_section #event_container').css({'position':'relative', 'left':0, 'bottom':0, 'width':'100%'});
	
	if (jQuery('#cp_events_page .news_section .news_list').hasClass('max')) {
		jQuery('#cp_events_page .news_section .news_list').removeClass('max');
		
	}
}

function fixEventsPageMax() {
	var leftMargNS = 40;
	var rightMargNS = 10;
	var widthLN = jQuery('#cp_events_page .news_section .latest_news').width();
	var widthNL = jQuery('#content').width() - widthLN - leftMargNS - rightMargNS;
	var heightLN = jQuery('#cp_events_page .news_section .latest_news').height();
	var heightEL = jQuery('#cp_events_page .events_section .events_list').height();
	
	if (jQuery('#cp_events_page .news_section .news_list').hasClass('max')) {
		jQuery('#cp_events_page .news_section .news_list').css({'margin-left':leftMargNS, 'width':widthNL});
		
	} else {
		jQuery('#cp_events_page .news_section .news_list').css({'position':'relative', 'left':widthLN, 'bottom':heightLN, 'width':widthNL, 'margin-top':0});
		jQuery('#cp_events_page .news_section .news_list').addClass('max');
		
	}
	
	jQuery('#cp_events_page .events_section').css({'margin-top':0});
	jQuery('#cp_events_page .events_section .events_list').css({'width':'33.33%'});
	jQuery('#cp_events_page .events_section #event_container').css({'position':'relative', 'left':'33.33%', 'bottom':heightEL, 'width':'66.66%'});
	
	fixEventsPageEventsSection();
}

function loadEventBody(eventId) {
	
	if (! jQuery('#cp_events_page #event_' + eventId + ' .event_active').is(":visible")) {
	
		jQuery('.events_list .items .item').each(function() {
			jQuery(this).children('a').removeClass('event_selected');
			jQuery(this).children('.event_active').css({'display':'none'});
		});
		
		jQuery('#cp_events_page .events_section #event_container .event_body .title').html('');
		jQuery('#cp_events_page .events_section #event_container .event_body .date').html('');
		jQuery('#cp_events_page .events_section #event_container .event_body .text').html('');
		jQuery('#cp_events_page .events_section #event_container .event_body .link').html('');
		
		var title = jQuery.parseHTML(jQuery('#cp_events_page #event_' + eventId + ' .title').text());
		var date = jQuery.parseHTML(jQuery('#cp_events_page #event_' + eventId + ' .date').text());
		var text = jQuery.parseHTML(jQuery('#cp_events_page #event_' + eventId + ' .text').text());
		var linkUri = jQuery('#cp_events_page #event_' + eventId + ' .link_uri').html();
		var linkTitle = jQuery('#cp_events_page #event_' + eventId + ' .link_title').html();
		
		
		jQuery('#cp_events_page .events_section #event_container .event_body .title').html( title );
		jQuery('#cp_events_page .events_section #event_container .event_body .date').html( date );
		jQuery('#cp_events_page .events_section #event_container .event_body .text').html( text );
		
		if (linkUri) {
			jQuery('#cp_events_page .events_section #event_container .event_body .link').append( '<a href="' + linkUri + '" class="event_body_button">' + linkTitle + '</a>' );
		}
		
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


