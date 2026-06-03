<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List - Todo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUa6mYf8zD+1MDE4p2aG3F5cpz7a3eOq4IWc1B4V6tV7D0Ld2R5xx4c3Q9Y3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #06b6d4;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        .page-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .page-header .subtitle {
            color: #6b7280;
            font-size: 1rem;
        }
        
        .btn-add-task {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-add-task:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }
        
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }
        
        .filter-card .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .filter-card .form-control,
        .filter-card .form-select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.6rem 0.75rem;
        }
        
        .filter-card .btn-success {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }
        
        .tasks-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            font-weight: 600;
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
            padding: 1rem;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .table tbody tr:hover {
            background-color: #f9fafb;
            transition: background-color 0.2s ease;
        }
        
        .badge-priority {
            border-radius: 6px;
            padding: 0.4rem 0.8rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-low {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-medium {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-high {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-status {
            border-radius: 6px;
            padding: 0.4rem 0.8rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-todo {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-inprogress {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-done {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .pagination {
            margin-top: 2rem;
        }
        
        .pagination .page-link {
            color: var(--primary-color);
            border-radius: 6px;
            border: 1px solid #d1d5db;
            margin: 0 0.25rem;
        }
        
        .pagination .page-link:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .no-tasks {
            text-align: center;
            padding: 3rem;
            color: #9ca3af;
        }
        
        .no-tasks i {
            font-size: 3rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid">
            <span class="navbar-brand">
                <i class="bi bi-check2-square"></i> TaskFlow
            </span>
            <div class="d-flex gap-2">
                <a href="../users/list_users.php" class="btn btn-outline-light btn-sm">Users</a>
                <a href="../assignments/index.php" class="btn btn-outline-light btn-sm">Assignments</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="bi bi-list-check"></i> Task List</h1>
                    <p class="subtitle">Organize and manage your tasks efficiently</p>
                </div>
                <a href="index.php?action=create" class="btn btn-add-task text-white">
                    <i class="bi bi-plus-circle"></i> Add New Task
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-card">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label"><i class="bi bi-search"></i> Search</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search task..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-2">
                    <label for="priority" class="form-label"><i class="bi bi-flag"></i> Priority</label>
                    <select id="priority" name="priority" class="form-select">
                        <option value="">All Priority</option>
                        <option value="Low" <?= $priority === 'Low' ? 'selected' : '' ?>>Low</option>
                        <option value="Medium" <?= $priority === 'Medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="High" <?= $priority === 'High' ? 'selected' : '' ?>>High</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label"><i class="bi bi-circle-half"></i> Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="To Do" <?= $status === 'To Do' ? 'selected' : '' ?>>To Do</option>
                        <option value="In Progress" <?= $status === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="Done" <?= $status === 'Done' ? 'selected' : '' ?>>Done</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date" class="form-label"><i class="bi bi-calendar"></i> Due Date</label>
                    <input type="date" id="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-funnel"></i> Filter Tasks
                    </button>
                </div>
            </form>
        </div>

        <!-- Tasks Table -->
        <div class="tasks-container">
            <?php if (!empty($tasks)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash"></i> ID</th>
                                <th><i class="bi bi-card-text"></i> Title</th>
                                <th><i class="bi bi-flag"></i> Priority</th>
                                <th><i class="bi bi-circle-half"></i> Status</th>
                                <th><i class="bi bi-calendar-event"></i> Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td class="text-muted">#<?= htmlspecialchars($task['id']) ?></td>
                                    <td><?= htmlspecialchars($task['title']) ?></td>
                                    <td>
                                        <span class="badge-priority badge-<?= strtolower($task['priority']) ?>">
                                            <?= htmlspecialchars($task['priority']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-status badge-<?= str_replace(' ', '', strtolower($task['status'])) ?>">
                                            <?= htmlspecialchars($task['status']) ?>
                                        </span>
                                    </td>
                                    <td><small><?= htmlspecialchars($task['due_date']) ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-tasks">
                    <i class="bi bi-inbox"></i>
                    <p>No tasks found. <a href="index.php?action=create" class="text-decoration-none">Create one</a> to get started!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (!empty($tasks)): ?>
            <nav aria-label="Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="index.php?page=<?= max(1, $page - 1) ?>">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                    </li>
                    <li class="page-item">
                        <span class="page-link">Page <?= $page ?></span>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=<?= $page + 1 ?>">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

