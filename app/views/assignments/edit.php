<!DOCTYPE html>
<html>
<head>
    <title>Edit Assignment</title>

    <link rel="stylesheet" href="/TODOAPP/assets/css/styles.css">
</head>
<body>

<div class="container">

    <h1>Edit Assignment</h1>

    <form method="POST">

        <div class="form-group">
            <label>Task Name</label>

            <input type="text"
                   name="task_name"
                   value="<?= $assignment['task_name'] ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Assigned To</label>

            <input type="text"
                   name="assigned_to"
                   value="<?= $assignment['assigned_to'] ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Due Date</label>

            <input type="date"
                   name="due_date"
                   value="<?= $assignment['due_date'] ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Status</label>

            <select name="status">

                <option value="Pending"
                <?= $assignment['status'] == 'Pending' ? 'selected' : '' ?>>
                    Pending
                </option>

                <option value="In Progress"
                <?= $assignment['status'] == 'In Progress' ? 'selected' : '' ?>>
                    In Progress
                </option>

                <option value="Completed"
                <?= $assignment['status'] == 'Completed' ? 'selected' : '' ?>>
                    Completed
                </option>

            </select>
        </div>

        <button type="submit" class="submit-btn">
            Update Assignment
        </button>

    </form>

</div>

</body>
</html>