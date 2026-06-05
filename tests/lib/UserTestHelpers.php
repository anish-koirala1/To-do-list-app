<?php
/**
 * User validation helpers — mirrors rules in users/add_user.php for isolated testing.
 */
declare(strict_types=1);

function validateUserCreateData(array $post, PDO $pdo, ?int $excludeUserId = null): array
{
    $allowedRoles = ['Admin', 'Teacher', 'Student'];
    $data = [
        'full_name' => trim($post['full_name'] ?? ''),
        'username'  => trim($post['username'] ?? ''),
        'email'     => trim($post['email'] ?? ''),
        'password'  => $post['password'] ?? '',
        'confirm'   => $post['confirm'] ?? ($post['password'] ?? ''),
        'role'      => $post['role'] ?? '',
    ];
    $errors = [];

    if ($data['full_name'] === '') {
        $errors['full_name'] = 'Full name is required.';
    } elseif (mb_strlen($data['full_name']) > 100) {
        $errors['full_name'] = 'Full name must not exceed 100 characters.';
    } elseif (preg_match('/\d/', $data['full_name'])) {
        $errors['full_name'] = 'Full name must not contain numbers.';
    }

    if ($data['username'] === '') {
        $errors['username'] = 'Username is required.';
    } elseif (!preg_match('/^[a-zA-Z0-9._-]{3,50}$/', $data['username'])) {
        $errors['username'] = 'Username must be 3–50 characters (letters, numbers, . _ -).';
    } else {
        $sql = 'SELECT id FROM users WHERE username = ?' . ($excludeUserId ? ' AND id != ?' : '');
        $stmt = $pdo->prepare($sql);
        $stmt->execute($excludeUserId ? [$data['username'], $excludeUserId] : [$data['username']]);
        if ($stmt->fetch()) {
            $errors['username'] = 'This username is already taken.';
        }
    }

    if ($data['email'] === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    } elseif (mb_strlen($data['email']) > 100) {
        $errors['email'] = 'Email must not exceed 100 characters.';
    } else {
        $sql = 'SELECT id FROM users WHERE email = ?' . ($excludeUserId ? ' AND id != ?' : '');
        $stmt = $pdo->prepare($sql);
        $stmt->execute($excludeUserId ? [$data['email'], $excludeUserId] : [$data['email']]);
        if ($stmt->fetch()) {
            $errors['email'] = 'This email address is already registered.';
        }
    }

    if ($data['password'] === '') {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($data['password']) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    } elseif (!preg_match('/[A-Z]/', $data['password'])) {
        $errors['password'] = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[a-z]/', $data['password'])) {
        $errors['password'] = 'Password must contain at least one lowercase letter.';
    } elseif (!preg_match('/[0-9]/', $data['password'])) {
        $errors['password'] = 'Password must contain at least one number.';
    } elseif ($data['password'] !== $data['confirm']) {
        $errors['confirm'] = 'Passwords do not match.';
    }

    if ($data['role'] === '') {
        $errors['role'] = 'Please select a role.';
    } elseif (!in_array($data['role'], $allowedRoles, true)) {
        $errors['role'] = 'Invalid role selected.';
    }

    return ['data' => $data, 'errors' => $errors];
}

function userListQuery(PDO $pdo, ?string $search, ?string $role, ?string $status): array
{
    $conditions = [];
    $params = [];
    if ($search !== null && $search !== '') {
        $conditions[] = '(full_name LIKE ? OR email LIKE ? OR username LIKE ?)';
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }
    if ($role !== null && $role !== '') {
        $conditions[] = 'role = ?';
        $params[] = $role;
    }
    if ($status !== null && $status !== '') {
        $conditions[] = 'is_active = ?';
        $params[] = (int)$status;
    }
    $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
    $stmt = $pdo->prepare("SELECT id, full_name, username, email, role, is_active FROM users $where ORDER BY id");
    $stmt->execute($params);
    return $stmt->fetchAll();
}
