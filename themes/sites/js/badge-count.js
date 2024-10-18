'use strict';

let datasetCountQuery = `
prefix cpmeta: <http://meta.icos-cp.eu/ontologies/cpmeta/>
prefix prov: <http://www.w3.org/ns/prov#>
prefix xsd: <http://www.w3.org/2001/XMLSchema#>
select (sum(?specCount) as ?count) where{
	{
		select ?station ?site ?submitter ?spec (count(?dobj) as ?specCount) where{
			?dobj cpmeta:wasSubmittedBy/prov:wasAssociatedWith ?submitter .
			?dobj cpmeta:hasObjectSpec ?spec .
			OPTIONAL {?dobj cpmeta:wasAcquiredBy/prov:wasAssociatedWith ?station }
			OPTIONAL {?dobj cpmeta:wasAcquiredBy/cpmeta:wasPerformedAt ?site }
			?dobj cpmeta:hasSizeInBytes ?size .
			FILTER NOT EXISTS {[] cpmeta:isNextVersionOf ?dobj}
		}
		group by ?spec ?submitter ?station ?site
	}
	FILTER(STRSTARTS(str(?spec), "https://meta.fieldsites.se/"))
	FILTER NOT EXISTS {?spec cpmeta:hasAssociatedProject/cpmeta:hasHideFromSearchPolicy "true"^^xsd:boolean}
}
`;

Drupal.behaviors.sites = {
	attach: function(context) {
		once('sites', '#datasets-count', context).forEach(function (badge) {
			console.log('load');
			fetch('https://meta.fieldsites.se/sparql', {
				method: 'post',
				headers: new Headers({
					'Accept': 'application/json',
					'Content-Type': 'text/plain'
				}),
				body: datasetCountQuery
			}).then(response => response.json())
				.then(json => {
					badge.textContent = json.results.bindings[0].count.value;
					badge.classList.remove('blurred');
				});
		});
	}
};
