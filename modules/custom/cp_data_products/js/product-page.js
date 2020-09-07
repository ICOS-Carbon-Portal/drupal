// This library is included for /data-products/xxx pages by the theme config
// A config object needs be included in the page content

(function ($, Drupal, drupalSettings) {
	Drupal.behaviors.productPageBehavior = {
		attach: function(context, settings) {
			$('body', context).once('productPageBehavior').each(function() {
				const tables = settings.data_product_preview;
				$.each(tables, function(index, value) {
					displayPreviewTable(value);
				});
				$(document).on('change', '.map-graph-select', function() {
					window.open($("option:selected", this).data('url'));
				})
			});
		}
	}

	function displayCitation(url) {
		$.ajax({
			url: url,
		}).done(function(citation) {
			$('#js-citation').html(`<strong>Citation:</strong> ${citation}`);
		});
	}

	function displayAbstract(url) {
		$.ajax({
			url: url
		}).done(function(result) {
			let $xml = $($.parseXML(new TextDecoder().decode(u_atob(result.data.attributes.xml))));
			let description = $xml.find('description').text();
			$('#js-abstract').html(`<strong>Abstract:</strong> ${description}`);
		});
	}

	function u_atob(ascii) {
		return Uint8Array.from(atob(ascii), c => c.charCodeAt(0));
	}

	const query = (spec, shouldGetHeight, previewType) => {
		const samplingHeight = shouldGetHeight
			? 'OPTIONAL{?dobj cpmeta:wasAcquiredBy/cpmeta:hasSamplingHeight ?samplingHeight} .'
			: ''
		const dates = previewType == "map-graph"
			? `?dobj cpmeta:wasAcquiredBy/prov:startedAtTime ?start .
				 ?dobj cpmeta:wasAcquiredBy/prov:endedAtTime ?end .`
			: ''
		return `prefix cpmeta: <http://meta.icos-cp.eu/ontologies/cpmeta/>
		prefix prov: <http://www.w3.org/ns/prov#>
		select ?dobj ?station ?samplingHeight ?start ?end
		where {
			VALUES ?spec { <${spec}> }
			?dobj cpmeta:hasObjectSpec ?spec .
			FILTER NOT EXISTS {[] cpmeta:isNextVersionOf ?dobj}
			?dobj cpmeta:wasSubmittedBy/prov:endedAtTime ?submEnd .
			?dobj cpmeta:wasAcquiredBy/prov:wasAssociatedWith/cpmeta:hasName ?station .
			${samplingHeight}
			${dates}
			FILTER(?station != "Karlsruhe")
		}
		order by ?station ?samplingHeight ?start`;
	}

	function timestampToDate(timestamp) {
		return timestamp.substring(0, timestamp.indexOf('T'));
	}

	const displayPreviewTable = (tableConfig) => {

		const shouldGetHeight = tableConfig.noHeight ? false : true;
		$.ajax({
			method: 'post',
			url: 'https://meta.icos-cp.eu/sparql',
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'text/plain',
				'Cache-Control': 'max-age=1000000'
			},
			data: query(tableConfig.spec, shouldGetHeight, tableConfig.previewType)
		}).done(function(result) {
			let station = '';
			let rowNumber = 1;
			let tableLength = $(`#${tableConfig.param[0]}-table thead th`).length - 1;
			const rows = $(result.results.bindings.map((binding, index) => {
				let row = '';
				if (tableConfig.previewType == "map-graph") {
					if (binding.station.value !== station) {
						row += index == 0 ? '' : '</select></td></tr>';
						station = binding.station.value;
						row += `<tr><th scope="row">${station}</th><td><select class="map-graph-select"><option selected disabled>Select dates</option>`;
						rowNumber = 1;
					}
					let objId = binding.dobj.value.split('/').pop();
					let previewUrl = `https://data.icos-cp.eu/map-graph/${objId}`
					let label = `${timestampToDate(binding.start.value)} \u2013 ${timestampToDate(binding.end.value)}`
					row += `<option data-url="${previewUrl}">${label}</option>`
					rowNumber++;
				} else if (tableConfig.param.length > 1) {
					row += `<tr><th scope="row">${binding.station.value}</th>`;
					let objId = binding.dobj.value.split('/').pop();
					tableConfig.param.map(param => {
						row += `<td><a href="https://data.icos-cp.eu/dygraph-light/?objId=${objId}&x=TIMESTAMP&type=scatter&linking=overlap&y=${param}">Preview</a></td>`;
					});
					row += '</tr>';
				} else {
					if (binding.station.value !== station) {
						row += index == 0 ? '' : '</tr>';
						station = binding.station.value;
						row += `<tr><th scope="row">${station}</th>`;
						rowNumber = 1;
					} else if (rowNumber % tableLength == 1) {
						row += `</tr><th scope="row"></th>`;
					}
					let objId = binding.dobj.value.split('/').pop();
					let previewUrl = `https://data.icos-cp.eu/dygraph-light/?objId=${objId}&x=TIMESTAMP&type=scatter&linking=overlap&y=${tableConfig.param[0]}`;
					let label = binding.samplingHeight ? binding.samplingHeight.value : 'Preview';
					row += `<td data-id="${objId}"><a href="${previewUrl}">${label}</a></td>`;
					rowNumber++;
				}
				return row;
			}).join()).map(function() {
				while (this.children.length <= $(`#${tableConfig.param[0]}-table thead th`).length - 1) {
					let td = document.createElement('td');
					$(this).append(td);
				}
				let ids = Array.from(this.children).reduce(function(acc, cur) {
					let id = $(cur).data('id');
					return id ? acc.concat(id) : acc;
				}, []);
				const allPreviews = (ids.length > 1)
					? `<a href="https://data.icos-cp.eu/dygraph-light/?objId=${ids}&x=TIMESTAMP&type=line&linking=overlap&y=${tableConfig.param[0]}">All</a>`
					: '';
				this.children.length > 2 && tableConfig.previewType != "map-graph" && tableConfig.param.length == 1 ? $(this).append(`<td>${allPreviews}</td>`) : '';
				return this;
			});
			$(`#${tableConfig.param[0]}-table tbody`).html(rows);
			$(`#${tableConfig.param[0]}-table`).show();
		})
	}
})(jQuery, Drupal, drupalSettings);
