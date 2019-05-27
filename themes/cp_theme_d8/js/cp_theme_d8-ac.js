makeToplinksUnclickable();

function makeToplinksUnclickable() {
	jQuery('#cp_theme_d8_menu_breadcrumbs').hide();

	jQuery('#cp_theme_d8_menu a').each(function(i, e) {
		if (jQuery(e).attr('href') === '/internal:#') {
			jQuery(e).attr('href', 'javascript:void(0);');  //jQuery(location).attr('href')
		}
	});
}
