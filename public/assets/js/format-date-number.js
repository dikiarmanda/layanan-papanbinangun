/**
 * Utilitas aplikasi yang tidak bergantung pada halaman tertentu.
 *
 * Kelompok fungsi:
 * - Nilai  : formatRupiah
 * - Tanggal: nightsBetween, addDays
 */
(function (window) {
  "use strict";

  function formatRupiah(value) {
    return "Rp " + Math.round(Number(value) || 0).toLocaleString("id-ID");
  }

  function toLocalDate(value) {
    if (value instanceof Date) {
      return new Date(value.getFullYear(), value.getMonth(), value.getDate());
    }
    if (typeof value !== "string" || !value) return null;

    const date = new Date(value + "T00:00:00");
    return Number.isNaN(date.getTime()) ? null : date;
  }

  function nightsBetween(start, end) {
    const startDate = toLocalDate(start);
    const endDate = toLocalDate(end);
    if (!startDate || !endDate) return 0;

    const diff = Math.round((endDate - startDate) / 86400000);
    return diff > 0 ? diff : 0;
  }

  function addDays(value, days) {
    const date = toLocalDate(value);
    if (!date) return "";

    date.setDate(date.getDate() + Number(days || 0));
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return year + "-" + month + "-" + day;
  }

  window.AppUtils = Object.assign(window.AppUtils || {}, {
    formatRupiah: formatRupiah,
    nightsBetween: nightsBetween,
    addDays: addDays,
  });
})(window);
