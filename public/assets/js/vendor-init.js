/**
 * Inisialisasi plugin vendor yang umum dipakai di layout.
 *
 * Selector:
 * - Dropify  : input.dropify
 * - Select2  : select.select2 / select.js-select2  → Select2Init
 * - Summernote : textarea.summernote-field
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

  function initSelect2(root, overrides) {
    if (window.Select2Init) return window.Select2Init.init(root, overrides);
  }

  function destroySelect2(root) {
    if (window.Select2Init) window.Select2Init.destroy(root);
  }

  var SUMMERNOTE_TOOLBAR = [
    ["style", ["style"]],
    ["font", ["bold", "italic", "underline", "strikethrough", "clear"]],
    ["fontsize", ["fontsize"]],
    ["color", ["color"]],
    ["para", ["ul", "ol", "paragraph"]],
    ["table", ["table"]],
    ["insert", ["link", "hr"]],
    ["view", ["codeview"]],
  ];

  var SUMMERNOTE_STYLE_TAGS = ["p", "blockquote", "h1", "h2", "h3", "h4", "pre"];

  function initSummernote(root) {
    if (!$ || !$.fn || !$.fn.summernote) return;

    $(root || document)
      .find("textarea.summernote-field")
      .each(function () {
        const $el = $(this);
        if ($el.data("summernote-ready")) return;

        const value = ($el.val() || "").trim();
        if (value && value.indexOf("<") === -1) {
          $el.val(
            value
              .split(/\n{2,}/)
              .map(function (paragraf) {
                return "<p>" + paragraf.trim().replace(/\n/g, "<br>") + "</p>";
              })
              .join("")
          );
        }

        $el.summernote({
          lang: "id-ID",
          height: parseInt($el.data("height") || "260", 10),
          placeholder: $el.data("placeholder") || "Tulis deskripsi…",
          toolbar: SUMMERNOTE_TOOLBAR,
          styleTags: SUMMERNOTE_STYLE_TAGS,
        });
        $el.data("summernote-ready", true);
      });
  }

  function initSweetAlert() {
    if (typeof window.initSwalFlash === "function") window.initSwalFlash();
    if (typeof window.initSwalConfirm === "function") window.initSwalConfirm();
  }

  function boot(root) {
    initDropify(root);
    initSelect2(root);
    initSummernote(root);
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
    destroySelect2: destroySelect2,
    initSummernote: initSummernote,
    initSweetAlert: initSweetAlert,
  };
})(window, window.jQuery);
