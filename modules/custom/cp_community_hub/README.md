# Community Hub

## Setup instructions

Once installed/enabled, requires a specific page setup to render properly.

1) Create a new basic page. Set the desired URL alias and disable the TOC.
2) Body should have several elements, including a replacement `h1`, introductory text that is always displayed, and a `div` with the id `user-groups-container`. Ensure "Full HTML" is selected. Example content:

```html
<h1 class="text-center">
    Welcome to the <span class="text-danger">ICOS Community Hub</span>!
</h1>
<p>
    (placeholder for introductory text TBD)
</p>
<div class="w-100" id="user-groups-container">
    <div class="fs-3 text-center">
        Are you...?
    </div>
</div>
```

3) Introduction should have the message that will be displayed immediately before the shown resource results. Example content:

```html
<p class="h4">
    Here are some resources curated for you!
</p>
```

4) Upload a header image for the page.
5) Save page.
6) In the layout manager:
    1) Add a new section at the bottom (e.g., "Light Blue Full Width (one column)")
    2) Add block to bottom section -- select the "Community Hub Resources" view block.
    3) Move the Introduction block from the top section to the bottom section, above the view block.
    4) Add block to bottom section -- create content block, add text that should appear when no user group is selected, e.g. `Tell us what your role is, and we’ll show you what you need...`
    5) Save layout.

## Hide h1

Go to `/admin/structure/block` and, for page title, click Configure. Add the page's alias (from above) to list of pages on which page title is hidden.

## Create taxonomy terms for "User groups"

Go to `/admin/structure/taxonomy/manage/community_user_groups/overview` and add user groups. Reorder as desired.

## Add content

Add new content, select "Community resource".
