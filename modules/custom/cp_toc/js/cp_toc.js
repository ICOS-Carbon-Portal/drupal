/**
 * @file
 * CP Table of contents – vanilla JS, no jQuery dependency.
 *
 * Reads configuration from data-* attributes on the .cp-toc wrapper element,
 * traverses the article body for matching headings, and builds a <ul>.
 *
 * Features:
 *   - Configurable CSS selectors (any valid querySelectorAll string)
 *   - Optional one-level nesting based on heading hierarchy
 *   - Custom CSS classes on the list container and each list item
 *   - Smooth-scroll with configurable pixel offset
 */

/* global Drupal, once */

((Drupal, once) => {
  const PROCESSED_ATTR = 'data-cp-toc-id';

  /**
   * Converts a heading text string into a URL-safe slug.
   *
   * @param {string[]} usedIds  Mutated accumulator of already-used ids.
   */
  function slugify(text, usedIds) {
    let base = text
      .toLowerCase()
      .replace(/[^\w\s-]/g, '')
      .trim()
      .replace(/[\s_]+/g, '-')
      .replace(/-{2,}/g, '-');

    if (!base) base = 'heading';

    let id = base, n  = 1;
    while (usedIds.includes(id)) {
      id = `${base}-${n++}`;
    }
    usedIds.push(id);
    return id;
  }

  function plainText(el) {
    return el.textContent.trim();
  }

  /**
   * Splits a space-separated class string into an array, filtering empties.
   */
  function splitClasses(str) {
    return str ? str.split(/\s+/).filter(s => typeof s === "string" && s.length > 0) : [];
  }

  /**
   * Returns a numeric rank for an element used to determine nesting.
   *
   * For standard heading elements (h1–h6) the rank equals the heading level
   * (h2 → 2, h3 → 3, etc.).  For all other elements the rank is 100 + the
   * element's position in the selector list, ensuring headings always sort
   * before non-heading selectors when mixing types.
   *
   * @param {string[]}    selectors  Trimmed, non-empty selector strings.
   */
  function elementRank(el, selectors) {
    const m = el.tagName.match(/^H([1-6])$/i);
    if (m) return parseInt(m[1], 10);
    for (let i = 0; i < selectors.length; i++) {
      if (el.matches(selectors[i])) return 100 + i;
    }
    return 999;
  }

  /** @param {HTMLElement} wrapper  The .cp-toc element rendered by Twig. */
  function buildToc(wrapper) {
    const list = wrapper.querySelector('.cp-toc__list');

    const d = wrapper.dataset;

    const settings = {
      headings: d.headings || 'h2, h3',
      minHeadings: parseInt(d.minHeadings || '2', 10),
      smoothScroll: d.smoothScroll  !== '0',
      scrollOffset: parseInt(d.scrollOffset || '0', 10),
      nesting: d.nesting === '1',
      listClasses: splitClasses(d.listClasses),
      listItemClasses: splitClasses(d.listItemClasses),
      linkClasses: splitClasses(d.linkClasses),
    };

    // Scan container: prefer the nearest <article>, then <main>, then <body>.
    const container = wrapper.closest('article') ??
      wrapper.closest('main') ??
      document.body;

    let usedIds = [];

    if (settings.listClasses.length > 0) {
      list.classList.add(...settings.listClasses);
    }

    const selectors = settings.headings.split(',')
      .map(s => s.trim()).filter(s => typeof s === "string" && s.length > 0);

    if (selectors.length === 0) return;

    const headings = Array.from(container.querySelectorAll(selectors.join(','))).filter(el => {
      if (wrapper.contains(el)) return false;
      const style = window.getComputedStyle(el);
      return style.display !== 'none' && style.visibility !== 'hidden';
    });

    if (headings.length < settings.minHeadings) {
      return; // Not enough headings – keep the block hidden.
    }

    if (settings.nesting) {
      const ranks  = headings.map(h => elementRank(h, selectors));
      const topRank = Math.min(...ranks);

      let currentParent = null;

      headings.forEach((heading, i) => {
        const li = buildItem(heading, settings, usedIds);

        if (ranks[i] <= topRank) {
          list.appendChild(li);
          currentParent = li;
        } else {
          if (currentParent) {
            let sublist = currentParent.querySelector('.cp-toc__sublist');
            if (!sublist) {
              sublist = document.createElement('ul');
              sublist.classList.add('cp-toc__sublist', 'ms-3');
              if (settings.listClasses.length > 0) {
                sublist.classList.add(...settings.listClasses);
              }
              currentParent.appendChild(sublist);
            }
            sublist.appendChild(li);
          } else {
            list.appendChild(li);
            currentParent = li;
          }
        }
      });
    } else {
      headings.forEach(heading => list.appendChild(buildItem(heading, settings, usedIds)));
    }


    wrapper.removeAttribute('hidden');
  }


  /**
   * Creates a single <li><a> pair for the given heading element.
   * Assigns a slug-based id to the heading if it does not already have one.
   *
   * @returns {HTMLElement}  The <li> element (not yet attached to the DOM).
   */
  function buildItem(heading, settings, usedIds) {
    // Assign a stable id if the heading doesn't have one.
    if (!heading.id) {
      heading.id = slugify(plainText(heading), usedIds);
      heading.setAttribute(PROCESSED_ATTR, '1');
    } else {
      usedIds.push(heading.id);
    }

    // Build <li>.
    const li = document.createElement('li');
    li.className = 'cp-toc__item';
    if (settings.listItemClasses.length) {
      li.classList.add(...settings.listItemClasses);
    }

    // Build <a>.
    const a = document.createElement('a');
    a.className = 'cp-toc__link';
    a.href = `#${heading.id}`;
    a.textContent = plainText(heading);

    if (settings.linkClasses.length) {
      a.classList.add(...settings.linkClasses);
    }

    a.addEventListener('click', (e) => {
      e.preventDefault();
      scrollTo(heading, settings);
    });

    li.appendChild(a);
    return li;
  }

  function scrollTo(heading, settings) {
    if (settings.scrollOffset === 0 && settings.smoothScroll) {
      heading.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
      const top = heading.getBoundingClientRect().top + window.scrollY - settings.scrollOffset;
      window.scrollTo({ top, behavior: settings.smoothScroll ? 'smooth' : 'auto' });
    }

    history.replaceState(null, '', `#${heading.id}`);

    if (!heading.hasAttribute('tabindex')) {
      heading.setAttribute('tabindex', '-1');
    }
    heading.focus({ preventScroll: true });
  }

  Drupal.behaviors.cpToc = {
    attach(context) {
      once('cp-toc', '.cp-toc', context).forEach(wrapper => {
        buildToc(wrapper);
      });
    },
  };

})(Drupal, once);
