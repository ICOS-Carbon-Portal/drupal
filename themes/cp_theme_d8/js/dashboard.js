// This library is included on the /data page
// The page needs to include the correct js id to display the dashboards

(function($, Drupal) {
	Drupal.behaviors.dashboardBehavior = {
		attach: function(context, settings) {
			$('body', context).once('dashboardBehavior').each(function() {
				displayDashboard();
			});
		}
	}

	const maxHeightPerStation =
	`prefix cpmeta: <http://meta.icos-cp.eu/ontologies/cpmeta/>
		prefix prov: <http://www.w3.org/ns/prov#>
		select (max(?sheight) as ?samplingHeight) (sample(?sId) as ?stationId) ?name where {
			?spec cpmeta:containsDataset/cpmeta:hasColumn/cpmeta:hasColumnTitle "co2"^^xsd:string;
				cpmeta:hasDataLevel "2"^^xsd:integer ;
				cpmeta:hasAssociatedProject <http://meta.icos-cp.eu/resources/projects/icos> .
			?dobj cpmeta:hasObjectSpec ?spec .
			filter not exists {[] cpmeta:isNextVersionOf ?dobj}
			?dobj cpmeta:wasAcquiredBy/prov:wasAssociatedWith ?station .
			?station cpmeta:hasStationId ?sId .
			?station cpmeta:hasName ?name .
			?dobj cpmeta:wasAcquiredBy/cpmeta:hasSamplingHeight ?sheight .
		}
		group by ?station ?name
	 `

	const displayDashboard = () => {
		$.ajax({
			method: 'post',
			url: 'https://meta.icos-cp.eu/sparql',
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'text/plain',
				'Cache-Control': 'max-age=1000000'
			},
			data: maxHeightPerStation
		}).done(function(stationsResult) {
			let stations = stationsResult.results.bindings;
			let station = stations[Math.floor(Math.random() * stations.length)];

			$('#js-dashboard-station-name').text(station.name.value);
			$('#js-co2-dashboard').attr('src', `https://data.icos-cp.eu/dashboard/?stationId=${station.stationId.value}&valueType=co2&height=${station.samplingHeight.value}`);
			$('#js-ch4-dashboard').attr('src', `https://data.icos-cp.eu/dashboard/?stationId=${station.stationId.value}&valueType=ch4&height=${station.samplingHeight.value}`);
		})
	}
})(jQuery, Drupal);
