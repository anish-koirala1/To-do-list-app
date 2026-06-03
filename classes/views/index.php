<?php require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header">
    <div>
        <h1 class="page-title">Scheduled Classes</h1>
        <p class="page-subtitle">Task management — classes (Puskar Bastola)</p>
    </div>
    <a href="index.php?action=create" class="btn btn-primary">+ Schedule Class</a>
</div>
<?php if (!empty($flash)): ?><div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></div><?php endif; ?>
<div class="table-card">
<table class="data-table">
<thead><tr><th>ID</th><th>Title</th><th>Instructor</th><th>Room</th><th>Start</th><th>End</th><th>Report</th><th>Actions</th></tr></thead>
<tbody>
<?php if (empty($classes)): ?><tr><td colspan="8">No classes scheduled.</td></tr>
<?php else: foreach ($classes as $c): ?>
<tr>
<td><?= (int)$c['id'] ?></td>
<td><?= htmlspecialchars($c['title']) ?></td>
<td><?= htmlspecialchars($c['instructor']) ?></td>
<td><?= htmlspecialchars($c['classroom']) ?></td>
<td><?= htmlspecialchars($c['start_time']) ?></td>
<td><?= htmlspecialchars($c['end_time']) ?></td>
<td><?= htmlspecialchars($c['report_title'] ?? '—') ?></td>
<td>
<a href="../exams/index.php?class_id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline">Exams</a>
<a href="index.php?action=edit&id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
<a href="index.php?action=delete&id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
