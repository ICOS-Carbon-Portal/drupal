(function (Drupal, drupalSettings) {
    Drupal.behaviors.searchBehavior = { attach: function (context, settings) {
        const root = context.querySelector("#search-results:not([data-cp-search-processed])");
        if (root) {
            // from Drupal settings for module:
            const indexName = drupalSettings.cpSearch.website + "-main";
            const typesenseApiKey = drupalSettings.cpSearch.apiKey;

            root.setAttribute('data-cp-search-processed', 'true');

            const searchboxDiv = context.getElementById("searchbox");
            const hitsDiv = context.getElementById("hits");
            const paginationDiv = context.getElementById("pagination");

            const typesenseInstantsearchAdapter = new TypesenseInstantSearchAdapter({
                server: {
                    apiKey: typesenseApiKey,
                    nodes: [
                        {
                            host: 'typesense.icos-cp.eu',
                            protocol: 'https',
                        },
                    ],
                },
                additionalSearchParameters: {
                    query_by: 'title,content,url',
                    highlight_affix_num_tokens: 15
                },
            });

            const searchClient = typesenseInstantsearchAdapter.searchClient;

            const search = instantsearch({
                searchClient,
                indexName,
                searchFunction(helper) {
                    if (helper.state.query.trim() === '') {
                        // Hide results when query is empty
                        document.querySelectorAll(".hide-on-empty-query").forEach((element) => element.style.display = 'none');
                    } else {
                        document.querySelectorAll(".hide-on-empty-query").forEach((element) => element.style.display = '');
                        helper.search();
                    }
                }
            });

            const paginationPanel = instantsearch.widgets.panel({
                hidden: ({ results }) => results.nbPages <= 1,
            })(instantsearch.widgets.pagination);
            
            const hitsPanel = instantsearch.widgets.panel({
                hidden: () => false,
            })(instantsearch.widgets.hits);

            search.addWidgets([
                instantsearch.widgets.searchBox({
                  container: searchboxDiv,
                  showSubmit: true,
                  showReset: false,
                  cssClasses: {
                    form: "input-group",
                    input: ["form-search", "form-control"],
                    submit: ["btn", "btn-light", "border"],
                    submitIcon: ["fas", "fa-search"]
                  },
                  templates: {
                    submit({ cssClasses }, { html }) {
                        return html`<i class="${cssClasses.submitIcon}"></i>`;
                    },
                  }
                }),
                paginationPanel({
                  container: paginationDiv
                }),
                hitsPanel({
                    container: hitsDiv,
                    escapeHTML: false,
                    /*transformItems(items) {
                        console.log(items);
                        return items.map(item => ({
                            ...item
                        }));
                    },*/
                    templates: {
                        item(item) {
                            return `<div class="search-results-hit">
                                <h4 class="h5"><a href="${item.url}">${item.title}</a></h4>
                                <p>${ item.content.split(/[ \n]/).length > 30
                                    ? item.content.split(/[ \n]/)
                                        .flatMap((x, idx) => idx < 30 ? [x] : [])
                                        .join(" ") + "&hellip;"
                                    : item.content
                                }</p>
                    </div>`
                      },
                    },
                }),
            ]);

            search.start();

            // If page is loaded with get parameter q="...", use query to start search
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has("q")) {
                search.setUiState({
                    [indexName]: {
                        query: urlParams.get("q")
                    }
                });
            }
        }
    }};
})(Drupal, drupalSettings);
