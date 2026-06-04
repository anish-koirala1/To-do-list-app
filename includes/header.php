<?php
/**
 * Page Header Template - header.php
 * 
 * Renders the HTML head section and main navigation header for all protected pages.
 * This template is included at the top of all page views.
 * 
 * Expected Variables (set by calling page):
 * - $pageTitle (string): The title of the current page (used in <title> tag)
 * - $baseUrl (string, optional): Relative path to app root (defaults to '../')
 */

// Set default page title if not provided by the calling page
if (!isset($pageTitle)) {
    $pageTitle = 'APSU';
}

// Load role-checking helper functions
require_once __DIR__ . '/helpers.php';

// Determine the relative path to the app root (needed because this is included from various depths)
$base = $baseUrl ?? '../';

// Get the current page path for navigation highlighting
$self = $_SERVER['PHP_SELF'] ?? '';

// Get the current user's role to customize the navigation
$role = $_SESSION['role'] ?? '';

// Build the dashboard URL
$dashHref = $base . 'dashboard/index.php';

// Helper function: Returns 'active' class if the current page matches the needle
$active = static function (string $needle) use ($self): string {
    return strpos($self, $needle) !== false ? 'active' : '';
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title shown in browser tab -->
    <title><?= htmlspecialchars($pageTitle) ?> &mdash; APSU</title>
    <!-- Global stylesheet for all pages -->
    <link rel="stylesheet" href="<?= htmlspecialchars($base) ?>assets/css/style.css">
</head>
<!-- Body class includes the user's role for role-specific styling -->
<body class="app-role-<?= strtolower(htmlspecialchars($role)) ?>">

<!-- Site header with navigation -->
<header class="site-header">
    <div class="header-inner">
        <!-- Branding: Logo and app name that links to dashboard -->
        <a href="<?= htmlspecialchars($dashHref) ?>" class="site-brand">
            <!-- SVG icon representing a user/people -->
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            APSU
        </a>

        <!-- Main navigation menu -->
        <nav class="site-nav" aria-label="Main">
            <!-- Dashboard link -->
            <a href="<?= htmlspecialchars($dashHref) ?>" class="nav-link <?= $active('/dashboard/') ?>">Dashboard</a>

            <!-- Users menu: Admin only -->
            <?php if (isAdmin()): ?>
            <a href="<?= htmlspecialchars($base) ?>users/list_users.php" class="nav-link <?= $active('list_users') ?>">Users</a>
            <?php endif; ?>

            <!-- Reports: Different label for students vs staff -->
            <a href="<?= htmlspecialchars($base) ?>reports/index.php" class="nav-link <?= $active('/reports/') ?>">
                <?= isStudent() ? 'My Reports' : 'Reports' ?>
            </a>

            <!-- Classes/Schedule: Different label for students vs staff -->
            <a href="<?= htmlspecialchars($base) ?>classes/index.php" class="nav-link <?= $active('/classes/') ?>">
                <?= isStudent() ? 'My Schedule' : 'Classes' ?>
            </a>

            <!-- Exams menu -->
            <a href="<?= htmlspecialchars($base) ?>exams/index.php" class="nav-link <?= $active('/exams/') ?>">
                <?= isStudent() ? 'Exams' : 'Exams' ?>
            </a>

            <!-- Assignments/Work: Different label for students vs staff -->
            <a href="<?= htmlspecialchars($base) ?>assignments/index.php" class="nav-link <?= $active('/assignments/') ?>">
                <?= isStudent() ? 'My Work' : 'Assignments' ?>
            </a>

            <!-- Add User button: Admin only -->
            <?php if (isAdmin()): ?>
            <a href="<?= htmlspecialchars($base) ?>users/add_user.php" class="nav-link">+ User</a>
            <?php endif; ?>
        </nav>

        <!-- User info and logout in top right -->
        <div class="header-user">
            <!-- Display user's name and role -->
            <span class="user-badge">
                <?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>
                <!-- Role badge with role-specific styling -->
                <span class="role-tag role-tag-<?= strtolower(htmlspecialchars($role)) ?>"><?= htmlspecialchars($role) ?></span>
            </span>
            <!-- Logout button -->
            <a href="<?= htmlspecialchars($base) ?>auth/logout.php" class="btn btn-outline btn-sm">Logout</a>
        </div>
    </div>
</header>

<!-- Main content area (closed in footer.php) -->
<main class="main-content">
