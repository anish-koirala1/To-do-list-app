<?php require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header">
<div>
    <h1 class="page-title">Exams</h1>
    <p class="page-subtitle">
        <?php if (isStudent()): ?>
        Take available exams and review your results.
        <?php else: ?>
        Exam module — create exams, questions, and review attempts (Puskar Bastola).
        <?php endif; ?>
    </p>
</div>
<?php if (canManageAcademics()): ?>
    <a href="index.php?action=create" class="btn btn-primary">+ Create Exam</a>
<?php endif; ?>
<a href="index.php?action=attempts" class="btn btn-outline"><?= isStudent() ? 'My Results' : 'View Attempts' ?></a>
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
<?php if (canManageAcademics()): ?>
<a href="index.php?action=questions&exam_id=<?= (int)$e['id'] ?>" class="btn btn-sm btn-outline">Questions</a>
<?php endif; ?>
<?php if (isStudent()): ?>
<a href="index.php?action=take&exam_id=<?= (int)$e['id'] ?>" class="btn btn-sm btn-primary">Take exam</a>
<?php endif; ?>
<?php if (canManageAcademics()): ?>
<a href="index.php?action=delete&id=<?= (int)$e['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
