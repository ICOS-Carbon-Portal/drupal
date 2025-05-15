(function (Drupal) {
    Drupal.behaviors.myCustomBehavior = { attach: function (context, settings) {
        const root = context.querySelector("#search-results:not([data-cp-search-processed])");
        if (root) {
            root.setAttribute('data-cp-search-processed', 'true');

            /* render everything here instead of the template; this ensures everything is present when loading */
            const searchBoxDiv = document.createElement("DIV");
            searchBoxDiv.id = "searchbox";
            root.appendChild(searchBoxDiv);

            const hitsHeader = document.createElement("H2");
            hitsHeader.innerText = "Search results";
            root.appendChild(hitsHeader);

            const hitsDiv = document.createElement("DIV");
            hitsDiv.id = "hits";
            root.appendChild(hitsDiv);

            const paginationDiv = document.createElement("DIV");
            paginationDiv.id = "pagination";
            root.appendChild(paginationDiv);
            
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
                        hitsHeader.style.display = 'none';
                        hitsDiv.style.display = 'none';
                        paginationDiv.style.display = 'none';
                        return;
                    }
                    // Show results when query is not empty
                    hitsHeader.style.display = '';
                    hitsDiv.style.display = '';
                    paginationDiv.style.display = '';
                    helper.search();
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
                  container: '#searchbox'
                }),
                paginationPanel({
                  container: '#pagination'
                }),
                hitsPanel({
                    container: '#hits',
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
            
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has("q")) {
                console.log("Attempting to setUiState")
                search.setUiState({
                    basic_content: {
                        query: urlParams.get("q")
                    }
                });
            }

        }
    }};
})(Drupal);