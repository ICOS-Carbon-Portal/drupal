(function ($) {
	'use strict';
	$(document).ready(function () {
		$.ajax({
			url: 'https://cpauth.icos-cp.eu/whoami',
			xhrFields: { withCredentials: true },
			error: showLoginLink
		})
			.done(function() {
				$(".logOutLnk").on('click', function() {
					$.ajax({
						url: 'https://cpauth.icos-cp.eu/logout',
						xhrFields: { withCredentials: true }
					}).done(function(){
						window.location.reload();
					});
				});
				$(".accountLnk").on('click', function() {
					window.location = 'https://cpauth.icos-cp.eu/';
				});
				$(".logOutLnk, .accountLnk").show();
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
