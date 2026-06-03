<?php
if (!isset($pageTitle)) {
    $pageTitle = 'APSU';
}
require_once __DIR__ . '/helpers.php';

$base = $baseUrl ?? '../';
$self = $_SERVER['PHP_SELF'] ?? '';
$role = $_SESSION['role'] ?? '';
$dashHref = $base . 'dashboard/index.php';
$active = static function (string $needle) use ($self): string {
    return strpos($self, $needle) !== false ? 'active' : '';
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> &mdash; APSU</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($base) ?>assets/css/style.css">
</head>
<body class="app-role-<?= strtolower(htmlspecialchars($role)) ?>">

<header class="site-header">
    <div class="header-inner">
        <a href="<?= htmlspecialchars($dashHref) ?>" class="site-brand">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            APSU
        </a>

        <nav class="site-nav" aria-label="Main">
            <a href="<?= htmlspecialchars($dashHref) ?>" class="nav-link <?= $active('/dashboard/') ?>">Dashboard</a>

            <?php if (isAdmin()): ?>
            <a href="<?= htmlspecialchars($base) ?>users/list_users.php" class="nav-link <?= $active('list_users') ?>">Users</a>
            <?php endif; ?>

            <a href="<?= htmlspecialchars($base) ?>reports/index.php" class="nav-link <?= $active('/reports/') ?>">
                <?= isStudent() ? 'My Reports' : 'Reports' ?>
            </a>
            <a href="<?= htmlspecialchars($base) ?>classes/index.php" class="nav-link <?= $active('/classes/') ?>">
                <?= isStudent() ? 'My Schedule' : 'Classes' ?>
            </a>
            <a href="<?= htmlspecialchars($base) ?>exams/index.php" class="nav-link <?= $active('/exams/') ?>">
                <?= isStudent() ? 'Exams' : 'Exams' ?>
            </a>
            <a href="<?= htmlspecialchars($base) ?>assignments/index.php" class="nav-link <?= $active('/assignments/') ?>">
                <?= isStudent() ? 'My Work' : 'Assignments' ?>
            </a>

            <?php if (isAdmin()): ?>
            <a href="<?= htmlspecialchars($base) ?>users/add_user.php" class="nav-link">+ User</a>
            <?php endif; ?>
        </nav>

        <div class="header-user">
            <span class="user-badge">
                <?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>
                <span class="role-tag role-tag-<?= strtolower(htmlspecialchars($role)) ?>"><?= htmlspecialchars($role) ?></span>
            </span>
            <a href="<?= htmlspecialchars($base) ?>auth/logout.php" class="btn btn-outline btn-sm">Logout</a>
        </div>
    </div>
</header>

<main class="main-content">
