(function($, Drupal) {
	Drupal.behaviors.dataSearchBehavior = {
		attach: function(context, settings) {
			$('.type .box-content', context).once('dataSearchBehavior').each(function() {
				$(this).click(function() {
					$('.type input').not($(this).siblings('input')).prop('checked', false);
				});
			});

			$('.type .box-input', context).once('dataSearchBehavior').each(function() {
				$(this).change(function() {
					if ($('.type input:checked').val() == '1,2') {
						$('.themes').show();
					} else {
						$('.themes').hide();
						$('.theme input').prop('checked', false);
					}
				});
			});

			$('#js-data-search-form', context).once('dataSearchBehavior').each(function() {
				$('.box-input').prop('checked', false);
				$(this).submit(function() {
					const level = $('input[name=type]:checked').val();
					const levelQuery = typeof level !== 'undefined' ? `"level":[${$('input[name=type]:checked').val()}]` : '';
					const themes = $('input[name=theme]:checked').map(function(){return this.value}).get().join('","');
					const themeQuery = level == '1,2' && themes.length ? `,"theme":["${themes}"]` : '';
					const query = typeof level !== 'undefined' ? `#{"filterCategories":{${levelQuery}${themeQuery}}}` : `#{"filterCategories":{"project":["icos"]}}`;

					$(location).attr('href', `https://data.icos-cp.eu/portal/${query}`);
					return false;
				});
			});
		}
	};
})(jQuery, Drupal);
