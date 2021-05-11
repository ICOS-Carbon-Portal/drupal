(function ($, Drupal) {

	const config = {
		sparqlUrl: "https://meta.icos-cp.eu/sparql",
		stationVisUrl: "https://meta.icos-cp.eu/station/"
	};

	const getIconUrl = themeShort =>
		`https://static.icos-cp.eu/share/stations/icons/${themeShort.toLowerCase()}.png`;

	const Vars = {
		lat: 'latstr',
		lon: 'lonstr',
		geoJson: 'geoJson',
		prodUri: 'prodUri',
		stationId: 'Id',
		stationName: 'Name',
		country: 'Country',
		theme: 'Theme',
		themeShort: 'themeShort',
		pi: 'PI_names',
		siteType: 'Site_type',
		seaElev: 'Elevation_above_sea',
		groundElev: 'Elevation_above_ground',
		stationClass: 'Station_class',
		labelingDate: 'Labeling_date',
		coords: 'Location'
	};

	const Columns = [
		Vars.stationId, Vars.stationName, Vars.theme, Vars.stationClass, Vars.coords, Vars.country,
		Vars.pi, Vars.siteType, Vars.seaElev, Vars.labelingDate, Vars.lat, Vars.lon, Vars.geoJson, Vars.themeShort
	];

	function idx(varName){
		return Columns.indexOf(varName);
	}

	const provQuery = `PREFIX cpst: <http://meta.icos-cp.eu/ontologies/stationentry/>
SELECT *
FROM <http://meta.icos-cp.eu/resources/stationentry/>
WHERE {
	{
		select ?s (GROUP_CONCAT(?piLname; separator=";") AS ?${Vars.pi})
		where{ ?s cpst:hasPi/cpst:hasLastName ?piLname }
		group by ?s
	}
	?s a ?owlClass .
	BIND(REPLACE(str(?owlClass),"http://meta.icos-cp.eu/ontologies/stationentry/", "") AS ?${Vars.themeShort})
	?s cpst:hasShortName ?${Vars.stationId} .
	?s cpst:hasLongName ?${Vars.stationName} .
	OPTIONAL{?s cpst:hasLat ?${Vars.lat} . ?s cpst:hasLon ?${Vars.lon} }
	OPTIONAL{?s cpst:hasSpatialReference ?${Vars.geoJson} }
	OPTIONAL{?s cpst:hasCountry ?${Vars.country} }
	OPTIONAL{?s cpst:hasSiteType ?${Vars.siteType} }
	OPTIONAL{?s cpst:hasElevationAboveSea ?${Vars.seaElev} }
	#OPTIONAL{?s cpst:hasElevationAboveGround ?${Vars.groundElev} }
	OPTIONAL{?s cpst:hasStationClass ?${Vars.stationClass} }
}`;

const prodQuery = `prefix cpmeta: <http://meta.icos-cp.eu/ontologies/cpmeta/>
prefix cpst: <http://meta.icos-cp.eu/ontologies/stationentry/>
select *
from <http://meta.icos-cp.eu/resources/icos/>
from <http://meta.icos-cp.eu/resources/cpmeta/>
from <http://meta.icos-cp.eu/resources/stationentry/>
where{
	{
		select ?s ?ps (GROUP_CONCAT(?lname; separator=";") AS ?${Vars.pi}) where {
			?s cpst:hasProductionCounterpart ?psStr .
			bind(iri(?psStr) as ?ps)
			?memb cpmeta:atOrganization ?ps ; cpmeta:hasRole <http://meta.icos-cp.eu/resources/roles/PI> .
			filter not exists {?memb cpmeta:hasEndTime []}
			?pers cpmeta:hasMembership ?memb ; cpmeta:hasLastName ?lname .
		}
		group by ?s ?ps
	}
	?ps cpmeta:hasStationId ?${Vars.stationId} ; cpmeta:hasName ?${Vars.stationName} .
	optional{ ?ps cpmeta:hasElevation ?${Vars.seaElev} }
	optional{ ?ps cpmeta:hasLatitude ?${Vars.lat}}
	optional{ ?ps cpmeta:hasLongitude ?${Vars.lon}}
	optional{ ?ps cpmeta:hasSpatialCoverage/cpmeta:asGeoJSON ?${Vars.geoJson}}
	optional{ ?ps cpmeta:countryCode ?${Vars.country}}
	optional{ ?ps cpmeta:hasStationClass  ?${Vars.stationClass}}
	optional{ ?ps cpmeta:hasLabelingDate ?${Vars.labelingDate}}
	bind(?ps as ?${Vars.prodUri})
}
`;

	const fetchStations = query => $.ajax({
		type: "POST",
		data: {query},
		url: config.sparqlUrl,
		dataType: "json"
	});


	function parseStationsJson(bindings){
		const themeName = {"AS": "Atmosphere", "ES": "Ecosystem", "OS": "Ocean"};

		const modifiers = {
			[Vars.theme]:       (v, row) => themeName[row[Vars.themeShort].value] || "?",
			[Vars.country]:      v => `${countries[v]} (${v})`,
			[Vars.pi]:           v => v.split(';').sort().join("<br>"),
			[Vars.stationClass]: v => (v == "Ass" ? "Associated" : v),
			[Vars.siteType]:     v => v.toLowerCase(),
			[Vars.stationId]:   (v, row) => (row[Vars.prodUri]
				? `<a target="_blank" href="${row[Vars.prodUri].value}">${v}</a>`
				: v
			)
		}

		var rows = bindings.map(row =>
			Columns.map(col => {
				const v = (row[col] || {}).value || "";
				const modifier = modifiers[col];
				return modifier ? modifier(v, row) : v;
			})
		);

		return ({
			rows,
			columns: Columns.map(col => ({title: col.replace(/_/g, " ")}))
		});
	}

	function mergeProvAndProd(prov, prod){
		const prodLookup = prod[0].results.bindings.reduce((acc, next) => {
			acc[next.s.value] = next;
			return acc;
		}, {});
		return prov[0].results.bindings.map(row => $.extend({}, row, prodLookup[row.s.value]));
	}

	function showMap(title, theme, lat, lon, geoJson) {
		if(geoJson == null && (lat == null || lon == null)) return;

		const iconQ = (lat != null && lon != null) ? ('&icon=' + getIconUrl(theme)) : '';
		const coverageJson = geoJson || `{"coordinates":[${lon}, ${lat}],"type":"Point"}`;
		const coverageQ = 'coverage=' + encodeURIComponent(coverageJson);

		const $frame = $(`<iframe style="border:0;width:100%;height:100%" src="${config.stationVisUrl}?${coverageQ}${iconQ}"></iframe>`);

		$("#stationMapModalLabel").text(title);
		$('#stationMapModalBody').html($frame);
		$("#station-map").modal();
	}

	function initDataTable(stations){
		$('#stationsTable').DataTable( {
			data: stations.rows,
			columns: stations.columns,
			columnDefs: [
				{
					//Hide some columns
					targets: [Vars.lat, Vars.lon, Vars.geoJson, Vars.themeShort].map(idx),
					visible: false,
					searchable: false
				},
				{
					targets: [idx(Vars.coords)],
					fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {

						const [stLat, stLon, stName, stTheme, stGeojson] = [
							Vars.lat, Vars.lon, Vars.stationName, Vars.themeShort, Vars.geoJson
						].map(vname => oData[idx(vname)]);

						if (stLat == "" || stLon == "") {
							if (stGeojson != "") {
								var $icon = $('<i class="fas fa-map-marked-alt"></i>');

								$icon.click(() => showMap(stName, stTheme, null, null, stGeojson));

								$(nTd).html($icon);
							}
						} else {
							var $icon = $(`<span class="station-coordinates" data-target="#exampleModal">
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

				$.when(fetchStations(provQuery), fetchStations(prodQuery))
					.then(mergeProvAndProd)
					.then(parseStationsJson)
					.done(initDataTable)
					.fail(function(err){
						console.log(err);
					});
			});
		}
	};

})(jQuery, Drupal);
