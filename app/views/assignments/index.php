<!DOCTYPE html>
<html>
<head>
    <title>Assignment List</title>

    <link rel="stylesheet" href="/TODOAPP/assets/css/styles.css">
</head>
<body>

<div class="container">

    <h1>Assignment List</h1>

    <!-- BUTTON SECTION -->
    <div class="top-buttons">

        <!-- CREATE BUTTON -->
        <a href="index.php?action=create" class="btn">
            + Assign Task
        </a>

        <!-- SEARCH BUTTON -->
        <a href="index.php?action=search" class="btn">
            Search Assignment
        </a>

        <!-- LOGOUT BUTTON -->
        <a href="index.php?action=logout" class="btn delete">
            Logout
        </a>

        <!-- FILTER FORM -->
        <form action="index.php" method="GET" class="filter-form">

            <!-- HIDDEN ACTION -->
            <input type="hidden" name="action" value="filter">

            <!-- STATUS FILTER -->
            <select name="status" class="filter-select">

                <option value="">All Status</option>

                <option value="In Progress">In Progress</option>

                <option value="Pending">Pending</option>

                <option value="Completed">Completed</option>

            </select>

            <!-- FROM DATE -->
            <input type="date"
                   name="from_date"
                   class="date-input">

            <!-- TO DATE -->
            <input type="date"
                   name="to_date"
                   class="date-input">

            <!-- FILTER BUTTON -->
            <button type="submit" class="btn">
                Filter
            </button>

        </form>

    </div>

    <!-- TABLE -->
    <table>

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

        <?php foreach($assignments as $assignment): ?>

            <tr>

                <td><?= $assignment['id'] ?></td>

                <td><?= $assignment['task_name'] ?></td>

                <td><?= $assignment['assigned_to'] ?></td>

                <td><?= $assignment['due_date'] ?></td>

                <td><?= $assignment['status'] ?></td>

                <td>

                    <!-- EDIT BUTTON -->
                    <a class="action-btn edit"
                       href="index.php?action=edit&id=<?= $assignment['id'] ?>">
                       ✏ Edit
                    </a>

                    <!-- DELETE BUTTON -->
                    <a class="action-btn delete"
                       href="index.php?action=delete&id=<?= $assignment['id'] ?>"
                       onclick="return confirm('Are you sure you want to delete this task?')">

                       🗑 Delete

                    </a>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

</body>
</html>