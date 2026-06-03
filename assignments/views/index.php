<?php
$pageTitle = 'Assignments';
$baseUrl = '../../';
require __DIR__ . '/../../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1>Assignment List</h1>
        <p class="page-subtitle">Manage team assignments</p>
    </div>
</div>

<div class="top-buttons" style="margin-bottom:1.5rem;display:flex;flex-wrap:wrap;gap:10px;align-items:center;">
    <a href="index.php?action=create" class="btn btn-primary">+ Assign Task</a>
    <a href="index.php?action=search" class="btn btn-outline">Search</a>

    <form action="index.php" method="GET" class="filter-form" style="display:flex;flex-wrap:wrap;gap:8px;margin-left:auto;">
        <input type="hidden" name="action" value="filter">
        <select name="status" class="form-control" style="width:auto;">
            <option value="">All Status</option>
            <option value="In Progress">In Progress</option>
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
        </select>
        <input type="date" name="from_date" class="form-control" style="width:auto;">
        <input type="date" name="to_date" class="form-control" style="width:auto;">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>

<div class="table-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Task Name</th>
                <th>Assigned To</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($assignments)): ?>
            <tr><td colspan="6" class="text-center text-muted">No assignments yet.</td></tr>
        <?php else: ?>
            <?php foreach ($assignments as $assignment): ?>
            <tr>
                <td><?= (int)$assignment['id'] ?></td>
                <td><?= htmlspecialchars($assignment['task_name']) ?></td>
                <td><?= htmlspecialchars($assignment['assigned_to']) ?></td>
                <td><?= htmlspecialchars($assignment['due_date']) ?></td>
                <td><?= htmlspecialchars($assignment['status']) ?></td>
                <td>
                    <a class="btn btn-sm btn-outline" href="index.php?action=edit&id=<?= (int)$assignment['id'] ?>">Edit</a>
                    <a class="btn btn-sm btn-danger" href="index.php?action=delete&id=<?= (int)$assignment['id'] ?>"
                       onclick="return confirm('Delete this assignment?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
