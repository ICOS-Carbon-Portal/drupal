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

        const selectAllBtn = document.getElementById('selectAllBtn');
        if (selectAllBtn && clonedCheckboxes.every(cb => cb.checked)) {
            selectAllBtn.innerText = 'Select none';
        } else {
            selectAllBtn.innerText = 'Select all';
        }
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
            checkbox.setAttribute('form', viewsFormId);
            checkbox.classList.add('btn-check');
            checkbox.setAttribute('autocomplete', 'off');
            const label = clone.querySelector('label[for="' + checkbox.id + '"]');
            if (label) {
                label.classList.add('btn', 'btn-outline-primary');
            }
        }

        const selectAllBtn = document.createElement('button');
        selectAllBtn.id = 'selectAllBtn';
        selectAllBtn.type = 'button';
        selectAllBtn.classList.add('btn', 'btn-outline-secondary', 'mb-3', 'ms-4');
        selectAllBtn.textContent = 'Select all';
        clone.querySelector(".form-checkboxes").appendChild(selectAllBtn);

        selectAllBtn.addEventListener('click', () => {
            const checkboxes = Array.from(document.querySelectorAll(groupContainerSelector + ' input[type="checkbox"]'));

            const newState = !(checkboxes.every(cb => cb.checked));
            const wasHidden = isViewBlockHidden();

            for (const cb of checkboxes) {
                cb.checked = newState;
            }

            updateBlockVisibility();

            syncInternalCheckboxes(wasHidden);
        });

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

    Drupal.behaviors.communityHubView = {
        attach: function (context, settings) {
            once('commhub-disable-views-scroll', 'html', document).forEach(() => {
                Drupal.AjaxCommands.prototype.scrollTop = function () {};
            });

            once('commhub-clone-groups', 'body', document).forEach(() => cloneUserGroupsFilter());

            updateBlockVisibility();
            document.querySelector(viewBlockSelector + ' .view-content')?.classList.remove('invisible');

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
