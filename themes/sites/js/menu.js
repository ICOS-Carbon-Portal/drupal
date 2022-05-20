
jQuery('#block-sites-main-menu .is_topnode .open_menu').once('sites').click(
	function(event) {
		jQuery(this).parent().parent().toggleClass('open');
	}
);
