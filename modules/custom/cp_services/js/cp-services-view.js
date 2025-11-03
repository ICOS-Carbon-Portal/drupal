(function (Drupal, drupalSettings) {
    let filtersVisible = false;
    let resizeListenerAdded = false;

    function repositionFilters() {
        const filters = document.querySelector(".cpservices-catalogue .view-filters .views-exposed-form.bef-exposed-form .form--inline");
        const mainWrapper = document.getElementById("main-wrapper");

        function leftPos(visible) {
            let initPos = parseInt(mainWrapper.offsetLeft) * (-1) - 30;
            if (visible) {
                return initPos;
            } else {
                return initPos - parseInt(filters.offsetWidth);
            }
        }

        filters.style.left = leftPos(filtersVisible) + "px";
    }

    Drupal.behaviors.servicesViewRefresh = {
        attach: function(context, settings) {
            const filters = context.querySelector(".cpservices-catalogue .view-filters .views-exposed-form.bef-exposed-form .form--inline");

            let filtersButton = document.createElement("button");
            filtersButton.innerText = "Show/Hide Filters";
            filtersButton.type = "button";
            filtersButton.classList.add("btn", "btn-secondary", "mb-2");

            const mainWrapper = document.getElementById("main-wrapper");

            function leftPos(visible) {
                let initPos = parseInt(mainWrapper.offsetLeft) * (-1) - 30;
                if (visible) {
                    return initPos;
                } else {
                    return initPos - parseInt(filters.offsetWidth);
                }
            }

            filters.parentNode.insertBefore(filtersButton, filters);

            if (filtersVisible) {
                filters.dataset["visible"] = "true";
                filters.classList.add("displayed");
                filters.style.left = leftPos(true) + "px";
            } else {
                filters.style.left = leftPos(false) + "px";
            }

            filtersButton.addEventListener("click", () => {
                console.log("click")
                if (filters.dataset["visible"] == "true") {
                    filters.dataset["visible"] = "false";
                    filters.style.left = leftPos(false) + "px";
                    filters.classList.remove("displayed");
                    filtersVisible = false;
                } else {
                    filters.dataset["visible"] = "true";
                    filters.style.left = leftPos(true) + "px";
                    filters.classList.add("displayed");
                    filtersVisible = true;
                }
            });

            if (!resizeListenerAdded) {
                resizeListenerAdded = true;
                window.addEventListener("resize", repositionFilters);
            }
        }
    }
})(Drupal, drupalSettings);
