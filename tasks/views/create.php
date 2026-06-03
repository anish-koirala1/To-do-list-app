<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task - Todo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUa6mYf8zD+1MDE4p2aG3F5cpz7a3eOq4IWc1B4V6tV7D0Ld2R5xx4c3Q9Y3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --success-color: #10b981;
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
        
        .form-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .form-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.7rem 0.9rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            color: white;
            width: 100%;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
            color: white;
        }
        
        .btn-back {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-back:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .buttons-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            border-color: #fecaca;
            color: #991b1b;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid">
            <span class="navbar-brand"><i class="bi bi-check2-square"></i> TaskFlow</span>
            <div class="d-flex gap-2">
                <a href="../users/list_users.php" class="btn btn-outline-light btn-sm">Users</a>
                <a href="../assignments/index.php" class="btn btn-outline-light btn-sm">Assignments</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="form-container">
            <div class="form-header">
                <h1><i class="bi bi-plus-circle"></i> Create Task</h1>
                <p class="text-muted">Add a new task to your todo list</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <strong>Errors:</strong>
                    <ul class="mt-2 mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label"><i class="bi bi-card-text"></i> Task Title</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Enter task title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="priority" class="form-label"><i class="bi bi-flag"></i> Priority</label>
                        <select id="priority" name="priority" class="form-select">
                            <option value="Low" <?= (($_POST['priority'] ?? '') === 'Low') ? 'selected' : '' ?>>Low</option>
                            <option value="Medium" <?= (($_POST['priority'] ?? 'Medium') === 'Medium') ? 'selected' : '' ?>>Medium</option>
                            <option value="High" <?= (($_POST['priority'] ?? '') === 'High') ? 'selected' : '' ?>>High</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label"><i class="bi bi-circle-half"></i> Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="To Do" <?= (($_POST['status'] ?? 'To Do') === 'To Do') ? 'selected' : '' ?>>To Do</option>
                            <option value="In Progress" <?= (($_POST['status'] ?? '') === 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                            <option value="Done" <?= (($_POST['status'] ?? '') === 'Done') ? 'selected' : '' ?>>Done</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="due_date" class="form-label"><i class="bi bi-calendar-event"></i> Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="form-control" value="<?= htmlspecialchars($_POST['due_date'] ?? '') ?>" required>
                </div>
                <div class="buttons-group">
                    <a href="index.php" class="btn-back"><i class="bi bi-chevron-left"></i> Back</a>
                    <button type="submit" class="btn-submit"><i class="bi bi-check-circle"></i> Save Task</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

