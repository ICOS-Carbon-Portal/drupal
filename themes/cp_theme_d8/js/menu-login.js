(function ($) {
	'use strict';
	$(document).ready(function () {
		$.ajax({
			url: 'https://cpauth.icos-cp.eu/whoami',
			xhrFields: { withCredentials: true },
			error: showLoginLink
		})
			.done(function() {
				$(".accountLnk").on('click', function() {
					window.location = 'https://cpauth.icos-cp.eu/';
				});
				$(".accountLnk").show();
				$(".logInLnk").hide();
			})
			.fail(function(jqXHR) {
				if (jqXHR.status == 401) {
					showLoginLink();
				}
			});
	});

	function showLoginLink() {
		$(".logInLnk").on('click', function() {
			window.location = 'https://cpauth.icos-cp.eu/login/?targetUrl=' + encodeURIComponent(window.location.href);
		});
		$(".logInLnk").show();
	}
}(jQuery));
