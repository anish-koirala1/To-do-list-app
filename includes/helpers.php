<?php

function isAdmin(): bool
{
    return ($_SESSION['role'] ?? '') === 'Admin';
}

function isTeacherRole(): bool
{
    return ($_SESSION['role'] ?? '') === 'Teacher';
}

/** Teacher or Admin — academic management (not user accounts). */
function isStaff(): bool
{
    $role = $_SESSION['role'] ?? '';
    return $role === 'Teacher' || $role === 'Admin';
}

/** @deprecated Use isStaff() for academic write access. */
function isTeacher(): bool
{
    return isStaff();
}

function isStudent(): bool
{
    return ($_SESSION['role'] ?? '') === 'Student';
}

function canManageUsers(): bool
{
    return isAdmin();
}

function canManageAcademics(): bool
{
    return isStaff();
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

function dashboardUrl(): string
{
    return appUrl('dashboard/index.php');
}

function requireAdmin(): void
{
    if (!isAdmin()) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Administrator access only.'];
        header('Location: ' . dashboardUrl());
        exit;
    }
}

function requireStaff(): void
{
    if (!isStaff()) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Teachers and administrators can change academic records.'];
        header('Location: ' . dashboardUrl());
        exit;
    }
}

function requireTeacher(): void
{
    requireStaff();
}

function redirectWithFlash(string $url, string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    header('Location: ' . $url);
    exit;
}
