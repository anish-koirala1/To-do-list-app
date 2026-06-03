<!DOCTYPE html>
<html>
<head>
    <title>Search Assignment</title>

    <link rel="stylesheet" href="/TODOAPP/assets/css/styles.css">
</head>
<body>

<div class="container">

    <h1>Search Assignment</h1>

    <div class="search-box">

        <form method="GET" action="index.php">

            <input type="hidden" name="action" value="search">

            <input
                type="number"
                name="search_id"
                placeholder="Enter Assignment ID"
                required
            >

            <button type="submit" class="btn">
                Search
            </button>

            <a href="index.php?action=index" class="btn reset-btn">
                Back
            </a>

        </form>

    </div>

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

                <?php foreach ($assignments as $assignment): ?>

                    <tr>

                        <td><?= $assignment['id']; ?></td>

                        <td><?= $assignment['task_name']; ?></td>

                        <td><?= $assignment['assigned_to']; ?></td>

                        <td><?= $assignment['due_date']; ?></td>

                        <td><?= $assignment['status']; ?></td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</div>

</body>
</html>