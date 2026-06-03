<?php require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header">
<div><h1 class="page-title">Assignments</h1><p class="page-subtitle">Assignment module (Sunil Kumar BK)</p></div>
<?php if (isTeacher()): ?><a href="index.php?action=create" class="btn btn-primary">+ Create</a><?php endif; ?>
<a href="index.php?action=search" class="btn btn-outline">Search</a>
<a href="index.php?action=filter" class="btn btn-outline">Filter</a>
</div>
<?php if (!empty($flash)): ?><div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></div><?php endif; ?>
<div class="table-card"><table class="data-table">
<thead><tr><th>ID</th><th>Title</th><th>Subject</th><th>Teacher</th><th>Due</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach ($items as $a): ?>
<tr>
<td><?= (int)$a['id'] ?></td>
<td><?= htmlspecialchars($a['title']) ?></td>
<td><?= htmlspecialchars($a['subject']) ?></td>
<td><?= htmlspecialchars($a['teacher_name']) ?></td>
<td><?= htmlspecialchars($a['due_date']) ?></td>
<td><?= htmlspecialchars($a['status']) ?></td>
<td>
<?php if (isStudent() && $a['status'] === 'Open'): ?>
<a href="index.php?action=submit&id=<?= (int)$a['id'] ?>" class="btn btn-sm btn-primary">Submit</a>
<?php endif; ?>
<?php if (isTeacher()): ?>
<a href="index.php?action=submissions&id=<?= (int)$a['id'] ?>" class="btn btn-sm btn-outline">Submissions</a>
<a href="index.php?action=edit&id=<?= (int)$a['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
<a href="index.php?action=delete&id=<?= (int)$a['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
