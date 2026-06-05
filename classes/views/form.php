<?php
/** Schedule class form — optional report link, room, datetime range. */
require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1></div>
<div class="card form-card"><div class="card-body">
<?php if (!empty($errors)): ?>
    <div class="alert alert-error">Please fix the errors below before submitting.</div>
<?php endif; ?>
<form method="POST" novalidate>
    <div class="form-group">
        <label>Linked Report (optional)</label>
        <select name="report_id" class="form-control <?= isset($errors['report_id']) ? 'is-invalid' : '' ?>">
            <option value="">— None —</option>
            <?php foreach ($reports as $r): ?>
            <option value="<?= (int)$r['id'] ?>" <?= (int)($row['report_id'] ?? 0) === (int)$r['id'] ? 'selected' : '' ?>><?= htmlspecialchars($r['title']) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['report_id'])): ?><p class="field-error"><?= htmlspecialchars($errors['report_id']) ?></p><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Title</label>
        <input name="title" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" maxlength="255" required value="<?= htmlspecialchars($row['title'] ?? '') ?>">
        <?php if (isset($errors['title'])): ?><p class="field-error"><?= htmlspecialchars($errors['title']) ?></p><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Instructor</label>
        <input name="instructor" class="form-control <?= isset($errors['instructor']) ? 'is-invalid' : '' ?>" maxlength="255" required value="<?= htmlspecialchars($row['instructor'] ?? '') ?>">
        <?php if (isset($errors['instructor'])): ?><p class="field-error"><?= htmlspecialchars($errors['instructor']) ?></p><?php endif; ?>
    </div>
    <div class="form-group">
        <label>Classroom</label>
        <input name="classroom" class="form-control <?= isset($errors['classroom']) ? 'is-invalid' : '' ?>" maxlength="255" required value="<?= htmlspecialchars($row['classroom'] ?? '') ?>">
        <?php if (isset($errors['classroom'])): ?><p class="field-error"><?= htmlspecialchars($errors['classroom']) ?></p><?php endif; ?>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label>Start</label>
            <input type="datetime-local" name="start_time" class="form-control <?= isset($errors['start_time']) ? 'is-invalid' : '' ?>" required value="<?= isset($row['start_time']) && $row['start_time'] !== '' ? date('Y-m-d\TH:i', strtotime($row['start_time'])) : '' ?>">
            <?php if (isset($errors['start_time'])): ?><p class="field-error"><?= htmlspecialchars($errors['start_time']) ?></p><?php endif; ?>
        </div>
        <div class="form-group">
            <label>End</label>
            <input type="datetime-local" name="end_time" class="form-control <?= isset($errors['end_time']) ? 'is-invalid' : '' ?>" required value="<?= isset($row['end_time']) && $row['end_time'] !== '' ? date('Y-m-d\TH:i', strtotime($row['end_time'])) : '' ?>">
            <?php if (isset($errors['end_time'])): ?><p class="field-error"><?= htmlspecialchars($errors['end_time']) ?></p><?php endif; ?>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="index.php" class="btn btn-outline">Cancel</a>
    </div>
</form>
</div></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
