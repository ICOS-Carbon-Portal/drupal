(function ($) {
 'use strict';
 Drupal.behaviors.cp_statistics = {
  attach: function(context, settings) {
	  $(context).find('#cp_statistics_view').once('load').each(function () {
		  console.log(settings.year);
		  console.log(settings.month);
		  load_cp_statistics_data(settings.year, settings.month)
	  }); 
  }
 };

}(jQuery));

function load_cp_statistics_data(year, month) {
	jQuery('#cp_statistics_view').empty();
	
	var element = '<div id="unique_visitors"></div>'
		+ '<div id="pages"></div>';

	jQuery('#cp_statistics_view').append(element);
	
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
	
	jQuery.getJSON('/cp_statistics/get_data/uniquevisitors/' + year + '/' + month, function(result) {
		console.log(result);
		console.log(Object.keys(result)[0]);

		var element = '<h5><span class="number">' + result.unique_visitors.total.number + '</span> unique visitors <span class="small_text">based on the pages below</span></h5>';
		jQuery('#unique_visitors').append(element);
	});
	
	jQuery.getJSON('/cp_statistics/get_data/numbersofuniquevisitorsperallpage/' + year + '/' + month, function(result) {
		console.log(result);
		
	    jQuery.each(result.pages, function(i, e) {
	    	var oddEven = 'odd';
	    	if (i % 2 == 0) { oddEven = 'even'; }
	    	
	    	var element = '<div class="page ' + oddEven + '">' + e.page + '&nbsp;&nbsp;&nbsp;&nbsp;(' + e.unique_visits + ')</div>';
	    	jQuery('#pages').append(element);
	    });
	    
		spinner.stop();
	});		
}
