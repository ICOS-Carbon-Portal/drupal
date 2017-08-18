(function ($) {
 'use strict';
 Drupal.behaviors.cp_datastat = {
  attach: function(context, settings) {
	  
  }
 };
}(jQuery))

function cp_datastat_get_data() {
	var url = 'https://restheart.icos-cp.eu/db/dobjdls';
	var query = '/_aggrs/getCreatorsAndContributors';

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
	jQuery('#cp_datastat table thead').append('<tr><th>Name</th><th>Number of uploads</th></tr>');
	jQuery('#cp_datastat table').append('<tbody />');
	
	var data = cp_datastat_get_data();
	console.log(data);
    jQuery.each(data._embedded, function(key, val) {  	
    	jQuery('#cp_datastat table tbody').append('<tr><td>' + val._id  + '</td><td>' + val.count + '</td></tr>');	
    });
 
}

cp_datastat_build();