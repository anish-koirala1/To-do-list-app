<?php require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header">
<div><h1 class="page-title">Exams</h1><p class="page-subtitle">Exam module — Puskar Bastola (linked to scheduled classes)</p></div>
<?php if (isTeacher()): ?><a href="index.php?action=create" class="btn btn-primary">+ Create Exam</a><?php endif; ?>
<a href="index.php?action=attempts" class="btn btn-outline">View Attempts</a>
</div>
<?php if (!empty($flash)): ?><div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></div><?php endif; ?>
<div class="table-card"><table class="data-table">
<thead><tr><th>ID</th><th>Title</th><th>Subject</th><th>Duration</th><th>Class</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach ($list as $e): ?>
<tr>
<td><?= (int)$e['id'] ?></td>
<td><?= htmlspecialchars($e['title']) ?></td>
<td><?= htmlspecialchars($e['subject']) ?></td>
<td><?= (int)$e['duration_minutes'] ?> min</td>
<td><?= htmlspecialchars($e['class_title'] ?? '—') ?></td>
<td>
<a href="index.php?action=questions&exam_id=<?= (int)$e['id'] ?>" class="btn btn-sm btn-outline">Questions</a>
<a href="index.php?action=take&exam_id=<?= (int)$e['id'] ?>" class="btn btn-sm btn-primary">Take</a>
<?php if (isTeacher()): ?><a href="index.php?action=delete&id=<?= (int)$e['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a><?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
