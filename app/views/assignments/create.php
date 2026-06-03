<!DOCTYPE html>
<html>
<head>
    <title>Create Assignment</title>

    <link rel="stylesheet" href="/TODOAPP/assets/css/styles.css">
</head>
<body>

<div class="container">

    <h1>Create Assignment</h1>

    <form method="POST">

        <div class="form-group">
            <label>Task Name</label>
            <input type="text" name="task_name" required>
        </div>

        <div class="form-group">
            <label>Assigned To</label>
            <input type="text" name="assigned_to" required>
        </div>

        <div class="form-group">
            <label>Due Date</label>
            <input type="date" name="due_date" required>
        </div>

        <div class="form-group">
            <label>Status</label>

            <select name="status">

                <option value="Pending">Pending</option>

                <option value="In Progress">In Progress</option>

                <option value="Completed">Completed</option>

            </select>
        </div>

        <button type="submit" class="submit-btn">
            Save Assignment
        </button>

    </form>

</div>

</body>
</html>