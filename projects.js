/* Client-side project search and category filtering. */
document.addEventListener("DOMContentLoaded", function () {
  var searchInput = document.getElementById("project-search");
  var filterButtons = Array.from(document.querySelectorAll(".filter-button"));
  var projectCards = Array.from(document.querySelectorAll(".project-card"));
  var resultText = document.getElementById("project-results");
  var emptyState = document.getElementById("project-empty-state");
  var activeCategory = "all";

  if (!searchInput || !projectCards.length) {
    return;
  }

  function updateProjects() {
    var query = searchInput.value.trim().toLowerCase();
    var visibleCount = 0;

    projectCards.forEach(function (card) {
      var categories = card.dataset.category.split(" ");
      var searchableText = card.dataset.search.toLowerCase();
      var categoryMatches = activeCategory === "all" || categories.includes(activeCategory);
      var searchMatches = searchableText.includes(query);
      var isVisible = categoryMatches && searchMatches;

      card.hidden = !isVisible;
      if (isVisible) {
        visibleCount += 1;
      }
    });

    if (resultText) {
      resultText.textContent = "Showing " + visibleCount + " project" + (visibleCount === 1 ? "" : "s");
    }

    if (emptyState) {
      emptyState.hidden = visibleCount !== 0;
    }
  }

  filterButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      activeCategory = button.dataset.filter;

      filterButtons.forEach(function (filterButton) {
        var isActive = filterButton === button;
        filterButton.classList.toggle("is-active", isActive);
        filterButton.setAttribute("aria-pressed", String(isActive));
      });

      updateProjects();
    });
  });

  searchInput.addEventListener("input", updateProjects);
});
