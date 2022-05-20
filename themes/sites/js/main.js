(function ($) {
	'use strict';
	Drupal.behaviors.sites = {
		attach: function(context) {
			$('#menu-button', context).once('sites').each(function() {
				$(this).click(function() {
					$('#block-sites-main-menu').toggleClass('open');
				});
			});
		}
	};
}(jQuery));
