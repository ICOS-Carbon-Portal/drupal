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
          if (window.innerWidth < 992) {
            target.classList.toggle("open");
          }
          event.preventDefault();
        });
        el.addEventListener('keyup', function (event) {
          if(event.key === "Enter") {
            let target = el.closest(".top-node");
            if (window.innerWidth < 992) {
              target.classList.toggle("open");
            }
            event.preventDefault();
          }
        });
      });
      const menuBackdrop = document.querySelector(".menu-backdrop");
      function whenEntered(topNode) {
        let height = topNode.dataset.computedHeight;
        if (!height) {
          const dropdown = topNode.querySelector(':scope > .dropdown-menu');
          height = (dropdown ? dropdown.offsetHeight : topNode.offsetHeight) + 'px';
          topNode.dataset.computedHeight = height;
        }
        menuBackdrop.style.height = height;
      }

      function whenExited(topNode) { // I might not need topNode here, but leave for now
        menuBackdrop.style.height = "0px";
      }
      once('cp_theme_d10_hover', '#cp_theme_d10_menu .top-node').forEach(function (el) {

        el.addEventListener('mouseover', function (event) {
          whenEntered(el);
        });
        el.addEventListener('focusin', function (event) {
          whenEntered(el);
        });

        el.addEventListener('mouseleave', function (event) {
          whenExited(el);
        });
        el.addEventListener('focusout', function (event) {
          if (!el.contains(event.relatedTarget)) {
            whenExited(el);
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
