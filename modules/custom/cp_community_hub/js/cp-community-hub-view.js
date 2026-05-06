(function (Drupal, drupalSettings) {

    function getScopeWrapper(context) {
        return context.querySelector('[data-drupal-selector="edit-scope-value"]')
            ?.closest('.form-item, .js-form-item, fieldset, details');
    }

    function hasGroupSelected(context) {
        return context.querySelectorAll(
            '[data-drupal-selector="edit-user-groups-target-id"] input[type="checkbox"]:checked'
        ).length > 0;
    }

    function updateScopeVisibility(context) {
        const wrapper = getScopeWrapper(context);
        if (!wrapper) {
            return;
        }
        if (hasGroupSelected(context)) {
            wrapper.classList.remove('cpcommhub-scope-hidden');
        } else {
            wrapper.classList.add('cpcommhub-scope-hidden');
        }
    }

    Drupal.behaviors.communityHubViewRefresh = {
        attach: function (context, settings) {
            updateScopeVisibility(context);

            context.querySelectorAll(
                '[data-drupal-selector="edit-user-groups-target-id"] input[type="checkbox"]'
            ).forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    updateScopeVisibility(context);
                });
            });
        }
    };

})(Drupal, drupalSettings);
