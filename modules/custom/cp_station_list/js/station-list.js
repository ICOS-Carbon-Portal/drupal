(function ($, Drupal) {
	const stationList = {
		nameIdx: varNameIdx(Vars.stationName),
		descIdx: varNameIdx(Vars.theme),
		latIdx: varNameIdx(Vars.lat),
		lonIdx: varNameIdx(Vars.lon),
		geoJsonIdx: varNameIdx(Vars.geoJson),
		propertyCols: ['stationId', 'stationName', 'country', 'Position', 'pi', 'siteType', 'seaElev', 'stationClass', 'labelingDate'],
		rows: undefined
	};

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

	function stationsToKML(){
		const geoJson = getGeoJson();

		const kmlOptions = {
            documentName: 'ICOS Station Network',
            documentDescription: 'The ICOS network operates in three distinct domains: Atmosphere, Ecosystem and Ocean. The stations in the 13 member countries are run and funded by national institutes, universities and funding agencies. Most of the ICOS stations are fixed stations like (tall) towers or buoys, whereas the Ocean domain has also the Ships of Opportunity and Research Vessels roaming the seas.',
            name: 'name',
            description: 'description',
            simplestyle: true
        };

		returnToBrowser("stations.kml", tokml(geoJson, kmlOptions), 'data:application/vnd.google-earth.kml+xml;charset=utf-8,');
	}

	function stationsToCSV(){
		const geoJson = getGeoJson();
		const header = stationList.propertyCols.map(key =>
			Vars.hasOwnProperty(key) ? Vars[key].replace(/_/g, " ") : key
		).join(",") + "\n";
		const csvRows = geoJson.features.map(feature =>
			stationList.propertyCols.map(key => extractProp(feature.properties, key).replace(/,/g, " ")).join(",")
		).join("\n");
		const csv = header + csvRows;

		returnToBrowser("stations.csv", csv, 'data:text/csv;charset=utf-8,');
	}

	function extractProp(properties, key){
		switch(key){
			case "stationId":
				const matches = properties[key].match(/<a.* href="(.*?)">.*?<\/a>/);
				return matches ? matches[1] : properties[key];

			case "pi":
				return properties[key].replace(/<br>/g, " ");

			default:
				return properties[key];
		}
	}

	function getGeoJson(){
		const geoJson = {
			type: "FeatureCollection",
			features: []
		};
		const dynamicPropIdxs = stationList.propertyCols.map(key =>
			Vars.hasOwnProperty(key) ? varNameIdx(Vars[key]) : undefined
		);

		if (Object.keys(stationList).some(key => key === undefined))
			return geoJson;

		return stationList.rows.reduce((acc, row) => {
			const props = {
				name: row[stationList.nameIdx],
				description: row[stationList.descIdx]
			};
			const dynamicProps = stationList.propertyCols.reduce((acc, key, idx) => {
				const rowIdx = dynamicPropIdxs[idx];

				if (rowIdx === undefined)
					acc[key] = "";

				else
					acc[key] = row[rowIdx];

				return acc;
			}, {});
			Object.assign(props, dynamicProps);

			if (row[stationList.lonIdx] !== "" && row[stationList.latIdx] !== "") {
				const lon = parseFloat(row[stationList.lonIdx]);
				const lat = parseFloat(row[stationList.latIdx]);
				if (props.hasOwnProperty('Position'))
					props.Position = `${lon} ${lat}`;
				acc.features.push(getFeature("Point", [lon, lat], props));

			} else if (row[stationList.geoJsonIdx]){
				const geoJsonFeature = JSON.parse(row[stationList.geoJsonIdx]);

				if (geoJsonFeature.features)
					geoJsonFeature.features.forEach(f => {

						const {type, coordinates} = f.geometry;
						acc.features.push(getFeature(type, coordinates, props));
					});

				else if(geoJsonFeature.geometries)
					geoJsonFeature.geometries.forEach(g => acc.features.push(getFeature(g.type, g.coordinates, props)));

				else
					acc.features.push(getFeature(geoJsonFeature.type, geoJsonFeature.coordinates, props));
			}

			return acc;
		}, geoJson);
	}

	function getFeature(type, coords, props = {}){
		return {
			type: "Feature",
			geometry: {
			  type: type,
			  coordinates: coords
			},
			properties: getColorProps(type, props)
		};
	}

	function getColorProps(type, props){
		switch(type){
			case "LineString":
				return Object.assign({}, props, {stroke: '#ffce3b'});

			case "Polygon":
				return Object.assign({}, props, {fill: '#a1c389', "stroke-width": 1, stroke: '#c0d5b2'});

			default:
				return props;
		}
	}

	function returnToBrowser(filename, text, mime){
		const element = document.createElement('a');
		element.setAttribute('href', mime + encodeURIComponent(text));
		element.setAttribute('download', filename);

		element.style.display = 'none';
		document.body.appendChild(element);

		element.click();

		document.body.removeChild(element);
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
					.then(stations => {
						stationList.rows = stations.rows;
						initDataTable(stations);
					})
					.fail(function (err) {
						console.log(err);
					});

				$("#kmlExportBtn").click(stationsToKML);
				$("#csvExportBtn").click(stationsToCSV);
			});
		}
	};

})(jQuery, Drupal);
