/**
 * Select2Init — inisialisasi Select2 berbasis class.
 *
 * Selector default: select.select2, select.js-select2
 *
 * Data attributes (opsional):
 * - data-placeholder
 * - data-allow-clear="1"
 * - data-width="100%"
 * - data-minimum-results-for-search="8"
 * - data-dropdown-parent=".selector"
 * - data-theme="default"
 *
 * Penggunaan:
 *   Select2Init.init();
 *   Select2Init.init('#form');
 *   Select2Init.init(document.getElementById('estimatePaket'));
 *   Select2Init.destroy(el);
 *   Select2Init.reinit(el);
 */
(function (window, $) {
  "use strict";

  const SELECTOR = "select.select2, select.js-select2";

  class Select2Init {
    static get selector() {
      return SELECTOR;
    }

    static isReady() {
      return !!(window.jQuery && $.fn && typeof $.fn.select2 === "function");
    }

    static isInitialized($el) {
      return $el.hasClass("select2-hidden-accessible");
    }

    /**
     * Kumpulkan elemen select di dalam root (atau root itu sendiri jika match).
     * @param {Element|Document|string|jQuery|null} root
     * @returns {jQuery}
     */
    static collect(root) {
      const $root = root ? $(root) : $(document);
      return $root.filter(SELECTOR).add($root.find(SELECTOR));
    }

    /**
     * Opsi Select2 dari data-* elemen.
     * @param {jQuery} $el
     * @param {object} [overrides]
     * @returns {object}
     */
    static optionsFrom($el, overrides) {
      const parentSel = $el.data("dropdown-parent");
      const minSearch = $el.data("minimum-results-for-search");

      const opts = {
        width: $el.data("width") || "100%",
        placeholder: $el.data("placeholder") || "Pilih…",
        allowClear: $el.data("allow-clear") === true || $el.data("allow-clear") === 1,
        language: "id",
        theme: $el.data("theme") || "default",
        minimumResultsForSearch: minSearch !== undefined ? minSearch : Infinity,
        dropdownParent: parentSel ? $(parentSel) : $(document.body),
      };

      return Object.assign(opts, overrides || {});
    }

    /**
     * Inisialisasi satu elemen.
     * @param {Element|jQuery|string} el
     * @param {object} [overrides]
     * @returns {jQuery|null}
     */
    static initOne(el, overrides) {
      if (!this.isReady()) return null;

      const $el = $(el);
      if (!$el.length || this.isInitialized($el)) return $el;

      $el.select2(this.optionsFrom($el, overrides));
      return $el;
    }

    /**
     * Inisialisasi semua select di root.
     * @param {Element|Document|string|jQuery|null} [root]
     * @param {object} [overrides]
     * @returns {number} jumlah yang diinisialisasi
     */
    static init(root, overrides) {
      if (!this.isReady()) return 0;

      let count = 0;
      this.collect(root).each((_, el) => {
        const $el = $(el);
        if (this.isInitialized($el)) return;
        $el.select2(this.optionsFrom($el, overrides));
        count += 1;
      });
      return count;
    }

    /**
     * Destroy satu / semua select di root.
     * @param {Element|Document|string|jQuery|null} [root]
     */
    static destroy(root) {
      if (!this.isReady()) return;

      this.collect(root).each((_, el) => {
        const $el = $(el);
        if (this.isInitialized($el)) $el.select2("destroy");
      });
    }

    /**
     * Destroy lalu init ulang.
     * @param {Element|Document|string|jQuery|null} [root]
     * @param {object} [overrides]
     */
    static reinit(root, overrides) {
      this.destroy(root);
      return this.init(root, overrides);
    }
  }

  window.Select2Init = Select2Init;
})(window, window.jQuery);
