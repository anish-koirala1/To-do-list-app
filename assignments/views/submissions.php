<?php /** Teacher view: all submissions for one assignment. */ require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title">Submissions: <?= htmlspecialchars($assignment['title'] ?? '') ?></h1><a href="index.php" class="btn btn-outline">Back</a></div>
<div class="table-card"><table class="data-table">
<thead><tr><th>Student</th><th>Submitted</th><th>Status</th><th>Marks</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach ($submissions as $s): ?>
<tr>
<td><?= htmlspecialchars($s['student_name']) ?></td>
<td><?= htmlspecialchars($s['submitted_at']) ?></td>
<td><?= htmlspecialchars($s['status']) ?></td>
<td><?= $s['marks'] !== null ? (int)$s['marks'] : '—' ?></td>
<td><a href="index.php?action=mark&sid=<?= (int)$s['id'] ?>" class="btn btn-sm btn-primary">Mark</a></td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
