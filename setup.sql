-- User Management System Database Setup
-- Run this file once to initialize the database

CREATE DATABASE IF NOT EXISTS user_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE user_management;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id      INT AUTO_INCREMENT PRIMARY KEY,
    full_name    VARCHAR(100) NOT NULL,
    email        VARCHAR(100) NOT NULL UNIQUE,
    password     VARCHAR(255) NOT NULL,
    role         ENUM('Admin', 'Teacher', 'Student') NOT NULL,
    is_active    BOOLEAN NOT NULL DEFAULT TRUE,
    created_date DATE NOT NULL DEFAULT (CURDATE())
);

-- Default admin account
-- Password: Admin@1234
INSERT INTO users (full_name, email, password, role, is_active, created_date) VALUES
(
    'System Admin',
    'admin@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Admin',
    TRUE,
    CURDATE()
);

-- Tasks module (from task_mangement branch)
CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    status ENUM('To Do', 'In Progress', 'Done') DEFAULT 'To Do',
    due_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_due_date (due_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tasks (title, priority, status, due_date) VALUES
('Complete project documentation', 'High', 'In Progress', '2026-06-10'),
('Review team pull requests', 'Medium', 'To Do', '2026-06-05'),
('Fix database connection issue', 'High', 'Done', '2026-06-01');

-- Assignments module (from assignment-module branch)
CREATE TABLE IF NOT EXISTS assignments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    task_name VARCHAR(255) NOT NULL,
    assigned_to VARCHAR(255) NOT NULL,
    due_date DATE NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;