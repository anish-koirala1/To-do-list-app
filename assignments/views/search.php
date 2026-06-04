<?php /** Staff search assignments by title/subject (?q=). */ require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title">Search Assignments</h1></div>
<form method="GET" class="filter-bar card" style="margin-bottom:20px;"><input type="hidden" name="action" value="search">
<input name="q" class="form-control" placeholder="Title or subject" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
<button class="btn btn-primary">Search</button></form>
<div class="table-card"><table class="data-table">
<thead><tr><th>Title</th><th>Subject</th><th>Due</th></tr></thead>
<tbody><?php foreach ($items as $a): ?>
<tr><td><?= htmlspecialchars($a['title']) ?></td><td><?= htmlspecialchars($a['subject']) ?></td><td><?= htmlspecialchars($a['due_date']) ?></td></tr>
<?php endforeach; ?></tbody></table></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
