<?php
require_once '../includes/auth_check.php';
requireAdmin();
require_once '../config/database.php';

$pageTitle    = 'Edit User';
$pdo          = getDB();
$errors       = [];
$allowedRoles = ['Admin', 'Teacher', 'Student'];

/* ---- Fetch existing user ---- */
$userId = (int)($_GET['id'] ?? $_POST['user_id'] ?? 0);

if ($userId <= 0) {
    header('Location: list_users.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE user_id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'User not found.'];
    header('Location: list_users.php');
    exit;
}

/* ---- Handle POST ---- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email']     ?? '');
    $role      = $_POST['role']           ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;

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
        $dup = $pdo->prepare('SELECT user_id FROM users WHERE email = ? AND user_id != ?');
        $dup->execute([$email, $userId]);
        if ($dup->fetch()) {
            $errors['email'] = 'This email is already used by another account.';
        }
    }

    /* --- Validate role --- */
    if ($role === '') {
        $errors['role'] = 'Please select a role.';
    } elseif (!in_array($role, $allowedRoles, true)) {
        $errors['role'] = 'Invalid role selected.';
    }

    /* --- Optional password change --- */
    $new_password      = $_POST['new_password']      ?? '';
    $confirm_password  = $_POST['confirm_password']  ?? '';
    $changePassword    = ($new_password !== '');

    if ($changePassword) {
        if (strlen($new_password) < 8) {
            $errors['new_password'] = 'Password must be at least 8 characters.';
        } elseif (!preg_match('/[A-Z]/', $new_password)) {
            $errors['new_password'] = 'Password must contain at least one uppercase letter.';
        } elseif (!preg_match('/[a-z]/', $new_password)) {
            $errors['new_password'] = 'Password must contain at least one lowercase letter.';
        } elseif (!preg_match('/[0-9]/', $new_password)) {
            $errors['new_password'] = 'Password must contain at least one number.';
        } elseif ($new_password !== $confirm_password) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }
    }

    /* --- Merge back for re-display if errors --- */
    if ($errors) {
        $user = array_merge($user, [
            'full_name' => $full_name,
            'email'     => $email,
            'role'      => $role,
            'is_active' => $is_active,
        ]);
    } else {
        if ($changePassword) {
            $upd = $pdo->prepare(
                'UPDATE users SET full_name = ?, email = ?, role = ?, is_active = ?, password = ? WHERE user_id = ?'
            );
            $upd->execute([$full_name, $email, $role, $is_active, password_hash($new_password, PASSWORD_BCRYPT), $userId]);
        } else {
            $upd = $pdo->prepare(
                'UPDATE users SET full_name = ?, email = ?, role = ?, is_active = ? WHERE user_id = ?'
            );
            $upd->execute([$full_name, $email, $role, $is_active, $userId]);
        }

        $_SESSION['flash'] = [
            'type'    => 'success',
            'message' => 'User "' . $full_name . '" was updated successfully.',
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
    <span>Edit User</span>
</nav>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit User</h1>
        <p class="page-subtitle">Update details for <strong><?= htmlspecialchars($user['full_name']) ?></strong></p>
    </div>
    <a href="list_users.php" class="btn btn-outline btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back
    </a>
</div>

<div class="card form-card">
    <div class="card-header">
        <h2 class="card-title">User Details</h2>
        <span style="font-size:.8rem; color:var(--color-text-muted);">ID: #<?= $user['user_id'] ?></span>
    </div>
    <div class="card-body">

        <?php if ($errors): ?>
            <div class="alert alert-error">Please fix the errors below before submitting.</div>
        <?php endif; ?>

        <form method="POST" action="edit_user.php" novalidate>
            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

            <div class="form-group">
                <label for="full_name" class="form-label">Full Name <span style="color:var(--color-danger)">*</span></label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($user['full_name']) ?>"
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
                    value="<?= htmlspecialchars($user['email']) ?>"
                    maxlength="100"
                >
                <?php if (isset($errors['email'])): ?>
                    <p class="field-error"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="role" class="form-label">Role <span style="color:var(--color-danger)">*</span></label>
                    <select id="role" name="role" class="form-control <?= isset($errors['role']) ? 'is-invalid' : '' ?>">
                        <option value="">-- Select a role --</option>
                        <?php foreach ($allowedRoles as $r): ?>
                            <option value="<?= $r ?>" <?= $user['role'] === $r ? 'selected' : '' ?>>
                                <?= $r ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['role'])): ?>
                        <p class="field-error"><?= htmlspecialchars($errors['role']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Account Status</label>
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer; margin-top:8px;">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            style="width:18px; height:18px; cursor:pointer; accent-color:var(--color-primary);"
                            <?= $user['is_active'] ? 'checked' : '' ?>
                        >
                        <span style="font-size:.9rem; font-weight:500;">Account is active</span>
                    </label>
                    <p class="form-hint">Uncheck to disable this user's access.</p>
                </div>
            </div>

            <!-- Password reset section -->
            <details style="margin-bottom:18px;">
                <summary style="cursor:pointer; font-size:.875rem; font-weight:600; color:var(--color-primary); user-select:none;">
                    Change Password (optional)
                </summary>
                <div style="margin-top:14px; padding:16px; background:var(--color-bg); border-radius:var(--radius-sm); border:1px solid var(--color-border);">
                    <p class="form-hint" style="margin-bottom:12px;">Leave blank to keep the current password.</p>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="new_password" class="form-label">New Password</label>
                            <div class="input-wrapper">
                                <input
                                    type="password"
                                    id="new_password"
                                    name="new_password"
                                    class="form-control <?= isset($errors['new_password']) ? 'is-invalid' : '' ?>"
                                    placeholder="Min. 8 characters"
                                >
                                <button type="button" class="toggle-password" onclick="togglePassword('new_password')" aria-label="Toggle">
                                    <svg id="eye-icon-new_password" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </div>
                            <?php if (isset($errors['new_password'])): ?>
                                <p class="field-error"><?= htmlspecialchars($errors['new_password']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <div class="input-wrapper">
                                <input
                                    type="password"
                                    id="confirm_password"
                                    name="confirm_password"
                                    class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                                    placeholder="Repeat password"
                                >
                                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')" aria-label="Toggle">
                                    <svg id="eye-icon-confirm_password" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                            </div>
                            <?php if (isset($errors['confirm_password'])): ?>
                                <p class="field-error"><?= htmlspecialchars($errors['confirm_password']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </details>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Save Changes
                </button>
                <a href="list_users.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
