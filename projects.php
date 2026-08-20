<?php

require_once __DIR__ . '/functions.php';

/**
 * Fetches published projects for a future dynamic project listing.
 * The Phase 6 HTML project list remains static until Phase 9.
 */
function getPublishedProjects(PDO $database, ?int $limit = null): array
{
    $sql = 'SELECT
                projects.id,
                projects.title,
                projects.slug,
                projects.short_description,
                projects.cover_image,
                project_categories.name AS category_name,
                project_categories.slug AS category_slug,
                projects.is_featured
            FROM projects
            LEFT JOIN project_categories ON project_categories.id = projects.category_id
            WHERE projects.is_published = 1
            ORDER BY projects.is_featured DESC, projects.created_at DESC';

    if ($limit !== null) {
        $sql .= ' LIMIT :limit';
    }

    $statement = $database->prepare($sql);

    if ($limit !== null) {
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
    }

    $statement->execute();

    return $statement->fetchAll();
}

/** Fetches one published project by its public slug. */
function getPublishedProjectBySlug(PDO $database, string $slug): ?array
{
    if (!isValidSlug($slug)) {
        return null;
    }

    $statement = $database->prepare(
        'SELECT
            projects.*,
            project_categories.name AS category_name,
            project_categories.slug AS category_slug
        FROM projects
        LEFT JOIN project_categories ON project_categories.id = projects.category_id
        WHERE projects.slug = :slug AND projects.is_published = 1
        LIMIT 1'
    );
    $statement->execute(['slug' => $slug]);
    $project = $statement->fetch();

    return $project ?: null;
}

/** Returns the categories that have at least one published project. */
function getPublishedProjectCategories(PDO $database): array
{
    $statement = $database->query(
        'SELECT project_categories.id, project_categories.name, project_categories.slug
        FROM project_categories
        INNER JOIN projects ON projects.category_id = project_categories.id
        WHERE projects.is_published = 1
        GROUP BY project_categories.id, project_categories.name, project_categories.slug, project_categories.sort_order
        ORDER BY project_categories.sort_order ASC, project_categories.name ASC'
    );

    return $statement->fetchAll();
}

/** Returns ordered technology names for one project. */
function getProjectTechnologies(PDO $database, int $projectId): array
{
    $statement = $database->prepare(
        'SELECT technology_name
        FROM project_technologies
        WHERE project_id = :project_id
        ORDER BY sort_order ASC, technology_name ASC'
    );
    $statement->execute(['project_id' => $projectId]);

    return $statement->fetchAll(PDO::FETCH_COLUMN);
}

/** Returns ordered screenshots for one project. */
function getProjectImages(PDO $database, int $projectId): array
{
    $statement = $database->prepare(
        'SELECT image_path, alt_text
        FROM project_images
        WHERE project_id = :project_id
        ORDER BY is_cover DESC, sort_order ASC, id ASC'
    );
    $statement->execute(['project_id' => $projectId]);

    return $statement->fetchAll();
}

/** Returns up to three published projects in the same category. */
function getRelatedProjects(PDO $database, int $projectId, ?int $categoryId): array
{
    if ($categoryId === null) {
        return [];
    }

    $statement = $database->prepare(
        'SELECT id, title, slug, short_description, cover_image
        FROM projects
        WHERE is_published = 1
          AND category_id = :category_id
          AND id != :project_id
        ORDER BY is_featured DESC, created_at DESC
        LIMIT 3'
    );
    $statement->execute([
        'category_id' => $categoryId,
        'project_id' => $projectId,
    ]);

    return $statement->fetchAll();
}
