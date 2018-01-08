(function ($) {
  'use strict';
  Drupal.behaviors.cp_theme_d8 = {
    attach: function(context, settings) {

    }
  };
}(jQuery));

fixHome();

if (jQuery(window).width() > 1200) {
	fixHomeMax();

} else {
	fixHomeMin();
}

jQuery(window).resize(function () {
	if (jQuery(window).width() > 1200) {
		fixHomeMax();

	} else {
		fixHomeMin();
	}
});

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
	if (tweetOff && movieOff) {
		if (tweetOff.top > movieOff.top) {
			jQuery('.main-content #block-tweets').css({'bottom':tweetOff.top - movieOff.top});
		}
	}
}

function fixHomeMin() {
	jQuery('.main-content #block-tweets').css({'position':'relative', 'left':0, 'margin-bottom':40});
	jQuery('.main-content #block-tweets').css({'width':450});
	jQuery('.main-content .block-cp-movies').css({'width':450});

	var tweetOff = jQuery('#block-tweets').offset();
	var movieOff = jQuery('#block-cpmovie').offset();
	if (tweetOff && movieOff) {
		if (tweetOff.top == movieOff.top) {
			jQuery('.main-content #block-tweets').css({'bottom':0});
		}
	}
}


(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-53530911-1', 'auto');
ga('send', 'pageview');
