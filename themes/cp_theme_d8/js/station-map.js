(function($, Drupal) {
	Drupal.behaviors.mapSwitchBehavior = {
		attach: function(context, settings) {
			$('.station-map-switch', context).once('mapSwitchBehavior').each(function() {
				$(this).change(function() {
					if ($('.station-map-switch:checked').val() == 'europe') {
            $('#js-station-map').css('padding-bottom', '81.5%');
						$('#js-station-map iframe').attr('src', 'https://static.icos-cp.eu/share/stationsproj/?zoom=2&center=4478000,4080000&srid=3035');
					} else {
            $('#js-station-map').css('padding-bottom', '50%');
						$('#js-station-map iframe').attr('src', 'https://static.icos-cp.eu/share/stationsproj/?srid=3857&center=430493.3433021128,6105178.323193599&zoom=2');
					}
				});
			});
		}
	};
})(jQuery, Drupal);
