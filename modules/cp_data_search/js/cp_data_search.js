(function($, Drupal) {
  Drupal.behaviors.dataSearchBehavior = {
    attach: function(context, settings) {
      $('.box-content', context).once('dataSearchBehavior').each(function() {
				$(this).click(function() {
					$(this).siblings('input').click();

					if ($('input[name=type]:checked').val() == '2') {
						$('.themes').show();
					} else {
						$('.themes').hide();
					}
				});
      });

      $('#js-data-search-form', context).once('dataSearchBehavior').each(function() {
        $('.box-input').prop('checked', false);
        $(this).submit(function() {
          const level = $('input[name=type]:checked').val();
          const levelQuery = typeof level !== 'undefined' ? `?level=%5B${$('input[name=type]:checked').val()}%5D` : '';
          const theme = $('input[name=theme]:checked').val();
          const themeQuery = level == '2' && typeof theme !== 'undefined' ? `&theme=%5B%22${$('input[name=theme]:checked').val()}%22%5D` : '';

          $(location).attr('href', `https://data.icos-cp.eu/portal/#search${levelQuery}${themeQuery}`);
          return false;
        });
      });
    }
  };
})(jQuery, Drupal);
