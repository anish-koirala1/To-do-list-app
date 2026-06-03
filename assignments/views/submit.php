<?php require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title">Submit: <?= htmlspecialchars($a['title']) ?></h1></div>
<div class="card form-card"><div class="card-body"><form method="POST">
<div class="form-group"><label>Your submission</label><textarea name="submission_text" class="form-control" rows="8" required></textarea></div>
<button class="btn btn-primary">Submit</button><a href="index.php" class="btn btn-outline">Cancel</a>
</form></div></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
