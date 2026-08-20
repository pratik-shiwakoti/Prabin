<?php

require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/projects.php';

$slug = $_GET['slug'] ?? '';
$project = null;
$technologies = [];
$images = [];
$relatedProjects = [];
$isDatabaseAvailable = true;

try {
    $database = getDatabaseConnection();
    $project = getPublishedProjectBySlug($database, $slug);

    if ($project !== null) {
        $technologies = getProjectTechnologies($database, (int) $project['id']);
        $images = getProjectImages($database, (int) $project['id']);
        $relatedProjects = getRelatedProjects($database, (int) $project['id'], $project['category_id'] ? (int) $project['category_id'] : null);
    }
} catch (Throwable $exception) {
    $isDatabaseAvailable = false;
}

$pageTitle = $project ? $project['title'] . ' | Prabin Bahadur Thapa' : 'Project Details | Prabin Bahadur Thapa';
$pageDescription = $project ? $project['short_description'] : 'Project details for a portfolio project by Prabin Bahadur Thapa.';
$activePage = 'projects';

require __DIR__ . '/includes/header.php';
?>
  <main id="main-content" tabindex="-1">
    <?php if (!$isDatabaseAvailable): ?>
      <section class="section"><div class="container project-empty-state"><h1>Project details are temporarily unavailable.</h1><p>Please check the local database configuration and import the database schema.</p><a class="button" href="projects.php">Back to projects</a></div></section>
    <?php elseif ($project === null): ?>
      <section class="section"><div class="container project-empty-state"><h1>Project not found.</h1><p>This project may be unpublished or the link may be incorrect.</p><a class="button" href="projects.php">Back to projects</a></div></section>
    <?php else: ?>
      <section class="project-detail-hero section"><div class="container">
        <a class="back-link" href="projects.php"><span aria-hidden="true">←</span> All projects</a>
        <p class="eyebrow"><?= e($project['category_name'] ?? 'Project') ?></p>
        <h1><?= e($project['title']) ?></h1>
        <p class="project-detail-hero__summary"><?= e($project['short_description']) ?></p>
        <div class="project-detail-hero__actions">
          <?php if (!empty($project['github_url'])): ?><a class="button" href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener noreferrer">View on GitHub</a><?php endif; ?>
          <?php if (!empty($project['demo_url'])): ?><a class="button button--secondary" href="<?= e($project['demo_url']) ?>" target="_blank" rel="noopener noreferrer">Live Demo</a><?php endif; ?>
        </div>
      </div></section>

      <?php if (!empty($project['cover_image'])): ?><img class="project-cover-image" src="<?= e($project['cover_image']) ?>" alt="<?= e($project['title']) ?> project cover"><?php endif; ?>

      <div class="container project-detail-layout section">
        <article class="project-detail-content">
          <?php foreach (['description' => 'Overview', 'problem' => 'Problem', 'solution' => 'Solution', 'features' => 'Key features', 'development_process' => 'Development process', 'challenges' => 'Challenges', 'outcome' => 'Outcome'] as $field => $heading): ?>
            <?php if (!empty($project[$field])): ?><section><h2><?= e($heading) ?></h2><p><?= nl2br(e($project[$field])) ?></p></section><?php endif; ?>
          <?php endforeach; ?>
          <?php if ($images): ?><section><h2>Screenshots</h2><div class="screenshot-grid"><?php foreach ($images as $image): ?><figure class="screenshot-placeholder"><img src="<?= e($image['image_path']) ?>" alt="<?= e($image['alt_text']) ?>" loading="lazy"><figcaption><?= e($image['alt_text']) ?></figcaption></figure><?php endforeach; ?></div></section><?php endif; ?>
        </article>
        <aside class="project-detail-sidebar" aria-label="Project information">
          <?php if ($technologies): ?><section class="project-info-card"><h2>Technologies</h2><div class="project-card__technologies"><?php foreach ($technologies as $technology): ?><span class="badge"><?= e($technology) ?></span><?php endforeach; ?></div></section><?php endif; ?>
          <?php if ($project['python_version'] || $project['libraries_frameworks'] || $project['database_apis'] || $project['automation_algorithms']): ?>
            <section class="project-info-card"><h2>Python project details</h2><dl class="project-spec-list">
              <?php if ($project['python_version']): ?><div><dt>Python version</dt><dd><?= e($project['python_version']) ?></dd></div><?php endif; ?>
              <?php if ($project['libraries_frameworks']): ?><div><dt>Libraries / framework</dt><dd><?= e($project['libraries_frameworks']) ?></dd></div><?php endif; ?>
              <?php if ($project['database_apis']): ?><div><dt>Database / APIs</dt><dd><?= e($project['database_apis']) ?></dd></div><?php endif; ?>
              <?php if ($project['automation_algorithms']): ?><div><dt>Automation / algorithms</dt><dd><?= e($project['automation_algorithms']) ?></dd></div><?php endif; ?>
            </dl></section>
          <?php endif; ?>
        </aside>
      </div>

      <?php if ($relatedProjects): ?><section class="section section--alt" aria-labelledby="related-projects-title"><div class="container"><div class="section-title"><p class="eyebrow">Continue exploring</p><h2 id="related-projects-title">Related projects</h2></div><div class="related-projects"><?php foreach ($relatedProjects as $related): ?><article class="card"><div class="card__content"><h3 class="card__title"><?= e($related['title']) ?></h3><p><?= e($related['short_description']) ?></p><a class="button button--text" href="project.php?slug=<?= rawurlencode($related['slug']) ?>">View details <span aria-hidden="true">→</span></a></div></article><?php endforeach; ?></div></div></section><?php endif; ?>
    <?php endif; ?>
  </main>
<?php require __DIR__ . '/includes/footer.php'; ?>
