(function ($) {
 'use strict';
 Drupal.behaviors.cp_statistics = {
  attach: function(context, settings) {
	  cp_statistics_set_data_url(settings.data_url);
  }
 };
}(jQuery));

var cp_statistics_data_url = '';

function cp_statistics_set_data_url(data_url) {
	cp_statistics_data_url = data_url;
	
}

function cp_statistics_data(param) {
	if (! cp_statistics_data_url) { console.log('None data provider. Nothing to do.'); return false;}
	
	var data = 'empty';
	var req = '';
	
	if (param) { req = '?' + param; }
	
	jQuery.ajax({
	    url: cp_statistics_data_url + req,
	    type: 'get',
	    async: false
	}).done(function(res) {
	   data = res;
	});
	
	return data;
}





