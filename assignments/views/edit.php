<?php
$pageTitle = 'Edit Assignment';
$baseUrl = '../../';
require __DIR__ . '/../../includes/header.php';
?>

<div class="page-header"><h1>Edit Assignment</h1></div>

<div class="form-card" style="max-width:600px;">
    <form method="POST" class="user-form">
        <div class="form-group">
            <label>Task Name</label>
            <input type="text" name="task_name" class="form-control"
                   value="<?= htmlspecialchars($assignment['task_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Assigned To</label>
            <input type="text" name="assigned_to" class="form-control"
                   value="<?= htmlspecialchars($assignment['assigned_to']) ?>" required>
        </div>
        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control"
                   value="<?= htmlspecialchars($assignment['due_date']) ?>" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <?php foreach (['Pending', 'In Progress', 'Completed'] as $s): ?>
                <option value="<?= $s ?>" <?= $assignment['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php?action=index" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
