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
                    highlight_full_fields: 'content'
                },
            });

            const searchClient = typesenseInstantsearchAdapter.searchClient;

            const search = instantsearch({
                searchClient,
                indexName,
                searchFunction(helper) {
                    let newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?q=' + encodeURIComponent(helper.state.query.trim());

                    if (helper.state.query.trim() === '') {
                        newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                        // Hide results when query is empty
                        document.querySelectorAll(".hide-on-empty-query").forEach((element) => element.style.display = 'none');
                    } else {
                        document.querySelectorAll(".hide-on-empty-query").forEach((element) => element.style.display = '');
                        helper.search();
                    }
                    window.history.replaceState({path:newurl},'',newurl);
                }
            });

            const paginationPanel = instantsearch.widgets.panel({
                hidden: ({ results }) => results.nbPages <= 1,
            })(instantsearch.widgets.pagination);
            
            const hitsPanel = instantsearch.widgets.panel({
                hidden: () => false,
            })(instantsearch.widgets.hits);

            function processHighlightedContent(content) {
                const contentWords = content.split(/[ \n]/);
                const markedIndex = contentWords.findIndex((word) => word.startsWith("<mark>"));
                const wordsOnSide = 20;

                if (contentWords.length < (wordsOnSide*2)) {
                    return contentWords.join(" ").replaceAll(/<\/?mark>/g, "");
                }

                if (markedIndex === -1) {
                    return contentWords.slice(0, wordsOnSide*2).join(" ") + "&hellip;";
                }

                let start = Math.max(markedIndex - wordsOnSide, 0);
                let end = Math.min(markedIndex + wordsOnSide + 1, contentWords.length);

                const startExtensionLimit = wordsOnSide * 3;
                const startOffset = 3;

                // find a probable sentence-start by walking backwards through words
                if (start > 0) {
                    for (let i=start+startOffset; i>0 && i>start-startExtensionLimit; i--) {
                        let sentenceLike = /[.,!?:;]$/.test(contentWords[i-1]) && /^[“”‘’"']?[A-Z]/.test(contentWords[i]);
                        if (sentenceLike) {
                            start = i;
                            break;
                        }
                    }
                }

                // if start is unchanged and the content to beginning is short, include from beginning
                if (start === markedIndex - wordsOnSide && start - startExtensionLimit <= 0) {
                    start = 0;
                }
                
                let contentHighlight = contentWords.slice(start, end).join(" ");
                if (end !== contentWords.length) {
                    contentHighlight += "&hellip;";
                }

                return contentHighlight.replaceAll(/<\/?mark>/g, "");
            }

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
                                <p>${processHighlightedContent(item._highlightResult.content.value)}</p>
                            </div>`;
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
                        query: decodeURIComponent(urlParams.get("q"))
                    }
                });
            }
        }
    }};
})(Drupal, drupalSettings);
