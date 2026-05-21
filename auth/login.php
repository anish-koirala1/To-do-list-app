<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../users/list_users.php');
    exit;
}

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login &mdash; User Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">

<div class="auth-card">
    <div class="auth-logo">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
    </div>
    <h1 class="auth-title">User Management</h1>
    <p class="auth-subtitle">Sign in to your account</p>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="authenticate.php" method="POST" novalidate>
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                placeholder="admin@example.com"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    required
                >
                <button type="button" class="toggle-password" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                    <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </form>
</div>

<script src="../assets/js/main.js"></script>
</body>
</html>
