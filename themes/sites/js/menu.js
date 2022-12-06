
jQuery('#block-sites-main-menu .primary-nav-menu-item-has-children').once('sites').click(
	function() {
		if (window.innerWidth < 576) {
			jQuery(this).toggleClass('open');
		}
	}
);
