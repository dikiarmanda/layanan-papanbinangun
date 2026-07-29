/**
 * Mobile sidebar toggle — Panel Admin
 */
(function () {
  function initAdminNav() {
    const sidebar = document.getElementById("adminSidebar");
    const overlay = document.getElementById("adminOverlay");
    const toggle = document.getElementById("adminNavToggle");
    const nav = document.getElementById("adminNav");
    if (!sidebar || !overlay || !toggle) return;

    const icon = toggle.querySelector("i");

    const setOpen = (open) => {
      sidebar.classList.toggle("is-open", open);
      overlay.classList.toggle("is-visible", open);
      overlay.hidden = !open;
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      toggle.setAttribute("aria-label", open ? "Tutup menu" : "Buka menu");
      document.body.style.overflow = open ? "hidden" : "";
      if (icon) {
        icon.className = open ? "fa-solid fa-xmark" : "fa-solid fa-bars";
      }
    };

    toggle.addEventListener("click", () => {
      setOpen(!sidebar.classList.contains("is-open"));
    });

    overlay.addEventListener("click", () => setOpen(false));

    nav?.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        if (window.innerWidth <= 992) setOpen(false);
      });
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") setOpen(false);
    });

    window.addEventListener("resize", () => {
      if (window.innerWidth > 992) setOpen(false);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAdminNav);
  } else {
    initAdminNav();
  }
})();
