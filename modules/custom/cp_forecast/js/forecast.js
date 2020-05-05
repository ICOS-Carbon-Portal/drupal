(function ($, Drupal) {
  Drupal.behaviors.forecastBehavior = {
    attach: function (context) {
      $('body', context).once('forecastBehavior').each(function () {
        getStations();
      });
    }
  }

  function getStations() {
    const apiBaseUrl = 'https://flexextract.icos-cp.eu/meteo/latest';
    $.ajax({
      url: `${apiBaseUrl}/mpg/`,
    }).done(function(files) {
      const fileOptions = files.map((file) => {
        return `<option value="${file.name}">${file.name.replace(/\.[^/.]+$/, "")}</option>`;
      });
      $('#forecast-select').html(fileOptions);
      $('#forecast-select').change(function() {
        $('#video-player').attr('src', `${apiBaseUrl}/mpg/${$(this).val()}`);
        $('#video-download-button').attr('href', `${apiBaseUrl}/mpg/${$(this).val()}`);
        $('#data-download-button').attr('href', `${apiBaseUrl}/trj/TI_${$(this).val().replace(/\.[^/.]+$/, ".gz")}`);
      }).trigger('change');
    });
  }
})(jQuery, Drupal);
