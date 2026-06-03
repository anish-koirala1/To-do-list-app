<?php

function isTeacher(): bool
{
    $role = $_SESSION['role'] ?? '';
    return $role === 'Teacher' || $role === 'Admin';
}

function isStudent(): bool
{
    return ($_SESSION['role'] ?? '') === 'Student';
}

function requireTeacher(): void
{
    if (!isTeacher()) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Teachers or admins only.'];
        header('Location: ' . appUrl('users/list_users.php'));
        exit;
    }
}

function currentUserId(): int
{
    return (int)($_SESSION['user_id'] ?? 0);
}

function appUrl(string $path): string
{
    $depth = max(0, substr_count($_SERVER['PHP_SELF'], '/') - 2);
    return str_repeat('../', $depth) . ltrim($path, '/');
}

function redirectWithFlash(string $url, string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    header('Location: ' . $url);
    exit;
}
