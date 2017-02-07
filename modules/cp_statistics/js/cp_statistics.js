(function ($) {
 'use strict';
 Drupal.behaviors.cp_statistics = {
  attach: function(context, settings) {  
	$(context).find('#cp-statistics-page-form').once('correct').each(function () {
		var form = $(this);
		form.detach();
		$('#cp_statistics_selection').append(form);
	});	   
  }
 };

}(jQuery));

jQuery('#edit-cp-statistics-page-year').change(function() {
	loadCPStatistics(jQuery(this).val());	
});

function loadCPStatistics(year) {
	jQuery.getJSON('/cp_statistics/get_statistics_data/months/' + year + '/q', function(result) {
		if (result) {
			jQuery('#cp_statistics_view').empty();
		}
		
		jQuery('#cp_statistics_title').html('Visit statistics for ' . year);

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
				var element = '<div id="cp_statistics_month_' + month + '" class="cp_statistics_month">'
						+ '<div class="inner">'
						+ '<h5 class="">' + monthNames[month].name + '</h5>'
						+ '<div class="numbers"></div>'
						+ '<div class="chart"></div>';
						+ '</div>'
						+ '</div>';
						
				jQuery('#cp_statistics_view').append(element);
				
				//showTotal(year, month);
				showUnique(year, month);
				showChart(year, month);
			});
		});
	});
}

function showTotal(year, month) {
	jQuery.getJSON('/cp_statistics/get_statistics_data/totalvisitors/' + year + '/' + month + '', function(result) {
		jQuery.each(result, function(i, field) {
			jQuery('#cp_statistics_month_' + month + ' .numbers').append('<p class="cp_statistics_total">Total visits: ' + field[0].total_visits + '</p>');
		});
	});
}

function showUnique(year, month) {
	jQuery.getJSON('/cp_statistics/get_statistics_data/uniquevisitors/' + year + '/' + month + '', function(result) {
		jQuery.each(result, function(i, field) {
			jQuery('#cp_statistics_month_' + month + ' .numbers').append('<p class="cp_statistics_unique">Unique visits: ' + field[0].unique_visits + '</p>');
		});
	});
}

function showChart(year, month) {
	var width = 400;
    var height = 400;
    var outerRadius = 200;
    var colors = d3.scale.category20();
	
	jQuery.getJSON('/cp_statistics/get_statistics_data/uniquevisitorsper10page/' + year + '/' + month + '', function(result) {
		var data = [];
		
		jQuery.each(result, function(i, field) {
		    var pages = [];
		    
			jQuery.each(field, function(i, item) {
				pages.push({'page': item.page, 'visits': item.unique_visits});	
			});
			
			jQuery.each(pages, function(i, e) {
				data.push({visits: parseInt(e.visits), label: e.page});
			});
			
		});
	    
		var arc = '<div class="arc"></div>';
		var legend = '<div class="legend"></div>';
		
		jQuery('#cp_statistics_month_' + month + ' .chart').append('<h6 class="chart_title">The ten most visited pages</h6>');
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
	    	console.log(colors(i));
	    	var legendEl = '<div style="background-color:'+colors(i)+'">' + e.label + ' (' + e.visits + ')</div>';
	    	jQuery('#cp_statistics_month_' + month + ' .chart .legend').append(legendEl);
	    });  
	});
}

var y = jQuery('#edit-cp-statistics-page-year :first-child').val();
jQuery('#cp_statistics_selected_year').text(y);
loadCPStatistics(y);


