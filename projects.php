<?php

require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/projects.php';

$pageTitle = 'Projects | Prabin Bahadur Thapa';
$pageDescription = 'Project portfolio of Prabin Bahadur Thapa, including Python and web development work.';
$activePage = 'projects';
$projects = [];
$categories = [];
$isDatabaseAvailable = true;

try {
    $database = getDatabaseConnection();
    $projects = getPublishedProjects($database);
    $categories = getPublishedProjectCategories($database);
} catch (Throwable $exception) {
    $isDatabaseAvailable = false;
}

require __DIR__ . '/includes/header.php';
?>
  <main id="main-content" tabindex="-1">
    <section class="page-intro section">
      <div class="container">
        <p class="eyebrow">Project portfolio</p>
        <h1>Work built through learning and exploration.</h1>
        <p>Browse published projects by category or search for a technology or keyword.</p>
      </div>
    </section>

    <section class="section section--alt" aria-labelledby="projects-title">
      <div class="container">
        <div class="section-title"><p class="eyebrow">Browse work</p><h2 id="projects-title">Projects</h2></div>

        <?php if (!$isDatabaseAvailable): ?>
          <div class="project-empty-state"><h3>Projects are temporarily unavailable.</h3><p>Please check the local database configuration and import the database schema.</p></div>
        <?php elseif (!$projects): ?>
          <div class="project-empty-state"><h3>No published projects yet.</h3><p>Published projects will appear here once they have been added to the portfolio.</p></div>
        <?php else: ?>
          <div class="project-controls">
            <div class="project-search"><label class="visually-hidden" for="project-search">Search projects</label><input class="form-control" id="project-search" type="search" placeholder="Search projects" autocomplete="off"></div>
            <div class="project-filters" aria-label="Filter projects by category">
              <button class="filter-button is-active" type="button" data-filter="all" aria-pressed="true">All</button>
              <?php foreach ($categories as $category): ?>
                <button class="filter-button" type="button" data-filter="<?= e($category['slug']) ?>" aria-pressed="false"><?= e($category['name']) ?></button>
              <?php endforeach; ?>
            </div>
          </div>
          <p class="project-results" id="project-results" aria-live="polite">Showing <?= count($projects) ?> projects</p>
          <div class="project-grid" id="project-grid">
            <?php foreach ($projects as $project): ?>
              <?php $technologies = getProjectTechnologies($database, (int) $project['id']); ?>
              <article class="card project-card" data-category="<?= e($project['category_slug'] ?? 'other') ?>" data-search="<?= e(strtolower($project['title'] . ' ' . $project['short_description'] . ' ' . implode(' ', $technologies))) ?>">
                <?php if (!empty($project['cover_image'])): ?>
                  <img class="project-card__cover" src="<?= e($project['cover_image']) ?>" alt="<?= e($project['title']) ?> project cover" loading="lazy">
                <?php else: ?>
                  <div class="project-card__image" aria-hidden="true"><span><?= e(strtoupper(substr($project['title'], 0, 2))) ?></span></div>
                <?php endif; ?>
                <div class="card__content">
                  <p class="project-card__category"><?= e($project['category_name'] ?? 'Other') ?></p>
                  <h3 class="card__title"><?= e($project['title']) ?></h3>
                  <p><?= e($project['short_description']) ?></p>
                  <?php if ($technologies): ?><div class="project-card__technologies"><?php foreach ($technologies as $technology): ?><span class="badge"><?= e($technology) ?></span><?php endforeach; ?></div><?php endif; ?>
                  <div class="project-card__actions">
                    <?php if (!empty($project['github_url'])): ?><a class="button button--text" href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener noreferrer">GitHub</a><?php endif; ?>
                    <?php if (!empty($project['demo_url'])): ?><a class="button button--text" href="<?= e($project['demo_url']) ?>" target="_blank" rel="noopener noreferrer">Live Demo</a><?php endif; ?>
                    <a class="button button--text" href="project.php?slug=<?= rawurlencode($project['slug']) ?>">View Details <span aria-hidden="true">→</span></a>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
          <div class="project-empty-state" id="project-empty-state" hidden><h3>No projects match that search.</h3><p>Try another keyword or choose a different category.</p></div>
        <?php endif; ?>
      </div>
    </section>
  </main>
  <script src="assets/js/projects.js" defer></script>
<?php require __DIR__ . '/includes/footer.php'; ?>
