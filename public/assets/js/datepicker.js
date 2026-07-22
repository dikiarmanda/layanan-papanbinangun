/**
 * Inisialisasi Flatpickr untuk semua input tanggal.
 * - .datepicker / [data-datepicker] : tanggal tunggal
 * - #check_in + #check_out : rentang homestay (linked)
 */
(function () {
  if (typeof flatpickr === "undefined") return;

  const locale = flatpickr.l10ns && flatpickr.l10ns.id ? flatpickr.l10ns.id : "default";

  const defaults = {
    locale: locale,
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "j F Y",
    altInputClass: "form-control",
    allowInput: false,
    disableMobile: true,
  };

  function parseMin(el) {
    const min = el.getAttribute("data-min") || el.getAttribute("min");
    return min || null;
  }

  function initSingle(el) {
    if (el._flatpickr) return el._flatpickr;
    const opts = Object.assign({}, defaults, {
      minDate: parseMin(el) || undefined,
      defaultDate: el.value || undefined,
    });
    return flatpickr(el, opts);
  }

  function initHomestayRange() {
    const checkInEl = document.getElementById("check_in");
    const checkOutEl = document.getElementById("check_out");
    if (!checkInEl || !checkOutEl) return false;

    const onChange = typeof window.__onHomestayDatesChange === "function" ? window.__onHomestayDatesChange : function () {};

    const checkOutFp = flatpickr(
      checkOutEl,
      Object.assign({}, defaults, {
        minDate: parseMin(checkOutEl) || "tomorrow",
        defaultDate: checkOutEl.value || undefined,
        onChange: function () {
          onChange();
        },
      }),
    );

    flatpickr(
      checkInEl,
      Object.assign({}, defaults, {
        minDate: parseMin(checkInEl) || "today",
        defaultDate: checkInEl.value || undefined,
        onChange: function (selectedDates, dateStr) {
          if (selectedDates[0]) {
            const next = new Date(selectedDates[0]);
            next.setDate(next.getDate() + 1);
            checkOutFp.set("minDate", next);
            const outVal = checkOutEl.value;
            if (outVal) {
              const outDate = new Date(outVal + "T00:00:00");
              if (outDate <= selectedDates[0]) {
                checkOutFp.clear();
              }
            }
          }
          onChange();
        },
      }),
    );

    return true;
  }

  document.addEventListener("DOMContentLoaded", function () {
    const isRange = initHomestayRange();

    document.querySelectorAll(".datepicker, [data-datepicker]").forEach(function (el) {
      if (isRange && (el.id === "check_in" || el.id === "check_out")) return;
      initSingle(el);
    });
  });
})();
