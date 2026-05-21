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
    <style>
        /* ---- Landing-specific overrides ---- */
        body {
            background: #f4f6f9;
            display: block;
        }

        /* Nav */
        .landing-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--color-border);
            padding: 0 24px;
        }
        .landing-nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .landing-brand {
            display: flex;
            align-items: center;
            gap: 9px;
            font-weight: 800;
            font-size: 1.05rem;
            color: var(--color-primary);
            text-decoration: none;
        }
        .landing-brand:hover { text-decoration: none; }

        /* Hero */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 90px 24px 80px;
            text-align: center;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 99px;
            padding: 5px 14px;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.2rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 18px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        .hero p {
            font-size: 1.05rem;
            opacity: .88;
            max-width: 520px;
            margin: 0 auto 36px;
            line-height: 1.7;
        }
        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            color: var(--color-primary);
            font-weight: 700;
            font-size: .95rem;
            padding: 13px 28px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(0,0,0,.15);
            transition: transform .15s, box-shadow .15s;
        }
        .hero-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,.18);
            text-decoration: none;
        }
        .hero-sub-cta {
            display: block;
            margin-top: 14px;
            font-size: .82rem;
            opacity: .75;
        }

        /* Stats strip */
        .stats-strip {
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            padding: 28px 24px;
        }
        .stats-strip-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            gap: 48px;
            flex-wrap: wrap;
        }
        .strip-stat { text-align: center; }
        .strip-stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--color-primary);
            line-height: 1;
        }
        .strip-stat-label {
            font-size: .78rem;
            color: var(--color-text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-top: 4px;
        }

        /* Features */
        .features-section {
            padding: 72px 24px;
            max-width: 1100px;
            margin: 0 auto;
        }
        .section-header {
            text-align: center;
            margin-bottom: 48px;
        }
        .section-label {
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--color-primary);
            margin-bottom: 10px;
        }
        .section-title {
            font-size: clamp(1.4rem, 3vw, 2rem);
            font-weight: 800;
            color: var(--color-text);
            margin-bottom: 10px;
        }
        .section-subtitle {
            font-size: .9rem;
            color: var(--color-text-muted);
            max-width: 460px;
            margin: 0 auto;
            line-height: 1.7;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .feature-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            padding: 28px 24px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .2s, transform .2s;
        }
        .feature-card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }
        .feature-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            background: var(--color-primary-lt);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }
        .feature-title {
            font-size: .95rem;
            font-weight: 700;
            color: var(--color-text);
            margin-bottom: 7px;
        }
        .feature-desc {
            font-size: .85rem;
            color: var(--color-text-muted);
            line-height: 1.65;
        }

        /* Roles section */
        .roles-section {
            background: var(--color-surface);
            border-top: 1px solid var(--color-border);
            border-bottom: 1px solid var(--color-border);
            padding: 64px 24px;
        }
        .roles-inner {
            max-width: 1100px;
            margin: 0 auto;
        }
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
        }
        .role-card {
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            padding: 24px;
            text-align: center;
        }
        .role-card .badge { font-size: .8rem; padding: 4px 14px; margin-bottom: 12px; }
        .role-card h3 { font-size: .95rem; font-weight: 700; margin-bottom: 8px; }
        .role-card p { font-size: .82rem; color: var(--color-text-muted); line-height: 1.6; }

        /* CTA banner */
        .cta-section {
            padding: 72px 24px;
            text-align: center;
        }
        .cta-box {
            max-width: 560px;
            margin: 0 auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: var(--radius-lg);
            padding: 52px 40px;
            color: #fff;
            box-shadow: var(--shadow-lg);
        }
        .cta-box h2 { font-size: 1.6rem; font-weight: 800; margin-bottom: 12px; }
        .cta-box p  { font-size: .9rem; opacity: .85; margin-bottom: 28px; line-height: 1.7; }

        /* Footer */
        .landing-footer {
            background: var(--color-surface);
            border-top: 1px solid var(--color-border);
            padding: 24px;
            text-align: center;
            font-size: .8rem;
            color: var(--color-text-light);
        }

        @media (max-width: 640px) {
            .roles-grid { grid-template-columns: 1fr; }
            .stats-strip-inner { gap: 28px; }
            .cta-box { padding: 36px 24px; }
            .hero { padding: 60px 20px 56px; }
        }
    </style>
</head>
<body>

<!-- Nav -->
<nav class="landing-nav">
    <div class="landing-nav-inner">
        <a href="index.php" class="landing-brand">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            UserMgmt
        </a>
        <a href="auth/login.php" class="btn btn-primary btn-sm">Sign In</a>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <h1>User Management &amp; Authentication System</h1>
    <p>A complete admin dashboard to manage users — add, edit, disable, search, and filter accounts with secure session-based authentication.</p>
    <a href="auth/login.php" class="hero-cta">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
        Go to Dashboard
    </a>
</section>

<!-- Stats strip -->
<div class="stats-strip">
    <div class="stats-strip-inner">
        <div class="strip-stat">
            <div class="strip-stat-value">3</div>
            <div class="strip-stat-label">User Roles</div>
        </div>
        <div class="strip-stat">
            <div class="strip-stat-value">7</div>
            <div class="strip-stat-label">Feature Modules</div>
        </div>
        <div class="strip-stat">
            <div class="strip-stat-value">100%</div>
            <div class="strip-stat-label">Raw PHP</div>
        </div>
        <div class="strip-stat">
            <div class="strip-stat-value">0</div>
            <div class="strip-stat-label">Frameworks Used</div>
        </div>
    </div>
</div>

<!-- Features -->
<section class="features-section">
    <div class="section-header">
        <p class="section-label">Features</p>
        <h2 class="section-title">Everything you need to manage users</h2>
        <p class="section-subtitle">Each module maps directly to a ticket in the PRD — from through.</p>
    </div>

    <div class="features-grid">

        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            </div>
            <p class="feature-title">Add User &mdash;</p>
            <p class="feature-desc">Create new accounts with full validation: unique email check, bcrypt password hashing, and role assignment.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </div>
            <p class="feature-title">Edit User &mdash; </p>
            <p class="feature-desc">Update name, email, role, and account status. Optional password reset section included inline.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
            </div>
            <p class="feature-title">Disable / Delete &mdash;</p>
            <p class="feature-desc">Soft-disable reverses access without data loss. Hard delete permanently removes the record. Both require JS confirmation.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
            </div>
            <p class="feature-title">List Users &mdash;</p>
            <p class="feature-desc">Paginated table showing all accounts with stats cards for totals, active count, and role breakdown.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <p class="feature-title">Search &mdash;</p>
            <p class="feature-desc">Live debounced search on name and email using SQL <code>LIKE</code>. Also exposes a <code>?format=json</code> AJAX endpoint.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
            </div>
            <p class="feature-title">Filter &mdash;</p>
            <p class="feature-desc">Filter by role (Admin / Teacher / Student) and account status. Dropdowns auto-submit on change with GET params.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
            <p class="feature-title">Login / Logout &mdash;</p>
            <p class="feature-desc">Session-based auth with <code>session_regenerate_id()</code> on login. Blocks disabled accounts. Stores user_id, name, and role.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>
            <p class="feature-title">Validation &mdash;</p>
            <p class="feature-desc">Email format + uniqueness, password strength (8+ chars, upper, lower, digit), and role whitelist — all server-side.</p>
        </div>

    </div>
</section>

<!-- Roles -->
<section class="roles-section">
    <div class="roles-inner">
        <div class="section-header">
            <p class="section-label">Access Control</p>
            <h2 class="section-title">Three built-in roles</h2>
            <p class="section-subtitle">Roles are stored in the database and enforced at the session level.</p>
        </div>
        <div class="roles-grid">
            <div class="role-card">
                <span class="badge badge-admin">Admin</span>
                <h3>Administrator</h3>
                <p>Full access to create, edit, disable, and delete any user account in the system.</p>
            </div>
            <div class="role-card">
                <span class="badge badge-teacher">Teacher</span>
                <h3>Teacher</h3>
                <p>Limited system access. Account credentials managed by administrators.</p>
            </div>
            <div class="role-card">
                <span class="badge badge-student">Student</span>
                <h3>Student</h3>
                <p>Basic user access. Can be enabled or disabled without losing account data.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-box">
        <h2>Ready to get started?</h2>
        <p>Sign in with the default admin account and explore the full dashboard.</p>
        <a href="auth/login.php" class="hero-cta">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
            Sign In
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="landing-footer">
    &copy; <?= date('Y') ?> User Management System &mdash; Built with Raw PHP &amp; MySQL
</footer>

</body>
</html>
