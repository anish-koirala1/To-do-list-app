<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="role-dashboard role-dashboard-teacher">
    <div class="dash-welcome">
        <span class="eyebrow">Faculty portal</span>
        <h1 class="page-title">Welcome, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Teacher') ?></h1>
        <p class="page-subtitle">Schedule classes, manage reports, create exams, and mark assignments. User accounts are managed by administrators only.</p>
    </div>

    <?php if (!empty($flash)): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></div>
    <?php endif; ?>

    <div class="dash-grid">
        <a href="<?= $baseUrl ?>reports/index.php" class="dash-card dash-card-primary">
            <span class="dash-icon" aria-hidden="true">📊</span>
            <strong>Reports &amp; Progress</strong>
            <p>Create reports, update status, and track student progress.</p>
            <span class="dash-cta">Manage reports →</span>
        </a>
        <a href="<?= $baseUrl ?>classes/index.php" class="dash-card">
            <span class="dash-icon" aria-hidden="true">📅</span>
            <strong>Schedule Classes</strong>
            <p>Set timetables, instructors, rooms, and link to reports.</p>
            <span class="dash-cta">Schedule class →</span>
        </a>
        <a href="<?= $baseUrl ?>exams/index.php" class="dash-card">
            <span class="dash-icon" aria-hidden="true">📝</span>
            <strong>Exams</strong>
            <p>Create exams, add MCQ questions, and review attempts.</p>
            <span class="dash-cta">Manage exams →</span>
        </a>
        <a href="<?= $baseUrl ?>assignments/index.php" class="dash-card">
            <span class="dash-icon" aria-hidden="true">📎</span>
            <strong>Assignments</strong>
            <p>Publish work, view submissions, and enter marks.</p>
            <span class="dash-cta">Manage assignments →</span>
        </a>
        <a href="<?= $baseUrl ?>reports/index.php?action=track" class="dash-card dash-card-accent">
            <span class="dash-icon" aria-hidden="true">📈</span>
            <strong>Track Progress</strong>
            <p>Dashboard of report completion and priorities.</p>
            <span class="dash-cta">View progress →</span>
        </a>
        <a href="<?= $baseUrl ?>exams/index.php?action=attempts" class="dash-card">
            <span class="dash-icon" aria-hidden="true">✓</span>
            <strong>Exam Attempts</strong>
            <p>See how students performed on assessments.</p>
            <span class="dash-cta">View attempts →</span>
        </a>
    </div>

    <aside class="dash-note card">
        <div class="card-body">
            <p><strong>Restricted:</strong> User management (add/edit/delete accounts) is not available to teachers. Contact an administrator if you need a new account.</p>
        </div>
    </aside>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
