(function ($) {
 'use strict';
 Drupal.behaviors.cp_statistics = {
  attach: function(context, settings) {  
	$(context).find('#cp-statistics-year-form').once('correct').each(function () {
		var form = $(this);
		form.detach();
		$('#cp_statistics_selection').append(form);
	});	   
  }
 };

}(jQuery));

jQuery('#edit-cp-statistics-year').change(function() {
	loadCPStatistics(jQuery(this).val());	
});

function loadCPStatistics(year) {
	
	var y = jQuery('#edit-cp-statistics-year').val();
	jQuery('#cp_statistics_title').text('The 20 most visited pages per month in ' + y);
	
	jQuery.getJSON('/cp_statistics/get_data/months/' + year + '/q', function(result) {
		if (result) {
			jQuery('#cp_statistics_view').empty();
		}

		var monthNames = {
				'01': {
					name: 'January',
				},
				'02': {
					name: 'February',
				},
				'03': {
					name: 'March'
				},
				'04': {
					name: 'April'
				},
				'05': {
					name: 'May'
				},
				'06': {
					name: 'June'
				},
				'07': {
					name: 'July'
				},
				'08': {
					name: 'August'
				},
				'09': {
					name: 'September'
				},
				'10': {
					name: 'October'
				},
				'11': {
					name: 'November'
				},
				'12': {
					name: 'December'
				}
			};			
		
		jQuery.each(result, function(i, field) {			
			jQuery(field).each(function(i, e) {
				var month = e.month;
				var element = '<div id="cp_statistics_month_' + month + '" class="month">'
						+ '<div class="inner">'
						+ '<h5 class="">' + monthNames[month].name + '&nbsp;&nbsp;&nbsp;&nbsp;<a href="/cp_statistics/review/' + year + '/' + month + '" target="_blank">Review</a></h5>'
						+ '<div class="chart"></div>'
						+ '</div>'
						+ '</div>';
				
				jQuery('#cp_statistics_view').append(element);
				
				showChart(year, month);
			});
		});
	});
}

function showChart(year, month) {
	
	var opts = {
			  lines: 13
			, length: 28
			, width: 14
			, radius: 42
			, scale: 1
			, corners: 1
			, color: '#000'
			, opacity: 0.25
			, rotate: 0
			, direction: 1
			, speed: 1
			, trail: 60
			, fps: 20
			, zIndex: 2e9
			, className: 'spinner'
			, top: '50%'
			, left: '50%'
			, shadow: false
			, hwaccel: false
			, position: 'absolute'
	};
	
	var target = document.getElementById('cp_statistics_view');
	var spinner = new Spinner(opts).spin(target);	
	
	var width = 400;
    var height = 400;
    var outerRadius = 200;
    var colors = d3.scale.category20();
	
	jQuery.getJSON('/cp_statistics/get_data/numbersofuniquevisitorsper20page/' + year + '/' + month + '', function(result) {
		var data = [];
		
		jQuery.each(result, function(i, field) {
		    var pages = [];
		    
			jQuery.each(field, function(i, item) {
				pages.push({'page': item.page, 'visits': item.unique_visits});	page
			});
			
			jQuery.each(pages, function(i, e) {
				data.push({visits: parseInt(e.visits), label: e.page});
			});
			
		});
	    
		var legend = '<div class="legend"></div>';
		var arc = '<div class="arc"></div>';
		
		jQuery('#cp_statistics_month_' + month + ' .chart').append('');
		jQuery('#cp_statistics_month_' + month + ' .chart').append(legend);
		jQuery('#cp_statistics_month_' + month + ' .chart').append(arc);
		
	    var vis = d3.select('#cp_statistics_month_' + month + ' .chart .arc')
	    	.append("svg:svg")
	    	.data([data])
	    	.attr("width", width)
	    	.attr("height", height)
	    	.append("svg:g")
	    	.attr("transform", "translate(" + outerRadius + "," + outerRadius + ")")
		
		var arc = d3.svg.arc()
			.outerRadius(outerRadius);

	    var pie = d3.layout.pie()
	    	.value(function(d) { 
	    		return d.visits; 
	    	})
	    	.sort( function(d) { 
	    		return null; 
	    	});
	
	    var arcs = vis.selectAll("g.slice")
	    	.data(pie)
	    	.enter()
	    	.append("svg:g")
	    	.attr("class", "slice");
	
	    arcs.append("svg:path")
	    	.attr("fill", function(d, i) { 
	    		return colors(i); 
	    	})
	    	.attr("d", arc);
	    
	    arcs.filter(function(d) { 
	    		return d.endAngle - d.startAngle > 0;
	    	})
	    	.append("svg:text")
	    	.attr("dy", ".35em")
	    	.attr("text-anchor", "middle")
	    	.attr("transform", function(d) {
	    		d.outerRadius = outerRadius;
	    		d.innerRadius = outerRadius/2;
	    		return "translate(" + arc.centroid(d) + ")rotate(" + angle(d) + ")";
	    	})
	    	.style("fill", "#fff")
	    	.style("font", "bold 10px arial")
	    	.text(function(d) { 
	    		return d.data.visits; 
	    	});
	    
	    function angle(d) {
	    	var a = (d.startAngle + d.endAngle) * 90 / Math.PI - 90;
	    	return a > 90 ? a - 180 : a;
	    }
	    
	    jQuery.each(data, function(i, e) {
	    	var legendEl = '<div style="background-color:'+colors(i)+'">' + e.label + '&nbsp;&nbsp;&nbsp;&nbsp;(' + e.visits + ')</div>';
	    	jQuery('#cp_statistics_month_' + month + ' .chart .legend').append(legendEl);
	    }); 
	    
	    spinner.stop();
	});
}

var y = jQuery('#edit-cp-statistics-year :first-child').val();
jQuery('#cp_statistics_selected_year').text(y);
loadCPStatistics(y);


