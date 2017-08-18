(function ($) {
 'use strict';
 Drupal.behaviors.cp_datastat = {
  attach: function(context, settings) {
	  
  }
 };
}(jQuery))

function cp_datastat_get_data(page) {
	var url = 'https://restheart.icos-cp.eu/db/dobjdls';
	var query = '/_aggrs/downloadCounts?pagesize=100&page='+page;

	var data = [];
	
	jQuery.ajax({
	    url: url + query,
	    type: 'get',
	    async: false
	}).done(function(res) {
		data = res;			
	});
	
	return data;
}

function cp_datastat_build() {
	
	jQuery('#cp_datastat').append('<table class="table table-striped sortable" />')
	jQuery('#cp_datastat table').append('<thead />');
	jQuery('#cp_datastat table thead').append('<tr><th>Dataset</th><th>Filename</th><th>Number of downloads</th></tr>');
	jQuery('#cp_datastat table').append('<tbody />');
	
	var page = 0;
	var total = 1;
	
	while (page < total) {
		page = page + 1;
		var data = cp_datastat_get_data(page);
		total = data['_total_pages'];
		
	    jQuery.each(data['_embedded'], function(key, val) {
	    	var name = val._id;
	    	var name = name.replace('https://meta.icos-cp.eu/objects/', '');
	    	
	    	jQuery('#cp_datastat table tbody').append('<tr><td><a href="https://hdl.handle.net/11676/' + name + '" target="_blank" >' + name  + '</a></td><td>' + val.fileName + '</td><td>' + val.count + '</td></tr>');	
	    });		
		
	}
}

cp_datastat_build();