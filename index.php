<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APSU — Academic Portal</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="landing-page">

<header class="landing-header">
    <a href="index.php" class="landing-brand">
        <span class="brand-mark">
            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </span>
        APSU
    </a>
    <a href="auth/login.php" class="btn btn-outline btn-sm">Sign In</a>
</header>

<main class="landing-main">
    <section class="landing-hero">
        <div class="hero-copy">
            <span class="eyebrow">Academic Portal System for University</span>
            <h1>One portal.<br>Three roles.</h1>
            <p>Administrators manage accounts. Teachers schedule classes, reports, and exams. Students view their schedule and complete assignments and exams.</p>
            <div class="hero-actions">
                <a href="auth/login.php" class="btn btn-primary">Sign in to your portal</a>
            </div>
            <div class="landing-role-hints">
                <span><strong>Admin</strong> — users</span>
                <span><strong>Teacher</strong> — academics</span>
                <span><strong>Student</strong> — tasks only</span>
            </div>
        </div>

        <div class="hero-panel landing-role-panel" aria-label="Role portals">
            <div class="panel-top">
                <span></span><span></span><span></span>
            </div>
            <div class="role-portal role-portal-admin">
                <div class="role-portal-head">
                    <span class="role-portal-badge badge-admin">Admin</span>
                    <strong>Control centre</strong>
                </div>
                <ul>
                    <li>User accounts &amp; roles</li>
                    <li>Enable / disable access</li>
                    <li>Oversee all modules</li>
                </ul>
            </div>
            <div class="role-portal role-portal-teacher">
                <div class="role-portal-head">
                    <span class="role-portal-badge badge-teacher">Teacher</span>
                    <strong>Faculty workspace</strong>
                </div>
                <ul>
                    <li>Reports &amp; progress</li>
                    <li>Schedule classes &amp; exams</li>
                    <li>Assignments &amp; marking</li>
                </ul>
            </div>
            <div class="role-portal role-portal-student">
                <div class="role-portal-head">
                    <span class="role-portal-badge badge-student">Student</span>
                    <strong>Learning hub</strong>
                </div>
                <ul>
                    <li>View schedule &amp; reports</li>
                    <li>Take exams</li>
                    <li>Submit assignments</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="landing-features" aria-label="Team modules">
        <article>
            <strong>User Management</strong>
            <p>Anish Koirala — Admin only: login, roles, user CRUD.</p>
        </article>
        <article>
            <strong>Reports &amp; Progress</strong>
            <p>Utsav Luitel — Teachers create; students view their reports.</p>
        </article>
        <article>
            <strong>Classes &amp; Exams</strong>
            <p>Puskar Bastola — Teachers schedule; students view &amp; take exams.</p>
        </article>
        <article>
            <strong>Assignments</strong>
            <p>Sunil Kumar BK — Teachers publish; students submit work.</p>
        </article>
    </section>
</main>

<footer class="landing-footer">
    &copy; <?= date('Y') ?> APSU — Academic Portal System for University
</footer>

</body>
</html>
