/**
 * @file
 * Global utilities.
 *
 */
(function (Drupal) {
  'use strict';

  Drupal.behaviors.cp_theme_d10 = {
    attach: function (context, settings) {
      /*once('cp_theme_d10', '#menu-button', context).forEach(function(button) {
        button.addEventListener('click', function() {
          document.getElementById('cp_theme_d10_menu').classList.toggle('open');
        });
      });*/

      once('cp_theme_d10', '#cp_theme_d10_menu .top-node .open-menu').forEach(function (el) {
        el.addEventListener('click', function () {
          el.parentElement.parentElement.classList.toggle('open');
        });
        console.log("added listener to ");
        console.log(el);
      });
    },
  };
})(Drupal);
