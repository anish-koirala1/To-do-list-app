<?php
/** Schedule class form — optional report link, room, datetime range. */
require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1></div>
<div class="card form-card"><div class="card-body">
<form method="POST">
<div class="form-group"><label>Linked Report (optional)</label>
<select name="report_id" class="form-control"><option value="">— None —</option>
<?php foreach ($reports as $r): ?><option value="<?= (int)$r['id'] ?>" <?= (int)($row['report_id'] ?? 0) === (int)$r['id'] ? 'selected' : '' ?>><?= htmlspecialchars($r['title']) ?></option><?php endforeach; ?>
</select></div>
<div class="form-group"><label>Title</label><input name="title" class="form-control" required value="<?= htmlspecialchars($row['title'] ?? '') ?>"></div>
<div class="form-group"><label>Instructor</label><input name="instructor" class="form-control" required value="<?= htmlspecialchars($row['instructor'] ?? '') ?>"></div>
<div class="form-group"><label>Classroom</label><input name="classroom" class="form-control" required value="<?= htmlspecialchars($row['classroom'] ?? '') ?>"></div>
<div class="form-row">
<div class="form-group"><label>Start</label><input type="datetime-local" name="start_time" class="form-control" required value="<?= isset($row['start_time']) ? date('Y-m-d\TH:i', strtotime($row['start_time'])) : '' ?>"></div>
<div class="form-group"><label>End</label><input type="datetime-local" name="end_time" class="form-control" required value="<?= isset($row['end_time']) ? date('Y-m-d\TH:i', strtotime($row['end_time'])) : '' ?>"></div>
</div>
<div class="form-actions"><button class="btn btn-primary">Save</button><a href="index.php" class="btn btn-outline">Cancel</a></div>
</form>
</div></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
