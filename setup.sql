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

-- Sample teacher
INSERT INTO users (full_name, email, password, role, is_active, created_date) VALUES
(
    'Jane Teacher',
    'teacher@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Teacher',
    TRUE,
    CURDATE()
);

-- Sample student
INSERT INTO users (full_name, email, password, role, is_active, created_date) VALUES
(
    'John Student',
    'student@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Student',
    TRUE,
    CURDATE()
);

-- Disabled student example
INSERT INTO users (full_name, email, password, role, is_active, created_date) VALUES
(
    'Inactive User',
    'inactive@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Student',
    FALSE,
    CURDATE()
);
