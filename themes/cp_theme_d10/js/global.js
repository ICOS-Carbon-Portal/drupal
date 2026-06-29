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
      if (!menuBackdrop) {
        return;
      }

      let committedTopNode = null;
      let dwellTimer = null;
      let closeTimer = null;
      let switchTimer = null;
      const DWELL_TIME = 200;
      const CLOSE_GRACE = 100;
      const SWITCH_DELAY = 160;

      function revealDropdown(topNode) {
        if (committedTopNode !== topNode) { return; }
        const dropdown = topNode.querySelector(':scope > .dropdown-menu');
        const currentHeight = parseInt(menuBackdrop.style.height, 10) || 0;
        const dropdownHeight = dropdown ? dropdown.offsetHeight : topNode.offsetHeight;
        menuBackdrop.style.height = dropdownHeight + 'px';
        if (dropdown) {
          if (currentHeight > 0) {
            const startClipPct = dropdownHeight > currentHeight
              ? (100 * (dropdownHeight - currentHeight) / dropdownHeight).toFixed(2) + '%'
              : '0%';
            dropdown.style.transition = 'opacity 0.2s ease-in-out, transform 0.2s ease-in-out';
            dropdown.style.clipPath = `inset(0 0 ${startClipPct} 0)`;
            dropdown.style.visibility = 'visible';
            dropdown.style.opacity = '1';
            dropdown.style.transform = 'translateY(0)';
            dropdown.getBoundingClientRect();
            dropdown.style.transition = '';
          } else {
            dropdown.style.visibility = 'visible';
            dropdown.style.opacity = '1';
            dropdown.style.transform = 'translateY(0)';
          }
          dropdown.style.clipPath = 'inset(0 0 0 0)';
        }
      }

      function showDropdown(topNode) {
        clearTimeout(closeTimer);
        clearTimeout(switchTimer);
        const prev = committedTopNode;
        committedTopNode = topNode;
        if (prev && prev !== topNode) {
          hideDropdown(prev, true);
          switchTimer = setTimeout(function () { revealDropdown(topNode); }, SWITCH_DELAY);
        } else {
          revealDropdown(topNode);
        }
      }

      function hideDropdown(topNode, isSwitch) {
        const dropdown = topNode.querySelector(':scope > .dropdown-menu');
        if (!dropdown) { return; }
        dropdown.style.opacity = '';
        dropdown.style.transform = '';
        if (!isSwitch) {
          dropdown.style.clipPath = '';
        }
        setTimeout(function () {
          dropdown.style.visibility = '';
          dropdown.style.clipPath = '';
        }, 200);
      }

      function closeMenu() {
        if (committedTopNode) {
          hideDropdown(committedTopNode);
          committedTopNode = null;
        }
        menuBackdrop.style.height = '0px';
      }

      window.addEventListener('resize', function () {
        if (committedTopNode) {
          revealDropdown(committedTopNode);
        }
      });

      once('cp_theme_d10_hover', '#cp_theme_d10_menu .top-node').forEach(function (el) {
        el.addEventListener('mouseenter', function () {
          if (window.innerWidth < 992) {
            return;
          }
          clearTimeout(closeTimer);
          dwellTimer = setTimeout(function () { showDropdown(el); }, DWELL_TIME);
        });

        el.addEventListener('mouseleave', function () {
          if (window.innerWidth < 992) {
            return;
          }
          clearTimeout(dwellTimer);
          closeTimer = setTimeout(closeMenu, CLOSE_GRACE);
        });

        el.addEventListener('focusin', function () {
          if (window.innerWidth < 992) {
            return;
          }
          clearTimeout(dwellTimer);
          clearTimeout(closeTimer);
          showDropdown(el);
        });

        el.addEventListener('focusout', function (event) {
          if (window.innerWidth < 992) {
            return;
          }
          const nextTopNode = event.relatedTarget && event.relatedTarget.closest('#cp_theme_d10_menu .top-node');
          if (!el.contains(event.relatedTarget) && committedTopNode === el && !nextTopNode) {
            closeMenu();
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
