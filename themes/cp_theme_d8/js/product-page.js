(function($, Drupal) {
	Drupal.behaviors.productPageBehavior = {
		attach: function(context, settings) {
			$('body', context).once('productPageBehavior').each(function() {
				displayAbstract();
				displayCitation();
				displayPreviewTable(config.co2);
				displayPreviewTable(config.ch4);
			});
		}
	}

	function displayCitation() {
		$.ajax({
			url: 'https://data.datacite.org/text/x-bibliography;style=copernicus-publications/10.18160/ATM_NRT_CO2_CH4',
		}).done(function(citation) {
			$('#js-citation').html(`<strong>Citation:</strong> ${citation}`);
		});
	}

	function displayAbstract() {
		$.ajax({
			url: 'https://api.datacite.org/works/10.18160/ATM_NRT_CO2_CH4'
		}).done(function(result) {
			let $xml = $($.parseXML(new TextDecoder().decode(u_atob(result.data.attributes.xml))));
			let description = $xml.find('description').text();
			$('#js-abstract').html(`<strong>Abstract:</strong> ${description}`);
		});
	}

	function u_atob(ascii) {
		return Uint8Array.from(atob(ascii), c => c.charCodeAt(0));
	}

	const config = {
		co2: {
			spec: '<http://meta.icos-cp.eu/resources/cpmeta/atcCo2NrtGrowingDataObject>',
			param: 'co2',
			numberOfCellsPerRow: 6
		},
		ch4: {
			spec: '<http://meta.icos-cp.eu/resources/cpmeta/atcCh4NrtGrowingDataObject>',
			param: 'ch4',
			numberOfCellsPerRow: 6
		}
	};

	const query = (spec) => {
		return `prefix cpmeta: <http://meta.icos-cp.eu/ontologies/cpmeta/>
		prefix prov: <http://www.w3.org/ns/prov#>
		select ?dobj ?station ?instrument ?samplingHeight
		where {
			VALUES ?spec { ${spec} }
			?dobj cpmeta:hasObjectSpec ?spec .
			FILTER NOT EXISTS {[] cpmeta:isNextVersionOf ?dobj}
			FILTER EXISTS {?dobj cpmeta:wasSubmittedBy/prov:endedAtTime []}
			?dobj cpmeta:wasAcquiredBy [
				prov:wasAssociatedWith/cpmeta:hasName ?station ;
				cpmeta:wasPerformedWith ?instrument ;
				cpmeta:hasSamplingHeight ?samplingHeight
			] .
		}
		order by ?station ?instrument ?samplingHeight`;
	}

	const displayPreviewTable = (config) => {
		$.ajax({
			method: 'post',
			url: 'https://meta.icos-cp.eu/sparql/',
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'text/plain',
				'Cache-Control': 'max-age=1000000'
			},
			data: query(config.spec)
		}).done(function(result) {
			let station = '';
			let instrument = '';
			const rows = $(result.results.bindings.map((binding, index) => {
				let row = '';
				if (binding.station.value !== station || binding.instrument.value != instrument) {
					row += index == 0 ? '' : '</tr>';
					station = binding.station.value;
					instrument = binding.instrument.value;
					row += `<tr><th scope="row">${station} (${instrument.split('/').pop()})</th>`;
				}
				let objId = binding.dobj.value.split('/').pop();
				let previewUrl = `https://data.icos-cp.eu/dygraph-light/?objId=${objId}&x=TIMESTAMP&type=line&linking=overlap&y=${config.param}`;
				row += `<td data-id="${objId}"><a href="${previewUrl}" target="_blank">${binding.samplingHeight.value}</a></td>`;
				return row;
			}).join()).map(function() {
				while (this.children.length <= config.numberOfCellsPerRow) {
					let td = document.createElement('td');
					$(this).append(td);
				}
				let ids = Array.from(this.children).reduce(function(acc, cur) {
					let id = $(cur).data('id');
					return id ? acc.concat(id) : acc;
				}, []);
				$(this).append(`<td><a href="https://data.icos-cp.eu/dygraph-light/?objId=${ids}&x=TIMESTAMP&type=line&linking=overlap&y=${config.param}">All</a></td>`);
				return this;
			});
			$(`#${config.param}-table tbody`).html(rows);
			$(`#${config.param}-table`).show();
		})
	}
})(jQuery, Drupal);
