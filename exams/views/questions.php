<?php require __DIR__ . '/../../includes/header.php'; ?>
<div class="page-header">
<div><h1 class="page-title">Questions: <?= htmlspecialchars($ex['title'] ?? '') ?></h1></div>
<?php if (canManageAcademics()): ?><a href="index.php?action=add_question&exam_id=<?= (int)$_GET['exam_id'] ?>" class="btn btn-primary">+ Add Question</a><?php endif; ?>
<a href="index.php" class="btn btn-outline">Back</a>
</div>
<div class="table-card"><table class="data-table">
<thead><tr><th>#</th><th>Question</th><th>Correct</th></tr></thead>
<tbody>
<?php foreach ($questions as $i => $q): ?>
<tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($q['question_text']) ?></td><td><?= htmlspecialchars($q['correct_option']) ?></td></tr>
<?php endforeach; ?>
</tbody></table></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
