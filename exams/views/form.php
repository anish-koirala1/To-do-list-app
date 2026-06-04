<?php /** Create exam — link to class, title, subject, duration. */ require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title">Create Exam</h1></div>
<div class="card form-card"><div class="card-body"><form method="POST">
<div class="form-group"><label>Scheduled Class</label><select name="scheduled_class_id" class="form-control"><option value="">—</option>
<?php foreach ($classes as $c): ?><option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Title</label><input name="title" class="form-control" required></div>
<div class="form-group"><label>Subject</label><input name="subject" class="form-control" required></div>
<div class="form-group"><label>Duration (minutes)</label><input type="number" name="duration_minutes" class="form-control" value="60" min="5"></div>
<button class="btn btn-primary">Create</button> <a href="index.php" class="btn btn-outline">Cancel</a>
</form></div></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
