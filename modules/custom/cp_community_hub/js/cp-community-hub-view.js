(function (Drupal, once) {

    var VIEW_BLOCK      = '.block-views-blockcpcommhub-resources-block-1';
    var INTRO_BLOCK     = '.block-inline-blockbasic';
    var GROUP_CONTAINER = '#user-groups-container';
    var GROUP_SELECTOR  = '[data-drupal-selector="edit-user-groups-target-id"]';
    var FORM_ID         = 'views-exposed-form-cpcommhub-resources-block-1';

    function moveUserGroupsFilter() {
        var groupContainer = document.querySelector(GROUP_CONTAINER);
        if (!groupContainer) {
            return;
        }
        var filterWrapper = document.querySelector(GROUP_SELECTOR)
            ?.closest('.form-item, .js-form-item, fieldset, details');
        if (!filterWrapper) {
            return;
        }
        filterWrapper.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.setAttribute('form', FORM_ID);
        });
        groupContainer.appendChild(filterWrapper);
    }

    function hideInViewGroupsFilter(context) {
        var wrapper = context.querySelector(GROUP_SELECTOR)
            ?.closest('.form-item, .js-form-item, fieldset, details');
        if (wrapper && !wrapper.closest(GROUP_CONTAINER)) {
            wrapper.classList.add('d-none');
        }
    }

    function hasGroupSelected() {
        return document.querySelectorAll(
            GROUP_CONTAINER + ' input[type="checkbox"]:checked'
        ).length > 0;
    }

    function updateBlockVisibility() {
        var viewBlock  = document.querySelector(VIEW_BLOCK);
        var introBlock = document.querySelector(INTRO_BLOCK);
        if (!viewBlock || !introBlock) {
            return;
        }
        if (hasGroupSelected()) {
            viewBlock.classList.add('d-block');
            introBlock.classList.add('d-none');
        } else {
            viewBlock.classList.remove('d-block');
            introBlock.classList.remove('d-none');
        }
    }

    Drupal.behaviors.communityHubView = {
        attach: function (context, settings) {
            once('commhub-move-groups', 'body', document).forEach(function () {
                moveUserGroupsFilter();
            });

            hideInViewGroupsFilter(context);

            updateBlockVisibility();

            once('commhub-group-listeners', GROUP_CONTAINER, document)
                .forEach(function (container) {
                    container.addEventListener('change', function (e) {
                        if (e.target.matches('input[type="checkbox"]')) {
                            updateBlockVisibility();
                        }
                    });
                });
        }
    };

})(Drupal, once);
