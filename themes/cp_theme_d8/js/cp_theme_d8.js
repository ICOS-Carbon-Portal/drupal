(function ($) {
  'use strict';
  Drupal.behaviors.cp_theme_d8 = {
    attach: function(context, settings) {
    	
	    checkCpUserConsent();
	    fixHome();
        
    	$(function() {
    		
    		if ($(window).width() < 800) {
    					
    		}
    		
    		if ($(window).width() > 1200) {
    			fixHomeMax();
    			
    		} else {
    			fixHomeMin();
    		}
    		
    		$(window).resize(function () {
    			if ($(window).width() < 800) {
        			
        			
        		}
    			
    			if ($(window).width() > 1200) {
        			fixHomeMax();
        			
        		} else {
        			fixHomeMin();
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
* Home page
* Tweets on home page.
* The following methods assume an embedded Tweet block and  CP Files Movie block on main content.
*/
function fixHome() {
	var w = jQuery('.main-content').width() / 2;
	jQuery('.main-content #block-tweets').css({'background-color':'#fff', 'height':460});
	jQuery('.main-content #block-tweets .content').css({'height':460, 'overflow-y':'scroll'});
    jQuery('.main-content .block-cp-movies').css({'background-color':'#fff', 'height':460});
}

function fixHomeMax() {
	var w = jQuery('.main-content').width() / 2;
	jQuery('.main-content #block-tweets').css({'position':'relative', 'left':w + 10, 'margin-bottom':0});
	jQuery('.main-content #block-tweets').css({'width':w - 20});
	jQuery('.main-content .block-cp-movies').css({'width':w -20});
	
	var tweetOff = jQuery('#block-tweets').offset();
	var movieOff = jQuery('#block-cpmovie').offset();
	if (tweetOff.top > movieOff.top) {
		jQuery('.main-content #block-tweets').css({'bottom':tweetOff.top - movieOff.top});
	}
}

function fixHomeMin() {
	jQuery('.main-content #block-tweets').css({'position':'relative', 'left':0, 'margin-bottom':40});
	jQuery('.main-content #block-tweets').css({'width':450});
	jQuery('.main-content .block-cp-movies').css({'width':450});
	
	var tweetOff = jQuery('#block-tweets').offset();
	var movieOff = jQuery('#block-cpmovie').offset();
	if (tweetOff.top == movieOff.top) {
		jQuery('.main-content #block-tweets').css({'bottom':0});
	}
}







