# CP Search Module - Typesense Front-end

## Generating a scoped API key

With the administrative API key, you can run this command against the Typesense server, providing the
Typesense API key and the appropriate drupal website below.

```
curl 'https://typesense.icos-cp.eu/keys' \
    -X POST \
    -H "X-TYPESENSE-API-KEY: TYPESENSE_API_KEY" \
    -H 'Content-Type: application/json' \
    -d '{"description":"Search-only key for WEBSITE.","actions": ["documents:search"], "collections": ["WEBSITE-main"]}'
```

## Provide key and website in configuration

Go to `/admin/config//search/cp-search` and enter the website and API key. This is also found in the Configuration menu, under Search -> Typesense.

## Structure of module

The main search results page, which will be found at `/search`, has an HTML scaffold in
`templates/search-results-page.html.twig`. This page is then dynamically generated from the JavaScript code in
`js/search-results-page.js`.