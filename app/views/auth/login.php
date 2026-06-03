<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link rel="stylesheet" href="/ToDoApp/assets/css/styles.css">
</head>
<body>

<div class="login-container">

    <form method="POST" class="login-form">

        <h2>Login</h2>

        <?php if(isset($error)) : ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <input
            type="text"
            name="username"
            placeholder="Username"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit" class="btn">
            Login
        </button>

    </form>

</div>

</body>
</html>