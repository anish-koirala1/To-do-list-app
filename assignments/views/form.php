<?php require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1></div>
<div class="card form-card"><div class="card-body"><form method="POST">
<div class="form-group"><label>Title</label><input name="title" class="form-control" required value="<?= htmlspecialchars($row['title'] ?? '') ?>"></div>
<div class="form-group"><label>Subject</label><input name="subject" class="form-control" required value="<?= htmlspecialchars($row['subject'] ?? '') ?>"></div>
<div class="form-row">
<div class="form-group"><label>Assign Date</label><input type="date" name="assign_date" class="form-control" required value="<?= htmlspecialchars($row['assign_date'] ?? '') ?>"></div>
<div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="form-control" required value="<?= htmlspecialchars($row['due_date'] ?? '') ?>"></div>
</div>
<div class="form-group"><label>Status</label><select name="status" class="form-control">
<option value="Open" <?= ($row['status'] ?? '') === 'Open' ? 'selected' : '' ?>>Open</option>
<option value="Closed" <?= ($row['status'] ?? '') === 'Closed' ? 'selected' : '' ?>>Closed</option>
</select></div>
<button class="btn btn-primary">Save</button> <a href="index.php" class="btn btn-outline">Cancel</a>
</form></div></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
