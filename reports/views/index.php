<?php require __DIR__ . '/../../includes/header.php'; ?>

<div class="page-header">
    <div>
        <h1 class="page-title"><?= isStudent() ? 'My Reports' : 'Reports' ?></h1>
        <p class="page-subtitle">
            <?php if (isStudent()): ?>
            View your academic progress reports (read-only).
            <?php else: ?>
            Reporting &amp; progress tracking — create and update reports.
            <?php endif; ?>
        </p>
    </div>
    <?php if (canManageAcademics()): ?>
    <div class="page-header-actions">
        <a href="index.php?action=create" class="btn btn-primary">+ Create Report</a>
        <a href="index.php?action=track" class="btn btn-outline">Track Progress</a>
    </div>
    <?php endif; ?>
</div>

<?php if (!empty($flash)): ?>
<div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<?php if (isStudent()): ?>
<div class="alert alert-info">You can view reports only. Contact your teacher to request changes.</div>
<?php endif; ?>

<form method="GET" class="filter-bar card" style="margin-bottom:20px;border-radius:var(--radius);">
    <div class="filter-group">
        <label>Search</label>
        <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search) ?>" placeholder="Title or subject">
    </div>
    <div class="filter-group">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="">All</option>
            <?php foreach (['Pending','In Progress','Completed'] as $s): ?>
            <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="filter-group">
        <label>Priority</label>
        <select name="priority" class="form-control">
            <option value="">All</option>
            <?php foreach (['Low','Medium','High'] as $p): ?>
            <option value="<?= $p ?>" <?= $priority === $p ? 'selected' : '' ?>><?= $p ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="filter-actions">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="index.php" class="btn btn-outline">Reset</a>
    </div>
</form>

<div class="table-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th><th>Title</th><th>Subject</th><th>Priority</th>
                <th>Assign</th><th>Due</th><th>Status</th><th>Owner</th>
                <?php if (canManageAcademics()): ?><th>Actions</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($reports)): ?>
            <tr><td colspan="<?= canManageAcademics() ? 9 : 8 ?>" class="text-muted">No reports found.</td></tr>
        <?php else: foreach ($reports as $r): ?>
            <tr>
                <td><?= (int)$r['id'] ?></td>
                <td><?= htmlspecialchars($r['title']) ?></td>
                <td><?= htmlspecialchars($r['subject']) ?></td>
                <td><?= htmlspecialchars($r['priority']) ?></td>
                <td><?= htmlspecialchars($r['assign_date']) ?></td>
                <td><?= htmlspecialchars($r['due_date']) ?></td>
                <td><?= htmlspecialchars($r['status']) ?></td>
                <td><?= htmlspecialchars($r['owner_name']) ?></td>
                <?php if (canManageAcademics()): ?>
                <td>
                    <a href="index.php?action=edit&id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                    <a href="index.php?action=delete&id=<?= (int)$r['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this report?')">Delete</a>
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
