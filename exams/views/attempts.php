<?php
/** Exam results table — all attempts (staff) or current student only. */
require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title">Exam Attempts</h1><a href="index.php" class="btn btn-outline">Back</a></div>
<div class="table-card"><table class="data-table">
<thead><tr><th>Exam</th><th>Student</th><th>Score</th><th>Total</th><th>Started</th><th>Completed</th></tr></thead>
<tbody>
<?php foreach ($attempts as $a): ?>
<tr>
<td><?= htmlspecialchars($a['exam_title']) ?></td>
<td><?= htmlspecialchars($a['full_name']) ?></td>
<td><?= (int)$a['score'] ?></td>
<td><?= (int)$a['total_questions'] ?></td>
<td><?= htmlspecialchars($a['started_at']) ?></td>
<td><?= htmlspecialchars($a['completed_at'] ?? '—') ?></td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
