<?php require __DIR__ . '/../../includes/header.php'; ?>
<div class="card form-card"><div class="card-body"><form method="POST">
<div class="form-group"><label>Question</label><textarea name="question_text" class="form-control" rows="3" required></textarea></div>
<div class="form-row">
<div class="form-group"><label>A</label><input name="option_a" class="form-control" required></div>
<div class="form-group"><label>B</label><input name="option_b" class="form-control" required></div>
</div>
<div class="form-row">
<div class="form-group"><label>C</label><input name="option_c" class="form-control" required></div>
<div class="form-group"><label>D</label><input name="option_d" class="form-control" required></div>
</div>
<div class="form-group"><label>Correct</label><select name="correct_option" class="form-control"><option>A</option><option>B</option><option>C</option><option>D</option></select></div>
<button class="btn btn-primary">Save</button>
</form></div></div>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
