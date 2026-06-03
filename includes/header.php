<?php

if (!isset($pageTitle)) {

    $pageTitle = 'APSU';

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle) ?> &mdash; APSU</title>

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

            APSU

        </a>



        <nav class="site-nav">

            <a href="<?= $baseUrl ?? '../' ?>users/list_users.php" class="nav-link <?= (strpos($_SERVER['PHP_SELF'], 'list_users') !== false) ? 'active' : '' ?>">Users</a>

            <a href="<?= $baseUrl ?? '../' ?>reports/index.php" class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/reports/') !== false) ? 'active' : '' ?>">Reports</a>

            <a href="<?= $baseUrl ?? '../' ?>classes/index.php" class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/classes/') !== false) ? 'active' : '' ?>">Classes</a>

            <a href="<?= $baseUrl ?? '../' ?>exams/index.php" class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/exams/') !== false) ? 'active' : '' ?>">Exams</a>

            <a href="<?= $baseUrl ?? '../' ?>assignments/index.php" class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/assignments/') !== false) ? 'active' : '' ?>">Assignments</a>

            <?php if (isAdmin()): ?>

            <a href="<?= $baseUrl ?? '../' ?>users/add_user.php" class="nav-link">+ User</a>

            <?php endif; ?>

        </nav>



        <div class="header-user">

            <span class="user-badge">

                <?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>

                <span class="role-tag"><?= htmlspecialchars($_SESSION['role'] ?? '') ?></span>

            </span>

            <a href="<?= $baseUrl ?? '../' ?>auth/logout.php" class="btn btn-outline btn-sm">Logout</a>

        </div>

    </div>

</header>



<main class="main-content">

