<?php

require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'Prabin Bahadur Thapa | BIT Student';
$pageDescription = $pageDescription ?? 'Professional portfolio of Prabin Bahadur Thapa, a Bachelor of Information Technology student.';
$activePage = $activePage ?? '';

$navigationItems = [
    'home' => ['label' => 'Home', 'path' => 'index.html'],
    'about' => ['label' => 'About', 'path' => 'about.html'],
    'experience' => ['label' => 'Experience', 'path' => 'experience.html'],
    'skills' => ['label' => 'Skills', 'path' => 'skills.html'],
    'projects' => ['label' => 'Projects', 'path' => 'projects.php'],
    'contact' => ['label' => 'Contact', 'path' => 'contact.html'],
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= e($pageDescription) ?>">
  <title><?= e($pageTitle) ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/responsive.css">
  <script src="assets/js/theme.js" defer></script>
  <script src="assets/js/main.js" defer></script>
</head>
<body>
  <a class="skip-link" href="#main-content">Skip to main content</a>
  <header class="site-header">
    <div class="container header__inner">
      <a class="site-brand" href="index.html" aria-label="Prabin Bahadur Thapa home page">PBT.</a>
      <nav class="primary-navigation" id="primary-navigation" aria-label="Primary navigation">
        <ul class="nav-list">
          <?php foreach ($navigationItems as $key => $item): ?>
            <li><a class="nav-link" href="<?= e($item['path']) ?>"<?= $activePage === $key ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>
      <div class="header__actions">
        <button class="theme-toggle" type="button" aria-label="Switch to dark theme" aria-pressed="false"><span class="theme-toggle__icon" aria-hidden="true">◐</span><span class="visually-hidden">Toggle color theme</span></button>
        <button class="menu-toggle" type="button" aria-controls="primary-navigation" aria-expanded="false"><span aria-hidden="true">☰</span><span class="visually-hidden">Toggle navigation menu</span></button>
      </div>
    </div>
  </header>
