(function (Drupal) {
    Drupal.behaviors.searchBehavior = { attach: function (context, settings) {
        const root = context.querySelector("#search-results:not([data-cp-search-processed])");
        if (root) {
            root.setAttribute('data-cp-search-processed', 'true');

            const searchboxDiv = context.getElementById("searchbox");
            const hitsDiv = context.getElementById("hits");
            const paginationDiv = context.getElementById("pagination");
            
            const typesenseInstantsearchAdapter = new TypesenseInstantSearchAdapter({
                server: {
                    apiKey: 'xyz',
                    nodes: [
                        {
                            host: 'localhost',
                            port: '8108',
                            protocol: 'http',
                        },
                    ],
                },
                additionalSearchParameters: {
                    query_by: 'body,description,title',
                    highlight_affix_num_tokens: 15
                },
            });
            
            const searchClient = typesenseInstantsearchAdapter.searchClient;
            const indexName = "basic_content";

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
                  container: searchboxDiv
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
                                <strong><a href="${item.url}">${item._highlightResult.title.value}</a></strong>
                                <p>${item._snippetResult.body.value.split(" ").length > 30
                                    ? item._snippetResult.body.value.split(" ")
                                    .flatMap((x, idx) => idx < 30 ? [x] : [])
                                    .join(" ")
                                    : item._snippetResult.body.value}</p>
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
                    basic_content: {
                        query: urlParams.get("q")
                    }
                });
            }
        }
    }};
})(Drupal);
