/**
 * @file
 * Global utilities.
 *
 */
(function (Drupal) {
  'use strict';

  Drupal.behaviors.icos_2026 = {
    attach: function (context, settings) {
      once('icos_2026', '#icos_2026_menu .top-node .dd-toggle').forEach(function (el) {
        el.addEventListener('click', function (event) {
          let target = el.closest(".top-node");
          target.classList.toggle("open");
          event.preventDefault();
        });
        el.addEventListener('keyup', function (event) {
          if(event.key === "Enter") {
            let target = el.closest(".top-node");
            target.classList.toggle("open");
            event.preventDefault();
            }
        });
      });
      once('icos_2026', '#icos_2026_menu').forEach(function (el) {
        el.addEventListener('mouseout', function (event) {
          if (event.target === document.activeElement) {
            event.target.blur();
          }
        });
      });
    },
  };
})(Drupal);
