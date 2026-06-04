<?php
/**
 * Helper Functions - helpers.php
 * 
 * Collection of utility functions for:
 * - Role-based access control (RBAC)
 * - Permission checking
 * - URL generation
 * - Session management
 * - Flash message handling
 * 
 * The system has three roles: Admin, Teacher, and Student
 */

// ============================================================
// Role Checking Functions
// ============================================================

/**
 * isAdmin() - Check if current user is an Administrator
 * 
 * @return bool True if user is an Admin, false otherwise
 */
function isAdmin(): bool
{
    return ($_SESSION['role'] ?? '') === 'Admin';
}

/**
 * isTeacherRole() - Check if current user is a Teacher
 * 
 * @return bool True if user is a Teacher, false otherwise
 */
function isTeacherRole(): bool
{
    return ($_SESSION['role'] ?? '') === 'Teacher';
}

/**
 * isStaff() - Check if current user is Staff (Teacher or Admin)
 * 
 * Teachers and Admins can manage academic content (classes, exams, assignments).
 * Students have read-only access to academic content.
 * 
 * @return bool True if user is a Teacher or Admin, false otherwise
 */
function isStaff(): bool
{
    $role = $_SESSION['role'] ?? '';
    return $role === 'Teacher' || $role === 'Admin';
}

/**
 * isTeacher() - Legacy alias for isStaff()
 * 
 * @deprecated Use isStaff() instead for clarity
 * @return bool True if user is Staff (Teacher or Admin)
 */
function isTeacher(): bool
{
    return isStaff();
}

/**
 * isStudent() - Check if current user is a Student
 * 
 * @return bool True if user is a Student, false otherwise
 */
function isStudent(): bool
{
    return ($_SESSION['role'] ?? '') === 'Student';
}

// ============================================================
// Permission Checking Functions
// ============================================================

/**
 * canManageUsers() - Check if user can manage user accounts
 * 
 * Only Admins can add, edit, delete, or disable user accounts.
 * 
 * @return bool True if user can manage users
 */
function canManageUsers(): bool
{
    return isAdmin();
}

/**
 * canManageAcademics() - Check if user can manage academic content
 * 
 * Teachers and Admins can create and manage classes, exams, assignments, and reports.
 * 
 * @return bool True if user can manage academic content
 */
function canManageAcademics(): bool
{
    return isStaff();
}

// ============================================================
// Session Functions
// ============================================================

/**
 * currentUserId() - Get the logged-in user's ID
 * 
 * @return int The user ID from the session, or 0 if not logged in
 */
function currentUserId(): int
{
    return (int)($_SESSION['user_id'] ?? 0);
}

// ============================================================
// URL Generation Functions
// ============================================================

/**
 * appUrl() - Generate a relative URL from the current page to the target path
 * 
 * Automatically calculates the correct number of ../ based on current page depth.
 * Useful for consistent URL generation from any page in the app.
 * 
 * @param string $path The target path relative to root (e.g., '/dashboard/index.php')
 * @return string The correctly relative URL to navigate to that path
 */
function appUrl(string $path): string
{
    // Count the slashes in PHP_SELF to determine page depth
    $depth = max(0, substr_count($_SERVER['PHP_SELF'], '/') - 2);
    // Add the appropriate number of ../ to go back to root
    return str_repeat('../', $depth) . ltrim($path, '/');
}

/**
 * dashboardUrl() - Get the URL to the user's role-specific dashboard
 * 
 * @return string Relative URL to the dashboard
 */
function dashboardUrl(): string
{
    return appUrl('dashboard/index.php');
}

// ============================================================
// Access Control Functions (Require & Redirect)
// ============================================================

/**
 * requireAdmin() - Require the current user to be an Administrator
 * 
 * If the user is not an Admin, displays an error and redirects to dashboard.
 * Use at the top of admin-only pages.
 * 
 * @return void Exits if access is denied
 */
function requireAdmin(): void
{
    if (!isAdmin()) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Administrator access only.'];
        header('Location: ' . dashboardUrl());
        exit;
    }
}

/**
 * requireStaff() - Require the current user to be Staff (Teacher or Admin)
 * 
 * If the user is not Staff, displays an error and redirects to dashboard.
 * Use at the top of pages for managing academic content.
 * 
 * @return void Exits if access is denied
 */
function requireStaff(): void
{
    if (!isStaff()) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Teachers and administrators can change academic records.'];
        header('Location: ' . dashboardUrl());
        exit;
    }
}

/**
 * requireTeacher() - Legacy alias for requireStaff()
 * 
 * @deprecated Use requireStaff() instead
 * @return void Exits if access is denied
 */
function requireTeacher(): void
{
    requireStaff();
}

// ============================================================
// Flash Message Functions
// ============================================================

/**
 * redirectWithFlash() - Redirect to a URL with a flash message
 * 
 * Flash messages are one-time messages displayed to the user (usually after form submission).
 * They are stored in the session and typically cleared after being displayed.
 * 
 * @param string $url The URL to redirect to
 * @param string $type The message type: 'success', 'error', 'warning', 'info'
 * @param string $message The message text to display
 * @return void Exits after redirecting
 */
function redirectWithFlash(string $url, string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    header('Location: ' . $url);
    exit;
}
