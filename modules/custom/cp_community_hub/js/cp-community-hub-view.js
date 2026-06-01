(function (Drupal, once) {

    const viewBlockSelector = '.block-views-blockcp-community-hub-block-1';
    const introBlockSelector = '.block-field-blocknodepagefield-introduction';
    const inlineBlockSelector = '.block-inline-blockbasic';
    const groupContainerSelector = '#user-groups-container';
    const filterGroupsSelector = '[data-drupal-selector="edit-user-groups-target-id"]';
    const viewsFormId = 'views-exposed-form-cp-community-hub-block-1';

    function isViewBlockHidden() {
        const viewBlock = document.querySelector(viewBlockSelector);
        return viewBlock ? !viewBlock.classList.contains('d-block') : false;
    }

    // Mirrors all cloned checkbox states into the hidden Views form, then fires a
    // single change event on the last matched checkbox to trigger Views AJAX.
    function syncInternalCheckboxes(wasHidden) {
        const form = document.getElementById(viewsFormId);
        if (!form) {
            return;
        }
        const clonedCheckboxes = Array.from(document.querySelectorAll(groupContainerSelector + ' input[type="checkbox"]'));
        const formCheckboxes = Array.from(form.querySelectorAll('input[type="checkbox"]'));
        let lastInternalCb;
        for (let cb of clonedCheckboxes) {
            const internalCb = formCheckboxes.find(icb => icb.name === cb.name);
            if (internalCb) {
                internalCb.checked = cb.checked;
                lastInternalCb = internalCb;
            }
        }

        if (wasHidden) {
            document.querySelector(viewBlockSelector + ' .view-content')?.classList.add('invisible');
        }

        lastInternalCb.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function cloneUserGroupsFilter() {
        const groupContainer = document.querySelector(groupContainerSelector);
        const filterEl = document.querySelector(filterGroupsSelector);
        const filterWrapper = filterEl?.closest('.form-item, .js-form-item, fieldset, details');

        if (!groupContainer || !filterWrapper) {
            return;
        }

        for (const cb of filterWrapper.querySelectorAll('input[type="checkbox"]')) {
            cb.checked = false;
        }

        const clone = filterWrapper.cloneNode(true);
        for (let checkbox of clone.querySelectorAll('input[type="checkbox"]')) {
            const originalId = checkbox.id;
            const newId = 'top-' + originalId;
            checkbox.id = newId;
            checkbox.setAttribute('form', viewsFormId);
            checkbox.classList.add('btn-check');
            checkbox.setAttribute('autocomplete', 'off');
            const label = clone.querySelector('label[for="' + originalId + '"]');
            if (label) {
                label.setAttribute('for', newId);
                label.classList.add('btn', 'btn-outline-primary');
            }
        }

        groupContainer.appendChild(clone);
        filterWrapper.classList.add('d-none');
    }

    function hasGroupSelected() {
        return document.querySelectorAll(groupContainerSelector + ' input[type="checkbox"]:checked').length > 0;
    }

    function updateBlockVisibility() {
        const viewBlock  = document.querySelector(viewBlockSelector);
        const introBlock  = document.querySelector(introBlockSelector);
        const inlineBlock = document.querySelector(inlineBlockSelector);

        if (!viewBlock || !introBlock || !inlineBlock) {
            console.error("Unable to find all blocks for updating visibility")
            return;
        }

        if (hasGroupSelected()) {
            viewBlock.classList.add('d-block');
            introBlock.classList.add('d-block');
            inlineBlock.classList.add('d-none');
        } else {
            viewBlock.classList.remove('d-block');
            introBlock.classList.remove('d-block');
            inlineBlock.classList.remove('d-none');
        }
    }

    function addSelectAllToEmpty() {
        const viewEmpty = document.querySelector(viewBlockSelector + ' .view-empty');
        const checkboxes = Array.from(document.querySelectorAll(groupContainerSelector + ' input[type="checkbox"]'));
        if (!viewEmpty || viewEmpty.querySelector('.select-all-link') || checkboxes.every(cb => cb.checked)) {
            return;
        }
        const link = document.createElement('a');
        link.href = '#';
        link.classList.add('select-all-link');
        link.textContent = 'Expand search to include all roles';
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const checkboxes = Array.from(document.querySelectorAll(groupContainerSelector + ' input[type="checkbox"]'));
            const wasHidden = isViewBlockHidden();
            for (const cb of checkboxes) {
                cb.checked = true;
            }
            updateBlockVisibility();
            syncInternalCheckboxes(wasHidden);
        });
        viewEmpty.appendChild(link);
    }

    Drupal.behaviors.communityHubView = {
        attach: function (context, settings) {
            once('commhub-disable-views-scroll', 'html', document).forEach(() => {
                Drupal.AjaxCommands.prototype.scrollTop = function () {};
            });

            once('commhub-clone-groups', 'body', document).forEach(() => cloneUserGroupsFilter());

            updateBlockVisibility();
            document.querySelector(viewBlockSelector + ' .view-content')?.classList.remove('invisible');
            addSelectAllToEmpty();

            once('commhub-group-listeners', groupContainerSelector, document).forEach((container) => {
                container.addEventListener('change', (e) => {
                    if (e.target.matches('input[type="checkbox"]')) {
                        const wasHidden = isViewBlockHidden();

                        updateBlockVisibility();

                        syncInternalCheckboxes(wasHidden);
                    }
                });
            });
        }
    };

})(Drupal, once);
