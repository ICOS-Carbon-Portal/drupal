(function ($) {
  'use strict';
  Drupal.behaviors.cp_theme_d8 = {
    attach: function(context, settings) {
    	
	    checkCpUserConsent();   
	    handleIngressAndTitle(); 
	    fixHome();
	    
    	$(function() {
    		
    		if ($(window).width() < 800) {
    			fixSmallsizeMenu();		
    		}
    		
    		if ($(window).width() > 1260) {
    			fixHomeMax();
    			
    		} else {
    			fixHomeMin();
    		}
    		
    		$(window).resize(function () {
    			if ($(window).width() < 800) {
        			fixSmallsizeMenu();		
        		}
    			
    			if ($(window).width() > 1260) {
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
* The following methods assume an embedded Tweet, a Teased CP Blog and a iframe (Google Map) in a respectively block on main content.
* The methods also depends of a CP Event's at right sidebar.
*/
function fixHome() {
	jQuery('#block-tweets').css({'background-color':'#f6f6f2', 'width':480, 'height':440});
	jQuery('#block-tweets .content').css({'height':440, 'overflow-y':'scroll'});
	jQuery('#block-viewteasedcpblog').css({'background-color':'#f6f6f2', 'height':440, 'border-left':'20px solid #f6f6f2', 'border-right':'20px solid #f6f6f2'});
	jQuery('#block-viewteasedcpblog .teaser').css({'width':'100%'});
	jQuery('#block-viewteasedcpblog .content h2').css({'padding-top':20});
	
	jQuery('#block-stationmap').css({'border-top':'5px solid #f6f6f2', 'background-color':'#f6f6f2'});
    jQuery('#block-stationmap h2').css({'margin-left':'2rem'});
    jQuery('#block-stationmap > .content').css({'border-left':'20px solid #f6f6f2', 'border-right':'20px solid #f6f6f2'});
    jQuery('#block-stationmap .to_bigger_map').css({'background-color':'#f6f6f2'});
    jQuery('#block-stationmap .content .text-formatted').css({'height':0});
}

function fixHomeMax() {
	jQuery('#content').css({'min-height':'auto'});
	
	jQuery('#block-listofteasedcpnews').css({'height':780});
	jQuery('#block-listofteasedcpevents').css({'height':1045});
	
	jQuery('#block-tweets').css({'position':'relative', 'top':5});
	
	jQuery('#block-viewteasedcpblog').css({'position':'relative', 'bottom':455, 'left':500});
	jQuery('#block-viewteasedcpblog').css({'width':444});
	
    jQuery('#block-stationmap').css({'position':'relative', 'bottom':415, 'width':940, 'height':565}); 
    
}

function fixHomeMin() {
	jQuery('#content').css({'min-height':2300});
	
	jQuery('#block-listofteasedcpnews').css({'height':'auto'});
	jQuery('#block-listofteasedcpevents').css({'height':'auto'});
	
	jQuery('#block-tweets').css({'position':'relative', 'top':0});
	
	jQuery('#block-viewteasedcpblog').css({'position':'relative', 'bottom':0, 'left':0});
	jQuery('#block-viewteasedcpblog').css({'width':480});
	
	jQuery('#block-stationmap').css({'position':'relative', 'bottom':-20, 'width':480, 'height':440});
	
}
