(function ($) {
 'use strict';
 Drupal.behaviors.cp_statistics = {
  attach: function(context, settings) {
	  cp_statistics_set_data_url(settings.data_url);
  }
 };
}(jQuery));

var cp_statistics_data = [];
var cp_statistics_data_url = '';

function cp_statistics_set_data_url(data_url) {
	cp_statistics_data_url = data_url;	
}

function cp_statistics_get_years() {
	if (! cp_statistics_data_url) { console.log('None data provider. Nothing to do.'); return false;}
	
	var years = [];

	jQuery.ajax({
	    url: cp_statistics_data_url + "/_aggrs/getYears?pagesize=100&np",
	    type: 'get',
	    async: false
	}).done(function(res) {
		years = res;
	});
	
	return years;
}

function cp_statistics_get_unique_ip_per_year(year, page) {
	if (! cp_statistics_data_url) { console.log('None data provider. Nothing to do.'); return false;}
	
	var uniqueIP = [];
	
	jQuery.ajax({
	    url: cp_statistics_data_url + "/_aggrs/getUniqueIpPerYear?avars=%7B%22n%22%3A%22"+year+"%22%7D&pagesize=1000&page="+page+"&np",
	    type: 'get',
	    async: false
	}).done(function(res) {
		uniqueIP = res;
	});
	
	return uniqueIP;
}

function cp_statistics_get_unique_ip_per_year_and_month(year, month, page) {
	if (! cp_statistics_data_url) { console.log('None data provider. Nothing to do.'); return false;}
	
	var uniqueIP = [];
	
	jQuery.ajax({
	    url: cp_statistics_data_url + "/_aggrs/getUniqueIpPerYearAndMonth"+month+"?avars=%7B%22n%22%3A%22"+year+"%22%7D&pagesize=1000&page="+page+"&np",
	    type: 'get',
	    async: false
	}).done(function(res) {
		uniqueIP = res;
	});
	
	return uniqueIP;
}

function cp_statistics_build() {
	
	jQuery('#cp_statistics').append('<p>Unique visitors</p>');
	
	var years = cp_statistics_get_years();
	
    jQuery.each(years._embedded["rh:result"], function(key, val) {
    	
    	var stat = cp_statistics_get_unique_ip_per_year(val.year, 1);
    	
    	var year = '<div><span class="year">' + val.year + '</span><span class="uniqueip">' + stat._size + '</span></div>';
    	jQuery('#cp_statistics').append(year);
    	
    	jQuery.each(['01','02','03','04','05','06','07','08','09','10','11','12'], function(key2, val2) {
    		var stat2 = cp_statistics_get_unique_ip_per_year_and_month(val.year, val2, 1);
    		
    		var uniqueip = '<div><span class="month">' + val2 + '</span><span class="uniqueip">' + stat2._size + '</span></div>'
    		jQuery('#cp_statistics').append(uniqueip);
    	});
    	
    });
}

function cp_statistics_collect_data(param) {
	if (! cp_statistics_data_url) { console.log('None data provider. Nothing to do.'); return false;}
	
	var data = 0;
	var req = '';	
	if (param) { req += '?' + param; }
	
	jQuery.ajax({
	    url: cp_statistics_data_url + req,
	    type: 'get',
	    async: false
	}).done(function(res) {
	  data = res;
	});
	
	return data;
}

jQuery('#cp_statistics').append('<div class="cp_body_content"><div class="button_box"><a href="javascript:void(0)" id="cp_statistics_start_build">Load</a></div></div>');
jQuery('#cp_statistics_start_build').click(function() {
	cp_statistics_build();
	jQuery('.cp_body_content').hide();
	
	jQuery('#cp_statistics .year').css({'font-size':'2rem'});
	jQuery('#cp_statistics .uniqueip').css({'font-size':'1.5rem', 'paddingLeft':'4rem'});
	jQuery('#cp_statistics .month').css({'font-size':'1.5rem'});
	
});
