(function ($, Drupal) {
	Drupal.behaviors.stationListBehavior = {
		attach: function (context) {
			$('body', context).once('stationListBehavior').each(function () {
				var config = {
					sparqlUrl: "https://meta.icos-cp.eu/sparql",
					hiddenCols: [0, 1, 2, 3, 4, 5],
					latInd: 1,
					lonInd: 2,
					geoJson: 3,
					posDescInd: 4,
					themeShortInd: 5,
					nameInd: 7,
					themeInd: 8,
					coordinatesInd: 9,
					maxSegmentLengthDeg: 1
				};

				querySparql(config);
			});

			function querySparql(config){
				var stationsPromise = fetchStations(config.sparqlUrl);

				stationsPromise
					.done(function(result){
						init(parseStationsJson(result, config), config);
					})
					.fail(function(request){
						console.log(request.responseText);
					});
			}

			function init(stations, config){
				$('#stationsTable').DataTable( {
					data: stations.rows,
					columns: stations.columns,
					columnDefs: [
						{
							//Hide some columns
							targets: config.hiddenCols,
							visible: false,
							searchable: false
						},
						{
							targets: [config.coordinatesInd],
							fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
								if (oData[config.latInd] == "?" || oData[config.lonInd] == "?") {
									if (oData[config.geoJson] != "?") {
										var $icon = $('<i class="fas fa-map-marked-alt"></i>');

										$icon.click(function () {
											showMap(this, oData[config.nameInd], oData[config.themeShortInd],
												null, null, oData[config.geoJson], config);
										});

										$(nTd).html($icon);
									}
								} else {
									var $icon = $(`<span class="station-coordinates" data-target="#exampleModal"><i class="fas fa-map-marked-alt"></i> (${oData[config.latInd]}, ${oData[config.lonInd]})</span>`);

									$icon.click(function () {
										showMap(this, oData[config.nameInd], oData[config.themeShortInd],
											parseFloat(oData[config.latInd]), parseFloat(oData[config.lonInd]), null, config);
									});

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

			function showMap(sender, title, theme, lat, lon, geoJson, config) {
				$("#stationMapModalLabel").text(title);
				
				if ($('#stationMapModalBody').data('map')) {
					$("#stationMapModalBody").find(".ol-viewport").hide();
				}

				$('#station-map').on('shown.bs.modal', function () {
					if ($('#stationMapModalBody').find('canvas').length == 0) {
						var mapQuestMap = new ol.layer.Tile({
							tag: "topoMapESRI",
							visible: true,
							source: new ol.source.XYZ({
								url: 'http://server.arcgisonline.com/arcgis/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}'
							})
						});

						var station = getVectorLayer({
							theme: theme,
							data: [{
								pos: [lon, lat],
								geoJson: geoJson
							}]
						}, config);

						var view = new ol.View({
							center: ol.proj.transform([0, 0], 'EPSG:4326', 'EPSG:3857'),
							zoom: 5
						});

						if (isNumeric(lat) && isNumeric(lon)) {
							view = new ol.View({
								center: ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'),
								zoom: 5
							});
						}

						var map = new ol.Map({
							layers: [mapQuestMap, station],
							target: 'stationMapModalBody',
							view: view
						});

						if (geoJson != null) {
							var extent = station.getSource().getExtent();
							view.fit(extent, map.getSize(), {
								padding: [10, 10, 10, 10]
							});
						}

						$('#stationMapModalBody').data('map', map);

					} else {
						var map = $('#stationMapModalBody').data('map');

						var stationLayer = map.getLayers().item(1);

						stationLayer.getSource().clear();
						stationLayer.getSource().addFeatures(getVectorFeatures({
							theme: theme,
							data: [{
								pos: [lon, lat],
								geoJson: geoJson
							}]
						}, config)
						);

						$("#stationMapModalBody").find(".ol-viewport").show();

						if (geoJson == null) {
							map.getView().setCenter(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'));
							map.getView().setZoom(5);
						} else {
							var view = map.getView();
							var extent = stationLayer.getSource().getExtent();
							view.fit(extent, map.getSize(), {
								padding: [5, 5, 5, 5]
							});
						}
					}
				});

				$("#station-map").modal();
			}

			function fetchStations(sparqlUrl){
				var query = [
					'PREFIX cpst: <http://meta.icos-cp.eu/ontologies/stationentry/>',
					'SELECT',
					'(str(?s) AS ?id)',
					'(IF(bound(?lat), str(?lat), "?") AS ?latstr)',
					'(IF(bound(?lon), str(?lon), "?") AS ?lonstr)',
					'(IF(bound(?spatRef), str(?spatRef), "?") AS ?geoJson)',
					'(IF(bound(?locationDesc), str(?locationDesc), "?") AS ?location)',
					'(REPLACE(str(?class),"http://meta.icos-cp.eu/ontologies/stationentry/", "") AS ?themeShort)',
					'(str(?sName) AS ?Id)',
					'(str(?lName) AS ?Name)',
					'(REPLACE(str(?class),"http://meta.icos-cp.eu/ontologies/stationentry/", "") AS ?Theme)',
					'(IF(bound(?country), str(?country), "?") AS ?Country)',
					'(GROUP_CONCAT(?piLname; separator=";") AS ?PI_names)',
					'(IF(bound(?siteType), LCASE(str(?siteType)), "?") AS ?Site_type)',
					'(IF(bound(?elevationAboveSea), str(?elevationAboveSea), "?") AS ?Elevation_above_sea)',
					'(IF(bound(?elevationAboveGround), str(?elevationAboveGround), "?") AS ?Elevation_above_ground)',
					'(IF(bound(?stationClass), str(?stationClass), "?") AS ?Station_class)',
					'FROM <http://meta.icos-cp.eu/resources/stationentry/>',
					'WHERE {',
					'?s a ?class .',
					'OPTIONAL{?s cpst:hasLat ?lat . ?s cpst:hasLon ?lon } .',
					'OPTIONAL{?s cpst:hasSpatialReference ?spatRef } .',
					'OPTIONAL{?s cpst:hasLocationDescription ?locationDesc } .',
					'OPTIONAL{?s cpst:hasCountry ?country } .',
					'?s cpst:hasShortName ?sName .',
					'?s cpst:hasLongName ?lName .',
					'?s cpst:hasPi ?pi .',
					'OPTIONAL{?pi cpst:hasFirstName ?piFname } .',
					'?pi cpst:hasLastName ?piLname .',
					'OPTIONAL{?s cpst:hasSiteType ?siteType } .',
					'OPTIONAL{?s cpst:hasElevationAboveSea ?elevationAboveSea } .',
					'OPTIONAL{?s cpst:hasElevationAboveGround ?elevationAboveGround } .',
					'OPTIONAL{?s cpst:hasStationClass ?stationClass } .',
					'}',
					'GROUP BY ?s ?lat ?lon ?spatRef ?locationDesc ?class ?country ?sName ?lName ?siteType ?elevationAboveSea',
					' ?elevationAboveGround ?stationClass ?stationKind ?preIcosMeasurements ?operationalDateEstimate ?isOperational ?fundingForConstruction'
				].join("\n");

				return $.ajax({
					type: "POST",
					data: {query: query},
					url: sparqlUrl,
					dataType: "json"
				});
			}

			function parseStationsJson(stationsJson, config){
				var themeName = {"AS": "Atmosphere", "ES": "Ecosystem", "OS": "Ocean"};

				var columns = stationsJson.head.vars.map(function (currVal){
					var cols = {};
					cols.title = currVal;
					return cols;
				});

				columns.splice(config.coordinatesInd, 0, {title: "Coordinates"});

				var rows = stationsJson.results.bindings.map(function (currObj){
					var row = [];

					columns.forEach(function(colObj){
						if (colObj.title == "Theme"){
							row.push(themeName[currObj[colObj.title].value]);
						} else if (colObj.title == "Country"){
							row.push(countries[currObj[colObj.title].value] + " (" + currObj[colObj.title].value + ")");
						} else if (colObj.title == "PI_names" || colObj.title == "PI_mails"){
							row.push(currObj[colObj.title].value.replace(";", "<br>"));
						} else if (colObj.title == "Coordinates") {
							row.push(`${currObj["latstr"].value}, ${currObj["lonstr"].value}`);
						} else if (colObj.title == "Station_class") {
							row.push(currObj[colObj.title].value.replace("Ass", "Associated"));
						} else {
							row.push(currObj[colObj.title].value);
						}
					});

					return row;
				});

				columns.forEach(function(colObj){
					colObj.title = colObj.title.replace(/_/g, " ");
				});

				var stations = {};
				stations.rows = rows;
				stations.columns = columns;

				return stations;
			}
		}
	}
})(jQuery, Drupal);


