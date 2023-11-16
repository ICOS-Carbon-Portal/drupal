(function ($, Drupal) {

  var mapData = [
    {
      id: 0,
      name: 'Zeppelin Observatory',
      country: 'Norway',
      x: 24,
      y: 4,
      pictureSrc: '/sites/default/files/2017-10/icoscapes_zeppelin.jpg',
      link: '/icoscapes/zeppelin-observatory',
      text: 'A very remote station high up in the Arctic, far away from the major pollution sources. Up in 474 m one can see the differences in shrinking glaciers and the melting permafrost...',
      active: 1,
    },

    {
      id: 1,
      name: 'Zackenberg Fen',
      country: 'Denmark',
      x: 3,
      y: 10,
      pictureSrc: '/sites/default/files/2017-08/icoscapes_zackenberg_fen_station.jpg',
      link: '/icoscapes/zackenberg-fen',
      text: 'As part of the world\'s largest national park in the remote Greenland, the northernmost ICOS ecosystem station has witnessed frequent visits of polar foxes, musk oxen and polar bears...',
      active: 1,
    },

    {
      id: 2,
      name: 'Pallas Sammaltunturi',
      country: 'Finland',
      x: 33,
      y: 30,
      pictureSrc: '/sites/default/files/2018-02/icoscapes_map_pallas.jpg',
      link: '/icoscapes/pallas',
      text: 'Located above the Arctic Circle in Finnish Lapland, this station witnesses polar nights in winter and 24 hours of sunshine in summer...',
      active: 1,
    },

    {
      id: 3,
      name: 'Svartberget',
      country: 'Sweden',
      x: 27,
      y: 44,
      pictureSrc: '/sites/default/files/2018-09/ICOScapes_Svartberget_station.jpg',
      link: '/icoscapes/svartberget',
      text: 'A combined station located in the boreal forest of Scandinavia, surrounded by 100-year-old pine trees. Boreal forests play an important role in the global greenhouse gas exchange...',
      active: 1,
    },

    {
      id: 4,
      name: 'Fendt',
      country: 'Germany',
      x: 26,
      y: 69,
      pictureSrc: '/sites/default/files/2018-09/ICOScapes_Fendt_station_3.jpg',
      link: '/icoscapes/fendt',
      text: 'Located in a farmland close to the Bavarian Alps, this station has witnessed the temperatures climbed up twice as much as in the rest of Germany over the last 50 years...',
      active: 1,
    },

    {
      id: 5,
      name: 'Auchencorth Moss',
      country: 'United Kingdom',
      x: 14,
      y: 60,
      pictureSrc: '/sites/default/files/2017-11/icoscapes_auchencorth_moss.jpg',
      link: '/icoscapes/auchencorth-moss',
      text: 'One of few peatland stations in the world measuring carbon fluxes. Although only 20 km from Edinburgh, the station locates in the remote Scottish countryside where the wind might get too rough at times...',
      active: 1,
    },

    {
      id: 6,
      name: 'Loobos',
      country: 'The Netherlands',
      x: 22,
      y: 69,
      pictureSrc: '/sites/default/files/2018-01/Loobos_map.jpg',
      link: '/icoscapes/loobos',
      text: 'One of the world\'s longest continuously running measuring stations, located in the largest sand areas in Western Europe, now covered with pine trees...',
      active: 1,
    },

    {
      id: 7,
      name: 'Simon Stevin and VLIZ Thornton buoy',
      country: 'Belgium',
      x: 19,
      y: 65,
      pictureSrc: '/sites/default/files/2018-09/ICOScapes_simon-stevin_station.jpg',
      link: '/icoscapes/simon-stevin',
      text: 'The 36-metre long ICOS Simon Stevin research vessel and the VLIZ Thornton buoy measure greenhouse gases in the ocean...',
      active: 1,
    },

    {
      id: 8,
      name: 'Lanžhot',
      country: 'Czech Republic',
      x: 29,
      y: 73,
      pictureSrc: '/sites/default/files/2018-09/ICOScapes_czech_station.jpg',
      link: '/icoscapes/lanzhot',
      text: 'Artificial lakes built around Lanžhot have changed the water flow in the area over the years. Air temperature at Lanžhot has increased and draughts occur more often due to climate change...',
      active: 1,
    },

    {
      id: 9,
      name: 'Saclay',
      country: 'France',
      x: 19,
      y: 75,
      pictureSrc: '/sites/default/files/2018-09/ICOScapes_Saclay_station.jpg',
      link: '/icoscapes/saclay',
      text: 'Located between Paris and a more rural area of Île-de-France, this station is important in understanding urban emissions...',
      active: 1,
    },

    {
      id: 10,
      name: 'Jungfraujoch',
      country: 'Switzerland',
      x: 24,
      y: 77,
      pictureSrc: '/sites/default/files/2018-05/ICOScapes_map_switzerland.jpg',
      link: '/icoscapes/jungfraujoch',
      text: 'This station in the High Alps boasts the highest railway station in Europe. It is also the highest measurement site within the ICOS network, located at 3500 metres...',
      active: 1,
    },

    {
      id: 11,
      name: 'Castelporziano 2',
      country: 'Italy',
      x: 26.8,
      y: 85,
      pictureSrc: '/sites/default/files/2017-08/icoscapes_castelporziano_station.jpg',
      link: '/icoscapes/castelporziano',
      text: 'Being a restricted area close to the Mediterranean Sea, this historical forest has a high environmental value due to the habitat biodiversity and richness of species...',
      active: 1,
    },
  ];

  function loadMap() {
      $(mapData).each(function(k, v) {
        var pX = v.x;
        var pY = v.y;

        var action = '';
        var style = '';
        if (v.active == 1) {
          action = 'viewInfoBox(' + v.id + ')';
          style = 'position:absolute; left:' + pX + '%; top:' + pY + '%; width:3%; height:6%; z-index:100; cursor:pointer;';
        }

        $('.map').append('<div class="selector" style="' + style + '" data-id="' + v.id + '"></div>');
      });

      $('.map').on('click', '.selector', function(event) {
        viewInfoBox($(event.target).data('id'));
      });

      $('.map').on('click', '.icoscapes-infobox-close', function(event) {
        closeInfoBox();
      });
  }

  function viewInfoBox(objId) {
    closeInfoBox();

    var element = `
    <div id="js-icoscapes-infobox" class="icoscapes-infobox">
      <div class="icoscapes-infobox-close"></div>
      <div class="icoscapes-infobox-image" style="background-image: url('${mapData[objId].pictureSrc}')"></div>
      <div class="icoscapes-infobox-text">
        <p style="font-size:1.4em; font-weight:bold">${mapData[objId].country}</p>
        <p style="font-size:1.4em; padding:0;">${mapData[objId].name}</p>
        <hr/>
        <p style="">${mapData[objId].text}</p>
        <div class="button_box"><a href="${mapData[objId].link}">Visit website</a></div>
      </div>
    </div>`;

    $('.map').append(element);
    $('body').addClass('no-scroll');
  }

  function closeInfoBox() {
    $('#js-icoscapes-infobox').remove();
    $('body').removeClass('no-scroll');
  }

  Drupal.behaviors.icoscapesBehavior = {
    attach: function (context) {
      once('icoscapesBehavior', 'body', context).forEach(function () {
        loadMap();
      });
    }
  }
})(jQuery, Drupal);
