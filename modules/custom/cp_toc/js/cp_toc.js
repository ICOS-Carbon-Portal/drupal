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

    let id = base;
    let n  = 1;
    while (usedIds.includes(id)) {
      id = `${base}-${n++}`;
    }
    usedIds.push(id);
    return id;
  }

  function headingLevel(el) {
    return parseInt(el.tagName[1], 10);
  }

  function plainText(el) {
    return el.textContent.trim();
  }

  /**
   * Splits a space-separated class string into an array, filtering empties.
   */
  function splitClasses(str) {
    return (str || '').split(/\s+/).filter(Boolean);
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

  class CpToc {

    /** @param {HTMLElement} wrapper  The .cp-toc element rendered by Twig. */
    constructor(wrapper) {
      this.wrapper = wrapper;
      this.list    = wrapper.querySelector('.cp-toc__list');

      const d = wrapper.dataset;

      this.settings = {
        headings:        d.headings      || 'h2, h3',
        minHeadings:     parseInt(d.minHeadings  || '2', 10),
        smoothScroll:    d.smoothScroll  !== '0',
        scrollOffset:    parseInt(d.scrollOffset || '0', 10),
        nesting:         d.nesting       === '1',
        listClasses:     splitClasses(d.listClasses),
        listItemClasses: splitClasses(d.listItemClasses),
        linkClasses:     splitClasses(d.linkClasses),
      };

      // Scan container: prefer the nearest <article>, then <main>, then <body>.
      this.container = (
        wrapper.closest('article') ||
        wrapper.closest('main')    ||
        document.body
      );

      this._usedIds    = [];
      this._headingEls = [];

      this._init();
    }

    _init() {
      if (this.settings.listClasses.length) {
        this.list.classList.add(...this.settings.listClasses);
      }

      this._buildToc();

      if (this._headingEls.length < this.settings.minHeadings) {
        return; // Not enough headings – keep the block hidden.
      }

      this.wrapper.removeAttribute('hidden');
    }

    _buildToc() {
      const selectors = this.settings.headings
        .split(',')
        .map(s => s.trim())
        .filter(Boolean);

      if (!selectors.length) return;

      const headings = Array.from(
        this.container.querySelectorAll(selectors.join(','))
      ).filter(el => {
        if (this.wrapper.contains(el)) return false;
        const style = window.getComputedStyle(el);
        return style.display !== 'none' && style.visibility !== 'hidden';
      });

      if (!headings.length) return;

      if (this.settings.nesting) {
        this._buildNestedList(headings, selectors);
      } else {
        this._buildFlatList(headings);
      }
    }

    /**
     * Appends every heading as a direct child of the root list.
     *
     * @param {HTMLElement[]} headings
     */
    _buildFlatList(headings) {
      headings.forEach(heading => {
        this.list.appendChild(this._buildItem(heading));
        this._headingEls.push(heading);
      });
    }

    /**
     * Appends top-level headings as direct children of the root list and
     * nests all other headings one level deep inside the preceding top-level
     * item.  The top level is defined as the lowest heading rank (smallest hN
     * number) found among the collected headings.  Only one level of nesting
     * is ever created regardless of how many distinct heading levels are
     * present.
     *
     * When a nested heading appears before any top-level heading it is added
     * to the root list instead of being silently dropped.
     *
     * @param {HTMLElement[]} headings
     * @param {string[]}      selectors  Trimmed selector strings.
     */
    _buildNestedList(headings, selectors) {
      const ranks  = headings.map(h => elementRank(h, selectors));
      const topRank = Math.min(...ranks);

      let currentParent = null; // Current top-level <li> element.

      headings.forEach((heading, i) => {
        const li = this._buildItem(heading);
        this._headingEls.push(heading);

        if (ranks[i] <= topRank) {
          // Top-level item.
          this.list.appendChild(li);
          currentParent = li;
        } else {
          // Nested item – always one level regardless of rank difference.
          if (currentParent) {
            let sublist = currentParent.querySelector('.cp-toc__sublist');
            if (!sublist) {
              sublist = document.createElement('ul');
              sublist.classList.add('cp-toc__sublist','ms-3');
              if (this.settings.listClasses.length) {
                sublist.classList.add(...this.settings.listClasses);
              }
              currentParent.appendChild(sublist);
            }
            sublist.appendChild(li);
          } else {
            // No parent exists yet; promote to root level.
            this.list.appendChild(li);
            currentParent = li;
          }
        }
      });
    }

    /**
     * Creates a single <li><a> pair for the given heading element.
     * Assigns a slug-based id to the heading if it does not already have one.
     *
     * @param  {HTMLElement} heading
     * @returns {HTMLElement}  The <li> element (not yet attached to the DOM).
     */
    _buildItem(heading) {
      const level = headingLevel(heading);

      // Assign a stable id if the heading doesn't have one.
      if (!heading.id) {
        heading.id = slugify(plainText(heading), this._usedIds);
        heading.setAttribute(PROCESSED_ATTR, '1');
      } else {
        this._usedIds.push(heading.id);
      }

      // Build <li>.
      const li = document.createElement('li');
      li.className = `cp-toc__item cp-toc__item--h${level}`;
      if (this.settings.listItemClasses.length) {
        li.classList.add(...this.settings.listItemClasses);
      }

      // Build <a>.
      const a = document.createElement('a');
      a.className   = 'cp-toc__link';
      a.href        = `#${heading.id}`;
      a.textContent = plainText(heading);
      if (this.settings.linkClasses.length) {
        a.classList.add(...this.settings.linkClasses);
      }
      a.addEventListener('click', (e) => {
        e.preventDefault();
        this._scrollTo(heading);
      });

      li.appendChild(a);
      return li;
    }

    // -----------------------------------------------------------------------
    // Scroll behaviour
    // -----------------------------------------------------------------------

    _scrollTo(heading) {
      const offset = this.settings.scrollOffset;

      if (offset === 0 && this.settings.smoothScroll) {
        heading.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } else {
        const top = heading.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: this.settings.smoothScroll ? 'smooth' : 'auto' });
      }

      history.replaceState(null, '', `#${heading.id}`);

      if (!heading.hasAttribute('tabindex')) {
        heading.setAttribute('tabindex', '-1');
      }
      heading.focus({ preventScroll: true });
    }

    // -----------------------------------------------------------------------
    // Teardown
    // -----------------------------------------------------------------------

    destroy() {
      this._headingEls.forEach(h => {
        if (h.getAttribute(PROCESSED_ATTR)) {
          h.removeAttribute('id');
          h.removeAttribute(PROCESSED_ATTR);
        }
      });

      this.list.innerHTML = '';
      this.wrapper.setAttribute('hidden', '');
    }
  }

  // -------------------------------------------------------------------------
  // Drupal behaviour
  // -------------------------------------------------------------------------

  Drupal.behaviors.cpToc = {
    attach(context) {
      once('cp-toc', '.cp-toc', context).forEach(wrapper => {
        wrapper._cpTocInstance = new CpToc(wrapper);
      });
    },

    detach(context, settings, trigger) {
      if (trigger !== 'unload') return;

      context.querySelectorAll('.cp-toc').forEach(wrapper => {
        if (wrapper._cpTocInstance) {
          wrapper._cpTocInstance.destroy();
          delete wrapper._cpTocInstance;
        }
      });
    },
  };

})(Drupal, once);
