<?php

/** Escapes text before it is inserted into HTML. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Sends the visitor to a local application path and stops execution. */
function redirect(string $path): void
{
    header('Location: ' . $path, true, 302);
    exit;
}

/** Allows a simple lowercase, hyphen-separated project slug. */
function isValidSlug(string $slug): bool
{
    return (bool) preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug);
}
