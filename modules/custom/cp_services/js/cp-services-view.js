(function (Drupal, drupalSettings) {
    function makeQuery(id) {
        return '[data-drupal-selector="' + id + '"]';
    }

    function isFilterOpen(id) {
        return document.querySelector(makeQuery(id)).open;
    }
    
    let filters = [
        {id: "edit-access-rights-value-collapsible", open: isFilterOpen("edit-access-rights-value-collapsible")},
        {id: "edit-group-value-collapsible", open: isFilterOpen("edit-group-value-collapsible")},
        {id: "edit-main-users-value-collapsible", open: isFilterOpen("edit-main-users-value-collapsible")}
    ];

    function toggleFilter(e) {
        filters = filters.map((filter) => filter.id === e.target.dataset["drupalSelector"] ? {id: filter.id, open: e.target.open} : filter);
    }

    Drupal.behaviors.servicesViewRefresh = {
        attach: function(context, settings) {
            for(let filter of filters) {
                // open filters that were previously open
                if (filter.open) {
                    context.querySelector(makeQuery(filter.id)).open = true;
                }
                // listen for "toggle" event, maintain external state
                context.querySelector(makeQuery(filter.id)).addEventListener("toggle", toggleFilter);
            }
        }
    }
})(Drupal, drupalSettings);
