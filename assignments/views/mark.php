<?php /** Teacher marks one submission — marks, feedback, status. */ require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title">Mark: <?= htmlspecialchars($sub['student_name']) ?></h1></div>
<div class="card form-card"><div class="card-body">
<p><strong>Assignment:</strong> <?= htmlspecialchars($sub['assignment_title']) ?></p>
<p style="margin:12px 0;"><?= nl2br(htmlspecialchars($sub['submission_text'])) ?></p>
<form method="POST">
<div class="form-group"><label>Marks</label><input type="number" name="marks" class="form-control" min="0" max="100" value="<?= (int)($sub['marks'] ?? 0) ?>"></div>
<div class="form-group"><label>Feedback</label><textarea name="feedback" class="form-control" rows="4"><?= htmlspecialchars($sub['feedback'] ?? '') ?></textarea></div>
<div class="form-group"><label>Status</label><select name="status" class="form-control">
<option value="Marked">Marked</option><option value="Submitted">Submitted</option><option value="Late">Late</option>
</select></div>
<button class="btn btn-primary">Save marks</button>
</form>
</div></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
