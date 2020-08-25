
jQuery('#cp_theme_d8_menu .is_topnode .open_menu').once('cp_theme_d8').click(
	function(event) {
		jQuery(this).parent().parent().toggleClass('open');
	}
);
