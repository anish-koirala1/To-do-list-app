-- =============================================================================
-- Seed / refresh demo users (set_users.sql)
-- Run after setup.sql. Passwords are bcrypt hashes (plain: admin / teacher / student).
-- ON DUPLICATE KEY UPDATE refreshes existing rows by username/email unique keys.
-- =============================================================================
USE academic_management_system;

-- Demo accounts for each role (Anish Koirala — User module)
INSERT INTO users (full_name, username, email, password, role, is_active) VALUES
(
    'System Admin',
    'admin',
    'admin@example.com',
    '$2y$12$ltHZHcRx9Qsh3JxJjt1KY.aeoAqAqb/cbkVWHl4W9.JktqW.XfSl6',
    'Admin',
    TRUE
),
(
    'Demo Teacher',
    'teacher',
    'teacher@example.com',
    '$2y$12$Hzb7Hd1hdzKGLg3E/Dolv.is7A8I.u/WsPt.QyLWwsiae69qXKhcq',
    'Teacher',
    TRUE
),
(
    'Demo Student',
    'student',
    'student@example.com',
    '$2y$12$iAsJ1KU3HIUNMdJuCE6weeofypNlwo6unv0d1OjQmlHiRyJPSl8Vm',
    'Student',
    TRUE
)
-- If username or email already exists, update profile fields and re-enable account
ON DUPLICATE KEY UPDATE
    full_name = VALUES(full_name),
    username  = VALUES(username),
    password  = VALUES(password),
    role      = VALUES(role),
    is_active = TRUE;
