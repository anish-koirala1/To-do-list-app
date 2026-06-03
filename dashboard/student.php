<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="role-dashboard role-dashboard-student">
    <div class="dash-welcome">
        <span class="eyebrow">Student portal</span>
        <h1 class="page-title">Welcome, <?= htmlspecialchars($_SESSION['full_name'] ?? 'Student') ?></h1>
        <p class="page-subtitle">View your schedule and reports, take exams, and submit assignments. You cannot change university records—only complete your tasks.</p>
    </div>

    <?php if (!empty($flash)): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></div>
    <?php endif; ?>

    <div class="dash-grid">
        <a href="<?= $baseUrl ?>classes/index.php" class="dash-card dash-card-primary">
            <span class="dash-icon" aria-hidden="true">📅</span>
            <strong>My Schedule</strong>
            <p>See your enrolled classes, rooms, and times.</p>
            <span class="dash-cta">View schedule →</span>
        </a>
        <a href="<?= $baseUrl ?>reports/index.php" class="dash-card">
            <span class="dash-icon" aria-hidden="true">📊</span>
            <strong>My Reports</strong>
            <p>Read-only view of your academic progress reports.</p>
            <span class="dash-cta">View reports →</span>
        </a>
        <a href="<?= $baseUrl ?>exams/index.php" class="dash-card dash-card-accent">
            <span class="dash-icon" aria-hidden="true">📝</span>
            <strong>Take Exams</strong>
            <p>Start available exams and submit your answers.</p>
            <span class="dash-cta">Go to exams →</span>
        </a>
        <a href="<?= $baseUrl ?>assignments/index.php" class="dash-card">
            <span class="dash-icon" aria-hidden="true">📎</span>
            <strong>My Assignments</strong>
            <p>Submit coursework for open assignments.</p>
            <span class="dash-cta">View assignments →</span>
        </a>
        <a href="<?= $baseUrl ?>exams/index.php?action=attempts" class="dash-card">
            <span class="dash-icon" aria-hidden="true">✓</span>
            <strong>My Exam Results</strong>
            <p>Review scores from completed exams.</p>
            <span class="dash-cta">View results →</span>
        </a>
    </div>

    <aside class="dash-note card">
        <div class="card-body">
            <p><strong>Read-only access:</strong> Schedules and reports are provided by your teachers. Use the actions above to take exams and submit assignments only.</p>
        </div>
    </aside>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
