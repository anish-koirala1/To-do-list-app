<?php
$pageTitle = 'Create Assignment';
$baseUrl = '../../';
require __DIR__ . '/../../includes/header.php';
?>

<div class="page-header"><h1>Create Assignment</h1></div>

<div class="form-card" style="max-width:600px;">
    <form method="POST" class="user-form">
        <div class="form-group">
            <label>Task Name</label>
            <input type="text" name="task_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Assigned To</label>
            <input type="text" name="assigned_to" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Assignment</button>
            <a href="index.php?action=index" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
