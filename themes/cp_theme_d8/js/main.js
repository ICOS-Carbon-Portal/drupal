'use strict';
Drupal.behaviors.cp_theme_d8 = {
	attach: function(context) {
		once('cp_theme_d8', '#menu-button', context).forEach(function(button) {
			button.addEventListener('click', function() {
				document.getElementById('cp_theme_d8_menu').classList.toggle('open');
			});
		});

		once('cp_theme_d8', '#cp_theme_d8_menu .is_topnode .open_menu').forEach(function (el) {
			el.addEventListener('click', function () {
				el.parentElement.parentElement.classList.toggle('open')
			})
		});
	}
};
