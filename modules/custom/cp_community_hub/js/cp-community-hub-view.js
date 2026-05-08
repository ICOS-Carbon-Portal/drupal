(function (Drupal, once) {

    const viewBlockSelector = '.block-views-blockcpcommhub-resources-block-1';
    const introBlockSelector = '.block-inline-blockbasic';
    const groupContainerSelector = '#user-groups-container';
    const filterGroupsSelector = '[data-drupal-selector="edit-user-groups-target-id"]';
    const viewsFormId = 'views-exposed-form-cpcommhub-resources-block-1';

    function moveUserGroupsFilter() {
        const groupContainer = document.querySelector(groupContainerSelector);
        const filterWrapper = document.querySelector(filterGroupsSelector)?.closest('.form-item, .js-form-item, fieldset, details');

        if (!groupContainer || !filterWrapper) {
            return;
        }

        for (let checkbox of filterWrapper.querySelectorAll('input[type="checkbox"]')) {
            checkbox.setAttribute('form', viewsFormId);
            checkbox.classList.add('btn-check');
            checkbox.setAttribute('autocomplete', 'off');
            checkbox.closest('.form-check')?.classList.add('d-inline');
            const label = filterWrapper.querySelector('label[for="' + checkbox.id + '"]');
            if (label) {
                label.classList.add('btn', 'btn-outline-primary');
            }
        }

        groupContainer.appendChild(filterWrapper);
    }

    function hideGroupsFilterInView(context) {
        const wrapper = context.querySelector(filterGroupsSelector)?.closest('.form-item, .js-form-item, fieldset, details');
        if (wrapper && !wrapper.closest(groupContainerSelector)) {
            wrapper.classList.add('d-none');
        }
    }

    function hasGroupSelected() {
        return document.querySelectorAll(groupContainerSelector + ' input[type="checkbox"]:checked').length > 0;
    }

    function updateBlockVisibility() {
        const viewBlock  = document.querySelector(viewBlockSelector);
        const introBlock = document.querySelector(introBlockSelector);

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
            once('commhub-move-groups', 'body', document).forEach(() => moveUserGroupsFilter());

            hideGroupsFilterInView(context);

            updateBlockVisibility();

            once('commhub-group-listeners', groupContainerSelector, document).forEach((container) => {
                container.addEventListener('change', (e) => {
                    if (e.target.matches('input[type="checkbox"]')) {
                        updateBlockVisibility();
                    }
                });
            });
        }
    };

})(Drupal, once);
