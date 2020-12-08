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
					themeInd: 6,
					shortNameInd: 8,
					maxSegmentLengthDeg: 1
				};

				querySparql(config);
			});

			function querySparql(config){
				var stationsPromise = fetchStations(config.sparqlUrl);

				stationsPromise
					.done(function(result){
						init(parseStationsJson(result), config);
					})
					.fail(function(request){
						console.log(request.responseText);
					});
			}

			function init(stations, config){
				$("body").prepend('<div id="map" title="Station map"></div>');
				$('#stationsTable').DataTable( {
					data: stations.rows,
					columns: stations.columns,
					columnDefs: [
						{
							//Hide some columns
							targets: config.hiddenCols,
							visible: false
						},
						{
							targets: [config.themeInd],
						}
					],
					lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
				});

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
					'(IF(bound(?siteType), str(?siteType), "?") AS ?Site_type)',
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

			function parseStationsJson(stationsJson){
				var themeName = {"AS": "Atmosphere", "ES": "Ecosystem", "OS": "Ocean"};

				var columns = stationsJson.head.vars.map(function (currVal){
					var cols = {};
					cols.title = currVal;
					return cols;
				});

				var rows = stationsJson.results.bindings.map(function (currObj){
					var row = [];

					columns.forEach(function(colObj){
						if (colObj.title == "Theme"){
							row.push(themeName[currObj[colObj.title].value]);
						} else if (colObj.title == "Country"){
							row.push(countries[currObj[colObj.title].value] + " (" + currObj[colObj.title].value + ")");
						} else if (colObj.title == "PI_names" || colObj.title == "PI_mails"){
							row.push(currObj[colObj.title].value.replace(";", "<br>"));
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


