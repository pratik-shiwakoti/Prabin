/* Theme preference with a local, browser-only saved choice. */
document.addEventListener("DOMContentLoaded", function () {
  var storageKey = "prabin-portfolio-theme";
  var toggle = document.querySelector(".theme-toggle");
  var icon = document.querySelector(".theme-toggle__icon");
  var savedTheme = null;

  try {
    savedTheme = window.localStorage.getItem(storageKey);
  } catch (error) {
    // Theme switching remains available if browser storage is unavailable.
  }

  function preferredTheme() {
    return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
  }

  function applyTheme(theme) {
    document.documentElement.setAttribute("data-theme", theme);

    if (toggle) {
      toggle.setAttribute("aria-pressed", String(theme === "dark"));
      toggle.setAttribute("aria-label", theme === "dark" ? "Switch to light theme" : "Switch to dark theme");
    }

    if (icon) {
      icon.textContent = theme === "dark" ? "☀" : "◐";
    }
  }

  applyTheme(savedTheme === "light" || savedTheme === "dark" ? savedTheme : preferredTheme());

  if (!toggle) {
    return;
  }

  toggle.addEventListener("click", function () {
    var nextTheme = document.documentElement.getAttribute("data-theme") === "dark" ? "light" : "dark";
    applyTheme(nextTheme);

    try {
      window.localStorage.setItem(storageKey, nextTheme);
    } catch (error) {
      // A visual theme change does not require persistent browser storage.
    }
  });
});
