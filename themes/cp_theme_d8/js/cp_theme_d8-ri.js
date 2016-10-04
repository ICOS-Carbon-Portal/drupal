(function ($) {
  'use strict';
  Drupal.behaviors.cp_theme_d8 = {
    attach: function(context, settings) {
    	
	    checkCpUserConsent();   
	    handleIngressAndTitle(); 
	    fixHome();
	    fixMaterials();
	    
    	$(function() {
    		
    		if ($(window).width() < 800) {
    			fixSmallsizeMenu();		
    		}
    		
    		if ($(window).width() > 1300) {
    			fixHomeMax();
    			
    		} else {
    			fixHomeMin();
    		}
    		
    		$(window).resize(function () {
    			if ($(window).width() < 800) {
        			fixSmallsizeMenu();		
        		}
    			
    			if ($(window).width() > 1300) {
        			fixHomeMax();
        			
        		} else {
        			fixHomeMin();
        		}
    			
    		});
    		
    	});
    }
  };
}(jQuery));

function fixSmallsizeMenu() {
	jQuery('#cp_theme_d8_menu .is_topnode').once('cp_theme_d8').each(
			
			function(index, value) {
				jQuery(value).prepend('<img src="/themes/cp_theme_d8/images/arrow-up-down.svg" class="open_menu" title="Open/Close" />');
				
			}
	);
	
	jQuery('#cp_theme_d8_menu .is_topnode .open_menu').once('cp_theme_d8').click(	    				
		function(event) {
			jQuery(this).parent().toggleClass('open');
		}
	);
}

function getCookieByName(cn) {
    var n = cn + "=";
    var ca = document.cookie.split(";");
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == " ") c = c.substring(1);
        if (c.indexOf(n) == 0) { return c.substring(n.length, c.length); }
    }
    return "";
}

function checkCpUserConsent() {
	if (checkedCpUserConsent) { return; }
	
    var hasConsent = getCookieByName("cpuserconsent");
    
    if (hasConsent === "") {
        var info = document.createElement("p");
        info.className = "info";
        var infot = document.createTextNode("We use cookies to give you a better experience on our website. If you continue on our site you agree that we store cookies in your web browser.");
        info.appendChild(infot);
        var agree = document.createElement("p");
        agree.className = "agree";
        var agreet = document.createTextNode("I understand!");
        var check = document.createElement("input");
        check.type = "checkbox";
        check.name = "iagree";
        check.addEventListener("click", function() {
			var d = new Date();
			d.setTime(d.getTime() + (10*12*30*24*60*60*1000));
			var expires = "expires=" + d.toUTCString();
			document.cookie = "cpuserconsent=true;" + expires;
			var div = document.getElementById("cpuserconsent");
			div.parentNode.removeChild(div);	
		});
        agree.appendChild(agreet);
        agree.appendChild(check);
        var div = document.createElement("div");
	    div.id = "cpuserconsent";
        div.appendChild(info);
        div.appendChild(agree);
		var page = document.getElementById("page");
		page.insertBefore(div, page.firstChild);    
    }
    checkedCpUserConsent = true;
}

checkedCpUserConsent = false;

/**
 * This method assume the field cp_page_ingress:  
 * Text (plain, long) Machine name: field_cp_page_ingress 
 */
function handleIngressAndTitle() {
	jQuery('.page-title').css({'padding':'0 0 1rem 0', 'border-bottom':'0.1rem dashed #c7c8ca'});
	jQuery('.field--name-field-cp-page-ingress .field__label').hide();
	jQuery('.field--name-field-cp-page-ingress .field__item').css({'padding':'0 0 3rem 0', 'font-size':'1.4rem', 'font-weight':'bold', 'text-transform':'uppercase', 'text-align':'center'});
}

/** 
* Home page
* Tweets, Teasing blog and Station map on home page.
* The following methods assume an embedded Tweet, a Teased CP Blog and a iframe in a respectively block on main content.
* The methods also depends of a CP Event's at right sidebar.
*/
function fixHome() {
	jQuery('.main-content #block-tweets').css({'background-color':'#f6f6f2', 'height':440});
    jQuery('.main-content .block-view-teased-cp-blog').css({'background-color':'#f6f6f2', 'height':440});
}

function fixHomeMax() {
	var w = jQuery('.main-content').width() / 2;
	var eventOff = jQuery('#block-listofteasedcpevents').offset();
	var tweetOff = jQuery('#block-tweets').offset();
	var tweetH = jQuery('#block-tweets').height();
	if (! (eventOff && tweetOff)) { return; }
	
	if (tweetOff.top < eventOff.top) {
		jQuery('.main-content #block-tweets').css({'position':'relative', 'top':eventOff.top - tweetOff.top});
	}
	
	var tweetPos = jQuery('.main-content #block-tweets').position();
	if (! tweetPos) { return; }
	
    jQuery('.main-content #block-tweets').css({'width':w - 20});
    jQuery('.main-content .block-view-teased-cp-blog').css({'width':w - 20, 'position':'absolute', 'top':tweetPos.top, 'left':w});
    jQuery('.main-content .block-view-teased-cp-blog .teaser').css({'width':'100%'});  
    
    jQuery('.main-content .block-view-teased-cp-blog').css({'margin-top':0});
    jQuery('.main-content #stationmap').css({'margin-top':220});
    
}

function fixHomeMin() {
	jQuery('.main-content #block-tweets').css({'width': '100%'});
	jQuery('.main-content .block-view-teased-cp-blog').css({'display':'block', 'width':'100%', 'position':'relative', 'top':'0', 'left':'0'});
	
    var picW = jQuery('.main-content #block-viewteasedcpblog .picture img').width();
    jQuery('.main-content .block-view-teased-cp-blog .teaser').css({'width':picW});
    
    jQuery('.main-content .block-view-teased-cp-blog').css({'margin-top':220});
    jQuery('.main-content #stationmap').css({'margin-top':220});
}

/** 
* Materials page.
*/
function fixMaterials() {
	jQuery('.layout-main-wrapper').css({'min-height':0});
	jQuery('.block-cp-cmis-documents').css({'border-bottom':0});
}
