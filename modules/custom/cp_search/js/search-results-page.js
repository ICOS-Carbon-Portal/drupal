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
            const categoriesDiv = context.getElementById("categories");

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
                filterByOptions: {
                    category: { exactMatch: true}
                }
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

            const { connectConfigure } = instantsearch.connectors;

            const renderConfigure = (renderOptions, isFirstRender) => {
                const { refine, widgetParams } = renderOptions;

                if (isFirstRender) {
                    const buttonInfos = [
                        {key: "all", label: "All"},
                        {key: "events", label: "Events"},
                        {key: "news", label: "News"},
                        {key: "main_website", label: "Pages"},
                        {key: "station", label: "Stations"},
                    ];

                    const buttonElements = buttonInfos.map((btnInfo) => {
                        let el = document.createElement("button");
                        el.type = "button";
                        el.classList.add("btn","me-2","rounded-1","fw-normal");
                        if (btnInfo.key !== "all") {
                            el.classList.add("btn-outline-primary");
                        } else {
                            el.classList.add("btn-primary");
                        }
                        el.innerText = btnInfo.label;
                        el.dataset["category"] = btnInfo.key;
                        return el;
                    });

                    for (let button of buttonElements) {
                        button.addEventListener("click", () => {
                            refine({ filters: button.dataset["category"] === "all"
                                ? ""
                                : "category:=" + button.dataset["category"]});
                            for (let b of buttonElements) {
                                b.classList.add("btn-outline-primary");
                                b.classList.remove("btn-primary");
                            }
                            button.classList.remove("btn-outline-primary");
                            button.classList.add("btn-primary");
                        });
                        widgetParams.container.appendChild(button);
                    }
                }
            };

            const categoryConfigure = connectConfigure(renderConfigure);

            const categoryLabels = {
                "main_website": "Page",
                "news": "News",
                "events": "Event",
                "station": "Station",
            };

            search.addWidgets([
                instantsearch.widgets.searchBox({
                  container: searchboxDiv,
                  showSubmit: true,
                  showReset: false,
                  cssClasses: {
                    form: "input-group",
                    input: ["form-search", "form-control"],
                    submit: ["btn", "btn-primary", "ms-3", "rounded-5"]
                  },
                  /*templates: {
                    submit({ cssClasses }, { html }) {
                        return html`<i class="${cssClasses.submitIcon}"></i>`;
                    },
                  }*/
                  templates: {
                    submit({ cssClasses }, { html }) {
                        return "Search";
                    },
                  }
                }),
                paginationPanel({
                  container: paginationDiv,
                  cssClasses: {
                    link: ["btn", "btn-outline-primary", "me-2", "rounded",
                        "d-flex", "justify-content-center", "align-items-center", "fs-sm"
                    ]
                  },
                  templates: {
                    last: "Last &nbsp;&raquo;",
                    next: "Next &nbsp;&rsaquo;",
                    previous: "&lsaquo;&nbsp; Previous",
                    first: "&laquo;&nbsp; First",
                  }
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
                            return `<div class="search-results-hit p-4 bg-blue-10 mb-25 rounded">
                                <div class="text-primary text-uppercase mb-1">${categoryLabels[item.category]}</div>
                                <h3><a href="${item.url}" class="text-dark">${item.title}</a></h3>
                                <p>${processHighlightedContent(item._highlightResult.content.value)}</p>
                            </div>`;
                      },
                    },
                }),
                categoryConfigure({
                    container: categoriesDiv,
                    searchParameters: {
                        filters: ""
                    }
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
