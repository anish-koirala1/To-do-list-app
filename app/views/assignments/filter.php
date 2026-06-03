<!DOCTYPE html>
<html>
<head>
    <title>Filter Assignment</title>

    <link rel="stylesheet" href="/TODOAPP/assets/css/styles.css">
</head>
<body>

<div class="container">

    <h1>Filter Assignment</h1>

    <form method="GET" action="index.php" class="filter-form">

        <input type="hidden" name="action" value="filter">

        <select name="status" required>

            <option value="">Select Status</option>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>

        </select>

        <button type="submit" class="btn">
            Filter
        </button>

        <a href="index.php?action=index" class="btn reset-btn">
            Back
        </a>

    </form>

    <?php if (!empty($assignments)): ?>

        <table>

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

            <?php foreach($assignments as $assignment): ?>

                <tr>
                    <td><?= $assignment['id'] ?></td>
                    <td><?= $assignment['task_name'] ?></td>
                    <td><?= $assignment['assigned_to'] ?></td>
                    <td><?= $assignment['due_date'] ?></td>
                    <td><?= $assignment['status'] ?></td>
                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</div>

</body>
</html>