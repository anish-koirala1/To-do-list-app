<!DOCTYPE html>
<html>
<head>
    <title>Delete Assignment</title>

    <link rel="stylesheet" href="/TODOAPP/assets/css/styles.css">
</head>
<body>

<div class="container">

    <div class="delete-box">

        <h1>Delete Assignment</h1>

        <p class="delete-text">
            Are you sure you want to delete this assignment?
        </p>

        <div class="delete-details">

            <p>
                <strong>Task:</strong>
                <?= $assignment['task_name']; ?>
            </p>

            <p>
                <strong>Assigned To:</strong>
                <?= $assignment['assigned_to']; ?>
            </p>

        </div>

        <form method="POST">

            <!-- IMPORTANT -->
            <input type="hidden"
                   name="id"
                   value="<?= $assignment['id']; ?>">

            <button type="submit" class="action-btn delete">
                Yes, Delete
            </button>

            <a href="index.php" class="btn cancel-btn">
                Cancel
            </a>

        </form>

    </div>

</div>

</body>
</html>