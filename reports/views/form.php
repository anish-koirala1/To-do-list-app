<?php
/** Report create/edit form — title, subject, priority, dates, status. */
require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1></div>
<div class="card form-card"><div class="card-body">
<!-- POST handled by reports/index.php (create or edit action) -->
<form method="POST">
    <div class="form-group"><label>Title</label><input name="title" class="form-control" required value="<?= htmlspecialchars($report['title']) ?>"></div>
    <div class="form-group"><label>Subject</label><input name="subject" class="form-control" required value="<?= htmlspecialchars($report['subject']) ?>"></div>
    <div class="form-row">
        <div class="form-group"><label>Priority</label>
            <select name="priority" class="form-control"><?php foreach (['Low','Medium','High'] as $p): ?>
                <option value="<?= $p ?>" <?= ($report['priority'] ?? '') === $p ? 'selected' : '' ?>><?= $p ?></option>
            <?php endforeach; ?></select>
        </div>
        <div class="form-group"><label>Status</label>
            <select name="status" class="form-control"><?php foreach (['Pending','In Progress','Completed'] as $s): ?>
                <option value="<?= $s ?>" <?= ($report['status'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?></select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group"><label>Assign Date</label><input type="date" name="assign_date" class="form-control" required value="<?= htmlspecialchars($report['assign_date'] ?? '') ?>"></div>
        <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="form-control" required value="<?= htmlspecialchars($report['due_date'] ?? '') ?>"></div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="index.php" class="btn btn-outline">Cancel</a>
    </div>
</form>
</div></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
