(function ($, Drupal) {

	function showMap(title, theme, lat, lon, geoJson) {
		if(geoJson == null && (lat == null || lon == null)) return;

		const iconQ = (lat != null && lon != null) ? ('&icon=' + getIconUrl(theme)) : '';
		const coverageJson = geoJson || `{"coordinates":[${lon}, ${lat}],"type":"Point"}`;
		const coverageQ = 'coverage=' + encodeURIComponent(coverageJson);

		const $frame = $(`<iframe style="border:0;width:100%;height:100%" src="${urls.stationVisUrl}?${coverageQ}${iconQ}"></iframe>`);

		$("#stationMapModalLabel").text(title);
		$('#stationMapModalBody').html($frame);

		new bootstrap.Modal($("#station-map")).show()
	}

	function initDataTable(stations) {
		$('#stationsTable').DataTable({
			data: stations.rows,
			columns: stations.columns,
			columnDefs: [
				{
					//Hide some columns
					targets: [Vars.lat, Vars.lon, Vars.geoJson, Vars.themeShort].map(varNameIdx),
					visible: false,
					searchable: false
				},
				{
					targets: [varNameIdx(Vars.coords)],
					fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {

						const [stLat, stLon, stName, stTheme, stGeojson] = [
							Vars.lat, Vars.lon, Vars.stationName, Vars.themeShort, Vars.geoJson
						].map(vname => oData[varNameIdx(vname)]);

						if (stLat == "" || stLon == "") {
							if (stGeojson != "") {
								var $icon = $(`<span class="station-coordinates">
								<i class="fas fa-map-marked-alt"></i>
							</span>`);

								$icon.click(() => showMap(stName, stTheme, null, null, stGeojson));

								$(nTd).html($icon);
							}
						} else {
							var $icon = $(`<span class="station-coordinates">
								<i class="fas fa-map-marked-alt"></i> (${stLat}, ${stLon})
							</span>`);

							$icon.click(() => showMap(stName, stTheme, parseFloat(stLat), parseFloat(stLon), null));

							$(nTd).html($icon);
						}
					}
				}
			],
			lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
			orderCellsTop: true,
			initComplete: function () {
				this.api().columns().every(function (ind) {
					var column = this;

					var $headerControl = $('<div class="input-group">' +
						'<input class="suggestInput form-control" type="search" placeholder="Search column" />' +
						'</div>');
					var $suggestInput = $headerControl.find("input");
					$headerControl.appendTo($(column.header()));

					$suggestInput
						.on('click', function (event) {
							event.stopPropagation();
						})
						.on('keyup', function (event) {
							var val = $.fn.dataTable.util.escapeRegex($(this).val());
							column.search(val ? val : '', true, false).draw();
						})
				});
			}
		});
	}

	Drupal.behaviors.stationListBehavior = {
		attach: function (context) {
			$('body', context).once('stationListBehavior').each(function () {

				$.when(fetchMergeParseStations(new StationParser(countries)))
					.then(initDataTable)
					.fail(function (err) {
						console.log(err);
					});
			});
		}
	};

})(jQuery, Drupal);
