<?php
$pageTitle = 'Delete Assignment';
$baseUrl = '../../';
require __DIR__ . '/../../includes/header.php';
?>

<div class="form-card" style="max-width:500px;">
    <h1>Delete Assignment</h1>
    <p>Are you sure you want to delete this assignment?</p>
    <p><strong>Task:</strong> <?= htmlspecialchars($assignment['task_name']) ?></p>
    <p><strong>Assigned To:</strong> <?= htmlspecialchars($assignment['assigned_to']) ?></p>
    <form method="POST" class="form-actions" style="margin-top:1rem;">
        <button type="submit" class="btn btn-danger">Yes, Delete</button>
        <a href="index.php?action=index" class="btn btn-outline">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
