(function ($) {
	'use strict';
	$(document).ready(function () {
		$.ajax({
			url: 'https://cpauth.icos-cp.eu/whoami',
			xhrFields: { withCredentials: true },
			error: showLoginLink
		})
			.done(function() {
				$(".account-link, .cart-link").show();
			})
			.fail(function(jqXHR) {
				if (jqXHR.status == 401) {
					showLoginLink();
				}
			});
	});

	function showLoginLink() {
		$(".login-link").on('click', function() {
			window.location = 'https://cpauth.icos-cp.eu/login/?targetUrl=' + encodeURIComponent(window.location.href);
		});
		$(".login-link").show();
	}
}(jQuery));
