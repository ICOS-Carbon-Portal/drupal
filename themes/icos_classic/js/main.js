(function ($) {
	'use strict';
	Drupal.behaviors.cp_theme_d8 = {
		attach: function(context) {
			$('#menu-button', context).once('cp_theme_d8').each(function() {
				$(this).click(function() {
					$('#cp_theme_d8_menu').toggleClass('open');
				});
			});
		}
	};
}(jQuery));
