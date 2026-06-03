<?php require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header"><h1 class="page-title">Take: <?= htmlspecialchars($ex['title'] ?? '') ?></h1></div>
<form method="POST">
<input type="hidden" name="attempt_id" value="<?= (int)$attemptId ?>">
<?php foreach ($questions as $i => $q): ?>
<div class="card" style="margin-bottom:14px;padding:18px;">
<p><strong>Q<?= $i+1 ?>.</strong> <?= htmlspecialchars($q['question_text']) ?></p>
<?php foreach (['A'=>'option_a','B'=>'option_b','C'=>'option_c','D'=>'option_d'] as $L => $col): ?>
<label style="display:block;margin:6px 0;"><input type="radio" name="answer[<?= (int)$q['id'] ?>]" value="<?= $L ?>" required>
 <?= $L ?>) <?= htmlspecialchars($q[$col]) ?></label>
<?php endforeach; ?>
</div>
<?php endforeach; ?>
<button type="submit" class="btn btn-primary">Submit Exam</button>
</form>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
