<?php
/** Report create/edit form — title, subject, priority, dates, status. */
require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1></div>
<div class="card form-card"><div class="card-body">
<?php if (!empty($errors)): ?>
    <div class="alert alert-error">Please fix the errors below before submitting.</div>
<?php endif; ?>
<!-- POST handled by reports/index.php (create or edit action) -->
<form method="POST" novalidate>
    <div class="form-group">
        <label>Title</label>
        <input name="title" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" maxlength="255" required value="<?= htmlspecialchars($report['title'] ?? '') ?>">
        <?php if (isset($errors['title'])): ?><p class="field-error"><?= htmlspecialchars($errors['title']) ?></p><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Subject</label>
        <input name="subject" class="form-control <?= isset($errors['subject']) ? 'is-invalid' : '' ?>" maxlength="255" required value="<?= htmlspecialchars($report['subject'] ?? '') ?>">
        <?php if (isset($errors['subject'])): ?><p class="field-error"><?= htmlspecialchars($errors['subject']) ?></p><?php endif; ?>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Priority</label>
            <select name="priority" class="form-control <?= isset($errors['priority']) ? 'is-invalid' : '' ?>">
                <?php foreach (['Low','Medium','High'] as $p): ?>
                <option value="<?= $p ?>" <?= ($report['priority'] ?? '') === $p ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['priority'])): ?><p class="field-error"><?= htmlspecialchars($errors['priority']) ?></p><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control <?= isset($errors['status']) ? 'is-invalid' : '' ?>">
                <?php foreach (['Pending','In Progress','Completed'] as $s): ?>
                <option value="<?= $s ?>" <?= ($report['status'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['status'])): ?><p class="field-error"><?= htmlspecialchars($errors['status']) ?></p><?php endif; ?>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Assign Date</label>
            <input type="date" name="assign_date" class="form-control <?= isset($errors['assign_date']) ? 'is-invalid' : '' ?>" required value="<?= htmlspecialchars($report['assign_date'] ?? '') ?>">
            <?php if (isset($errors['assign_date'])): ?><p class="field-error"><?= htmlspecialchars($errors['assign_date']) ?></p><?php endif; ?>
        </div>
        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control <?= isset($errors['due_date']) ? 'is-invalid' : '' ?>" required value="<?= htmlspecialchars($report['due_date'] ?? '') ?>">
            <?php if (isset($errors['due_date'])): ?><p class="field-error"><?= htmlspecialchars($errors['due_date']) ?></p><?php endif; ?>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="index.php" class="btn btn-outline">Cancel</a>
    </div>
</form>
</div></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
