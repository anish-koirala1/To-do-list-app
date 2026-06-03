<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: users/list_users.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
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
        UserMgmt
    </a>
    <a href="auth/login.php" class="btn btn-outline btn-sm">Sign In</a>
</header>

<main class="landing-main">
    <section class="landing-hero">
        <div class="hero-copy">
            <span class="eyebrow">Simple admin dashboard</span>
            <h1>User Management System</h1>
            <p>Manage users, roles, access, and account status from one clean PHP dashboard.</p>
            <div class="hero-actions">
                <a href="auth/login.php" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    Open Dashboard
                </a>
            </div>
        </div>

        <div class="hero-panel" aria-label="Dashboard preview">
            <div class="panel-top">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="preview-title">
                <div>
                    <strong>All Users</strong>
                    <small>Today overview</small>
                </div>
                <span class="badge badge-active">Live</span>
            </div>
            <div class="preview-stats">
                <div><span>128</span><small>Total</small></div>
                <div><span>112</span><small>Active</small></div>
                <div><span>16</span><small>Disabled</small></div>
            </div>
            <div class="preview-list">
                <div><span class="avatar">A</span><p>Admin User</p><small>Admin</small></div>
                <div><span class="avatar">T</span><p>Teacher User</p><small>Teacher</small></div>
                <div><span class="avatar">S</span><p>Student User</p><small>Student</small></div>
            </div>
        </div>
    </section>

    <section class="landing-features" aria-label="Core features">
        <article>
            <strong>Add & edit</strong>
            <p>Create users and update account details with validation.</p>
        </article>
        <article>
            <strong>Search & filter</strong>
            <p>Find users quickly by name, email, role, or status.</p>
        </article>
        <article>
            <strong>Secure access</strong>
            <p>Session login, hashed passwords, and role-based controls.</p>
        </article>
    </section>
</main>

<footer class="landing-footer">
    &copy; <?= date('Y') ?> User Management System
</footer>

</body>
</html>
