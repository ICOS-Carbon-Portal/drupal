/**
 * @file
 * Global utilities.
 *
 */
(function (Drupal) {
  'use strict';

  Drupal.behaviors.cp_theme_d10 = {
    attach: function (context, settings) {
      once('cp_theme_d10', '#cp_theme_d10_menu .top-node .drop-down-toggle').forEach(function (el) {
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
      once('cp_theme_d10', '#cp_theme_d10_menu').forEach(function (el) {
        el.addEventListener('mouseout', function (event) {
          if (event.target === document.activeElement) {
            event.target.blur();
          }
        });
      });
    },
  };
})(Drupal);
