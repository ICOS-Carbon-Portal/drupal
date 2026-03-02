/**
 * @file
 * CP Table of contents – vanilla JS, no jQuery dependency.
 *
 * Reads configuration from data-* attributes on the .cp-toc wrapper element,
 * traverses the article body for matching headings, and builds a flat <ul>.
 *
 * Features:
 *   - Configurable CSS selectors (any valid querySelectorAll string)
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

  // -------------------------------------------------------------------------
  // Core class
  // -------------------------------------------------------------------------

  class CpToc {
    constructor(wrapper) {
      this.wrapper = wrapper;
      this.list    = wrapper.querySelector('.cp-toc__list');

      const d = wrapper.dataset;

      this.settings = {
        headings:        d.headings      || 'h2, h3',
        minHeadings:     parseInt(d.minHeadings  || '2', 10),
        smoothScroll:    d.smoothScroll  !== '0',
        scrollOffset:    parseInt(d.scrollOffset || '0', 10),
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

      headings.forEach(heading => {
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
        this.list.appendChild(li);
        this._headingEls.push(heading);
      });
    }

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
