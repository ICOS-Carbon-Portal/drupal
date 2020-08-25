/**
 * Menu handler
 */
if (testIfOldIE()) {
	jQuery('#cp_theme_d8_menu .open_menu').css({'float':'none'});
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


jQuery('#cp_theme_d8_menu .is_topnode .open_menu').once('cp_theme_d8').click(
	function(event) {
		jQuery(this).parent().parent().toggleClass('open');
	}
);


jQuery('#cp_theme_d8_menu .open_menu').click(function(event) {
	event.preventDefault();

	var cont = jQuery(this).parent().parent();
	var submenu = jQuery(cont).children('ul');

	if (! jQuery(submenu).hasClass('cp_menu_open_submenu')) {

		if (jQuery(cont).hasClass('is_topnode') || jQuery(cont).hasClass('has_subnodes')) {
			jQuery(cont).parent().find('.cp_menu_open_submenu').each(function() {
				jQuery(this).removeClass('cp_menu_open_submenu');
				jQuery(this).css({'display':'none'});
			});

			jQuery(cont).parent().find('.open_menu').each(function() {
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

	jQuery('#cp_theme_d8_menu .is_topnode').parent().find('.open_menu').each(function() {
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
