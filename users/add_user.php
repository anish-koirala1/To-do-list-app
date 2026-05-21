<?php
require_once '../includes/auth_check.php';
requireAdmin();
require_once '../config/database.php';

$pageTitle    = 'Add User';
$errors       = [];
$old          = [];
$allowedRoles = ['Admin', 'Teacher', 'Student'];

/* ---- Handle POST ---- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['full_name'] = trim($_POST['full_name'] ?? '');
    $old['email']     = trim($_POST['email']     ?? '');
    $old['role']      = $_POST['role']            ?? '';

    $full_name = $old['full_name'];
    $email     = $old['email'];
    $password  = $_POST['password']  ?? '';
    $confirm   = $_POST['confirm']   ?? '';
    $role      = $old['role'];

    /* --- Validate full name --- */
    if ($full_name === '') {
        $errors['full_name'] = 'Full name is required.';
    } elseif (mb_strlen($full_name) > 100) {
        $errors['full_name'] = 'Full name must not exceed 100 characters.';
    }

    /* --- Validate email --- */
    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    } elseif (mb_strlen($email) > 100) {
        $errors['email'] = 'Email must not exceed 100 characters.';
    } else {
        $pdo  = getDB();
        $stmt = $pdo->prepare('SELECT user_id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'This email address is already registered.';
        }
    }

    /* --- Validate password --- */
    if ($password === '') {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors['password'] = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errors['password'] = 'Password must contain at least one lowercase letter.';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors['password'] = 'Password must contain at least one number.';
    } elseif ($password !== $confirm) {
        $errors['confirm'] = 'Passwords do not match.';
    }

    /* --- Validate role --- */
    if ($role === '') {
        $errors['role'] = 'Please select a role.';
    } elseif (!in_array($role, $allowedRoles, true)) {
        $errors['role'] = 'Invalid role selected.';
    }

    /* --- Insert if no errors --- */
    if (empty($errors)) {
        $pdo  = $pdo ?? getDB();
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare(
            'INSERT INTO users (full_name, email, password, role, is_active, created_date)
             VALUES (?, ?, ?, ?, 1, CURDATE())'
        );
        $stmt->execute([$full_name, $email, $hash, $role]);

        $_SESSION['flash'] = [
            'type'    => 'success',
            'message' => 'User "' . $full_name . '" was added successfully.',
        ];
        header('Location: list_users.php');
        exit;
    }
}

require_once '../includes/header.php';
?>

<nav class="breadcrumb">
    <a href="list_users.php">All Users</a>
    <span class="breadcrumb-sep">/</span>
    <span>Add User</span>
</nav>

<div class="page-header">
    <div>
        <h1 class="page-title">Add New User</h1>
        <p class="page-subtitle">Create a new account in the system</p>
    </div>
    <a href="list_users.php" class="btn btn-outline btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back
    </a>
</div>

<div class="card form-card">
    <div class="card-header">
        <h2 class="card-title">User Details</h2>
    </div>
    <div class="card-body">
        <?php if ($errors): ?>
            <div class="alert alert-error">Please fix the errors below before submitting.</div>
        <?php endif; ?>

        <form method="POST" action="add_user.php" novalidate>

            <div class="form-group">
                <label for="full_name" class="form-label">Full Name <span style="color:var(--color-danger)">*</span></label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
                    placeholder="e.g. Jane Doe"
                    maxlength="100"
                    autofocus
                >
                <?php if (isset($errors['full_name'])): ?>
                    <p class="field-error"><?= htmlspecialchars($errors['full_name']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address <span style="color:var(--color-danger)">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    placeholder="e.g. jane@example.com"
                    maxlength="100"
                >
                <?php if (isset($errors['email'])): ?>
                    <p class="field-error"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">Password <span style="color:var(--color-danger)">*</span></label>
                    <div class="input-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                            placeholder="Min. 8 characters"
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword('password')" aria-label="Toggle password">
                            <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                    <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
                    <span class="strength-label" id="strength-label"></span>
                    <?php if (isset($errors['password'])): ?>
                        <p class="field-error"><?= htmlspecialchars($errors['password']) ?></p>
                    <?php else: ?>
                        <p class="form-hint">Min 8 chars, one uppercase, one lowercase, one number.</p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="confirm" class="form-label">Confirm Password <span style="color:var(--color-danger)">*</span></label>
                    <div class="input-wrapper">
                        <input
                            type="password"
                            id="confirm"
                            name="confirm"
                            class="form-control <?= isset($errors['confirm']) ? 'is-invalid' : '' ?>"
                            placeholder="Repeat password"
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm')" aria-label="Toggle password">
                            <svg id="eye-icon-confirm" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                    <?php if (isset($errors['confirm'])): ?>
                        <p class="field-error"><?= htmlspecialchars($errors['confirm']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Role <span style="color:var(--color-danger)">*</span></label>
                <select id="role" name="role" class="form-control <?= isset($errors['role']) ? 'is-invalid' : '' ?>">
                    <option value="">-- Select a role --</option>
                    <?php foreach ($allowedRoles as $r): ?>
                        <option value="<?= $r ?>" <?= (($old['role'] ?? '') === $r) ? 'selected' : '' ?>>
                            <?= $r ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['role'])): ?>
                    <p class="field-error"><?= htmlspecialchars($errors['role']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Create User
                </button>
                <a href="list_users.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
