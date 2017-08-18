(function ($) {
  'use strict';
  Drupal.behaviors.cp_theme_d8 = {
    attach: function(context, settings) {
    	
	    checkCpUserConsent();   
	    //handleIngressAndTitle(); 
	    //fixHome();
	    
    	$(function() {
    		
    		if ($(window).width() < 800) {
    					
    		}
    		
    		if ($(window).width() > 1260) {
    			//fixHomeMax();
    			
    		} else {
    			//fixHomeMin();
    		}
    		
    		$(window).resize(function () {
    			if ($(window).width() < 800) {
        					
        		}
    			
    			if ($(window).width() > 1260) {
        			//fixHomeMax();
        			
        		} else {
        			//fixHomeMin();
        		}
    			
    		});
    		
    	});
    }
  };
}(jQuery));

/**
 * Menu handler  
 */
if (testIfOldIE()) {
	jQuery('#cp_theme_d8_menu .sublinks').css({'float':'none'});
}

jQuery(window).resize(function () {
	if (jQuery(window).width() > 799) {
		closeCPMenuItems();
	}
});

jQuery(document).click(function(e) {
	if (jQuery(window).width() > 799) {
		if (! jQuery(e.target).parents('.is_topnode').length) {
			closeCPMenuItems();
		}
	}
});

jQuery('#cp_theme_d8_menu .sublinks').click(function() {
	var cont = jQuery(this).parent().parent();
	var submenu = jQuery(cont).children('ul');

	if (! jQuery(submenu).hasClass('cp_menu_open_submenu')) {
		
		if (jQuery(cont).hasClass('is_topnode') || jQuery(cont).hasClass('has_subnodes')) {
			jQuery(cont).parent().find('.cp_menu_open_submenu').each(function() {
				jQuery(this).removeClass('cp_menu_open_submenu');
				jQuery(this).css({'display':'none'});
			});

			jQuery(cont).parent().find('.sublinks').each(function() {
				jQuery(this).attr('src', '/themes/cp_theme_d8/images/menu_arrow_down_w.png');
			});
		} 

		jQuery(submenu).addClass('cp_menu_open_submenu');

		if (jQuery(window).width() < 800) {
			jQuery(submenu).css({'display':'block', 'position':'relative', 'z-index':'1000'});
		} else {
			jQuery(submenu).css({'display':'block', 'position':'absolute', 'z-index':'1000'});
		}

		jQuery(this).attr('src', '/themes/cp_theme_d8/images/menu_arrow_right_w.png');

	} else if (jQuery(submenu).hasClass('cp_menu_open_submenu')) {
		jQuery(submenu).removeClass('cp_menu_open_submenu');
		jQuery(this).attr('src', '/themes/cp_theme_d8/images/menu_arrow_down_w.png');
		
		jQuery(submenu).css({'display':'none'});
	}
});

function closeCPMenuItems() {
	jQuery('#cp_theme_d8_menu .is_topnode').find('.cp_menu_open_submenu').each(function() {
		jQuery(this).removeClass('cp_menu_open_submenu');
		jQuery(this).css({'display':'none'});
	});

	jQuery('#cp_theme_d8_menu .is_topnode').parent().find('.sublinks').each(function() {
		jQuery(this).attr('src', '/themes/cp_theme_d8/images/menu_arrow_down_w.png');
	});
}

function testIfOldIE() {
	var browser = window.navigator.userAgent;

	if (browser.indexOf('MSIE ') > 0
			|| browser.indexOf('Trident/') > 0) {

		return true;
	}

	return false;
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
    jQuery('#block-stationmap #map').css({'height':480});
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


(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');



ga('create', 'UA-53530911-2', 'auto');

ga('send', 'pageview');
