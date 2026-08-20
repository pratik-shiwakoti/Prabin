/* Global navigation interactions. */
document.addEventListener("DOMContentLoaded", function () {
  var year = document.getElementById("current-year");
  var menuButton = document.querySelector(".menu-toggle");
  var navigation = document.querySelector(".primary-navigation");

  if (year) {
    year.textContent = String(new Date().getFullYear());
  }

  if (!menuButton || !navigation) {
    return;
  }

  function closeMenu() {
    navigation.classList.remove("is-open");
    menuButton.setAttribute("aria-expanded", "false");
  }

  menuButton.addEventListener("click", function () {
    var isOpen = navigation.classList.toggle("is-open");
    menuButton.setAttribute("aria-expanded", String(isOpen));
  });

  navigation.addEventListener("click", function (event) {
    if (event.target.closest("a")) {
      closeMenu();
    }
  });

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      closeMenu();
      menuButton.focus();
    }
  });

  window.addEventListener("resize", function () {
    if (window.matchMedia("(min-width: 48rem)").matches) {
      closeMenu();
    }
  });
});
