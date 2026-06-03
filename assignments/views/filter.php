<?php
$pageTitle = 'Filter Assignments';
$baseUrl = '../../';
require __DIR__ . '/../../includes/header.php';
?>

<div class="page-header"><h1>Filtered Assignments</h1></div>

<a href="index.php?action=index" class="btn btn-outline" style="margin-bottom:1rem;">Back to list</a>

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
<?php else: ?>
<p class="text-muted">No assignments match your filters.</p>
<?php endif; ?>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
