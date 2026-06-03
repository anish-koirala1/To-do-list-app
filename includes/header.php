<?php
if (!isset($pageTitle)) {
    $pageTitle = 'User Management';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> &mdash; User Management</title>
    <link rel="stylesheet" href="<?= $baseUrl ?? '../' ?>assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a href="<?= $baseUrl ?? '../' ?>users/list_users.php" class="site-brand">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            UserMgmt
        </a>

        <nav class="site-nav">
            <a href="<?= $baseUrl ?? '../' ?>users/list_users.php" class="nav-link <?= (strpos($_SERVER['PHP_SELF'], 'list_users') !== false) ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
                All Users
            </a>
            <?php if (isAdmin()): ?>
            <a href="<?= $baseUrl ?? '../' ?>users/add_user.php" class="nav-link <?= (strpos($_SERVER['PHP_SELF'], 'add_user') !== false) ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add User
            </a>
            <?php endif; ?>
        </nav>

        <div class="header-user">
            <span class="user-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>
                <span class="role-tag"><?= htmlspecialchars($_SESSION['role'] ?? '') ?></span>
            </span>
            <a href="<?= $baseUrl ?? '../' ?>auth/logout.php" class="btn btn-outline btn-sm">Logout</a>
        </div>
    </div>
</header>

<main class="main-content">