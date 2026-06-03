<?php
$pageTitle = 'Search Assignments';
$baseUrl = '../../';
require __DIR__ . '/../../includes/header.php';
?>

<div class="page-header"><h1>Search Assignment</h1></div>

<form method="GET" action="index.php" class="filter-bar" style="margin-bottom:1.5rem;">
    <input type="hidden" name="action" value="search">
    <div class="filter-group">
        <input type="number" name="search_id" class="form-control" placeholder="Assignment ID" required>
    </div>
    <div class="filter-actions">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="index.php?action=index" class="btn btn-outline">Back</a>
    </div>
</form>

<?php if (!empty($assignments)): ?>
<div class="table-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Task Name</th>
                <th>Assigned To</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assignments as $assignment): ?>
            <tr>
                <td><?= (int)$assignment['id'] ?></td>
                <td><?= htmlspecialchars($assignment['task_name']) ?></td>
                <td><?= htmlspecialchars($assignment['assigned_to']) ?></td>
                <td><?= htmlspecialchars($assignment['due_date']) ?></td>
                <td><?= htmlspecialchars($assignment['status']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
