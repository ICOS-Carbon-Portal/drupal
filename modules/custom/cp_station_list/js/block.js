Drupal.behaviors.stationListBehavior = {
	attach: function (context, settings) {
		once('stationListBehavior', 'body', context).forEach(el => {
			fetchMergeParseStations(new StationParser(countries))
				.then(stations => {
					const titles = stations.columns.map(c => c.title)
					const countryStations = stations.rows.filter(r => r[titles.indexOf("Country")] == settings.stationList.country);
					const headerHtml = "<tr><th>Name</th><th>Theme</th><th>Site type</th><th>ID</th></tr>";
					const stationHtml = countryStations.map(station => {
						return `<tr>
								<td>${station[titles.indexOf("Name")]}</td>
								<td>${station[titles.indexOf("Theme")]}</td>
								<td>${station[titles.indexOf("Site type")].replace(/\b\w/g, s => s.toUpperCase()) }</td>
								<td>${station[titles.indexOf("Id")]}</td>
							</tr>`
					}).join('');
					const tableHtml = `<table class="mb-5">${headerHtml}${stationHtml}</table>`;

					document.querySelector('.block-station-list-block .content').innerHTML = tableHtml;
				});
		});
	}
};