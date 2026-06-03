<?php require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title">Filter Assignments</h1></div>
<form method="GET" class="filter-bar card" style="margin-bottom:20px;"><input type="hidden" name="action" value="filter">
<input name="subject" class="form-control" placeholder="Subject" value="<?= htmlspecialchars($_GET['subject'] ?? '') ?>">
<select name="status" class="form-control"><option value="">All</option><option value="Open">Open</option><option value="Closed">Closed</option></select>
<button class="btn btn-primary">Filter</button></form>
<div class="table-card"><table class="data-table">
<thead><tr><th>Title</th><th>Subject</th><th>Status</th><th>Due</th></tr></thead>
<tbody><?php foreach ($items as $a): ?>
<tr><td><?= htmlspecialchars($a['title']) ?></td><td><?= htmlspecialchars($a['subject']) ?></td><td><?= htmlspecialchars($a['status']) ?></td><td><?= htmlspecialchars($a['due_date']) ?></td></tr>
<?php endforeach; ?></tbody></table></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
