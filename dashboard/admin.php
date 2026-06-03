<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="role-dashboard role-dashboard-admin">
    <div class="dash-welcome">
        <span class="eyebrow">Administrator</span>
        <h1 class="page-title">Welcome, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Admin') ?></h1>
        <p class="page-subtitle">Manage university accounts and oversee all academic modules.</p>
    </div>

    <?php if (!empty($flash)): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></div>
    <?php endif; ?>

    <div class="dash-grid">
        <a href="<?= $baseUrl ?>users/list_users.php" class="dash-card dash-card-primary">
            <span class="dash-icon" aria-hidden="true">👥</span>
            <strong>User Management</strong>
            <p>Add, edit, enable, or disable staff and student accounts.</p>
            <span class="dash-cta">Open users →</span>
        </a>
        <a href="<?= $baseUrl ?>reports/index.php" class="dash-card">
            <span class="dash-icon" aria-hidden="true">📊</span>
            <strong>Reports</strong>
            <p>View and manage progress reports across the institution.</p>
            <span class="dash-cta">Open reports →</span>
        </a>
        <a href="<?= $baseUrl ?>classes/index.php" class="dash-card">
            <span class="dash-icon" aria-hidden="true">📅</span>
            <strong>Scheduled Classes</strong>
            <p>Review class timetables and room assignments.</p>
            <span class="dash-cta">Open classes →</span>
        </a>
        <a href="<?= $baseUrl ?>exams/index.php" class="dash-card">
            <span class="dash-icon" aria-hidden="true">📝</span>
            <strong>Exams</strong>
            <p>Manage exams, questions, and student attempts.</p>
            <span class="dash-cta">Open exams →</span>
        </a>
        <a href="<?= $baseUrl ?>assignments/index.php" class="dash-card">
            <span class="dash-icon" aria-hidden="true">📎</span>
            <strong>Assignments</strong>
            <p>Oversee assignments, submissions, and marking.</p>
            <span class="dash-cta">Open assignments →</span>
        </a>
        <a href="<?= $baseUrl ?>users/add_user.php" class="dash-card dash-card-accent">
            <span class="dash-icon" aria-hidden="true">➕</span>
            <strong>Quick Add User</strong>
            <p>Register a new admin, teacher, or student.</p>
            <span class="dash-cta">Add user →</span>
        </a>
    </div>

    <aside class="dash-note card">
        <div class="card-body">
            <p><strong>Your role:</strong> Full system access. Teachers handle day-to-day scheduling and academics; you control accounts.</p>
        </div>
    </aside>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
