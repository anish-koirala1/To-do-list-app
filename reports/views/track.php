<?php
/**
 * Progress tracking view — summary stats, completion bar, report table.
 * Variables: $stats, $reports (from reports/index.php?action=track).
 */
$total = (int)($stats['total'] ?? 0);
$done = (int)($stats['completed'] ?? 0);
$pct = $total > 0 ? (int)round(($done / $total) * 100) : 0;
require __DIR__ . '/../../includes/header.php';
?>
<div class="page-header">
    <div><h1 class="page-title">Track Progress</h1><p class="page-subtitle">Report completion overview</p></div>
    <a href="index.php" class="btn btn-outline">Back to Reports</a>
</div>
<div class="stats-row">
    <div class="stat-card accent-primary"><span class="stat-label">Total</span><span class="stat-value"><?= $total ?></span></div>
    <div class="stat-card accent-warning"><span class="stat-label">Pending</span><span class="stat-value"><?= (int)$stats['pending'] ?></span></div>
    <div class="stat-card accent-primary"><span class="stat-label">In Progress</span><span class="stat-value"><?= (int)$stats['in_progress'] ?></span></div>
    <div class="stat-card accent-success"><span class="stat-label">Completed</span><span class="stat-value"><?= $done ?> (<?= $pct ?>%)</span></div>
</div>
<div class="progress-block card" style="padding:22px;margin-bottom:24px;">
    <div class="progress-meta"><span>Overall completion</span><strong><?= $pct ?>%</strong></div>
    <div class="progress-track"><div class="progress-fill accent-success" style="width:<?= $pct ?>%"></div></div>
</div>
<div class="table-card">
    <table class="data-table">
        <thead><tr><th>Title</th><th>Subject</th><th>Status</th><th>Due</th></tr></thead>
        <tbody>
        <?php foreach ($reports as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= htmlspecialchars($r['subject']) ?></td>
            <td><?= htmlspecialchars($r['status']) ?></td>
            <td><?= htmlspecialchars($r['due_date']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
