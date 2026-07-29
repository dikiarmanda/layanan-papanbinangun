/**
 * Inisialisasi plugin vendor yang umum dipakai di layout.
 *
 * Selector:
 * - Dropify  : input.dropify
 * - Select2  : select.select2 / select.js-select2
 * - Lexical  : textarea.lexical-field
 * - SweetAlert flash : .swal-flash[data-type][data-message]
 * - SweetAlert confirm form : form.js-swal-confirm
 */
(function (window, $) {
  "use strict";

  function initDropify(root) {
    if (!$ || !$.fn || !$.fn.dropify) return;

    $(root || document)
      .find("input.dropify")
      .each(function () {
        const $el = $(this);
        if ($el.data("dropify-initialized")) return;

        $el.dropify({
          messages: {
            default: "Seret file ke sini atau klik",
            replace: "Seret atau klik untuk mengganti",
            remove: "Hapus",
            error: "File tidak valid",
          },
          error: {
            fileSize: "Ukuran file terlalu besar (maks {{ value }}).",
            imageFormat: "Format gambar tidak didukung ({{ value }} saja).",
            fileExtension: "Ekstensi file tidak diizinkan.",
          },
        });
        $el.data("dropify-initialized", true);
      });
  }

  function initSelect2(root) {
    if (!$ || !$.fn || !$.fn.select2) return;

    $(root || document)
      .find("select.select2, select.js-select2")
      .each(function () {
        const $el = $(this);
        if ($el.hasClass("select2-hidden-accessible")) return;

        $el.select2({
          width: $el.data("width") || "100%",
          placeholder: $el.data("placeholder") || "Pilih…",
          allowClear: $el.data("allow-clear") === true || $el.data("allow-clear") === 1,
          language: "id",
          dropdownParent: $el.data("dropdown-parent")
            ? $($el.data("dropdown-parent"))
            : undefined,
        });
      });
  }

  function initLexical(root) {
    if (!window.LexicalAdminEditor || typeof window.LexicalAdminEditor.initLexicalEditors !== "function") {
      return;
    }
    window.LexicalAdminEditor.initLexicalEditors(root || document);
  }

  function initSweetAlert() {
    if (typeof window.initSwalFlash === "function") window.initSwalFlash();
    if (typeof window.initSwalConfirm === "function") window.initSwalConfirm();
  }

  function boot(root) {
    initDropify(root);
    initSelect2(root);
    initLexical(root);
    if (!root) initSweetAlert();
  }

  function onReady(fn) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", fn);
    } else {
      fn();
    }
  }

  onReady(function () {
    boot();
  });

  window.VendorInit = {
    boot: boot,
    initDropify: initDropify,
    initSelect2: initSelect2,
    initLexical: initLexical,
    initSweetAlert: initSweetAlert,
  };
})(window, window.jQuery);
