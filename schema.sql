git config --global user.name "Your Name"
git config --global user.email "your.email@github.com"-- Database schema for ToDoApp
-- Run this file to create the application database and core tables.

CREATE DATABASE IF NOT EXISTS `to_do_applist`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `to_do_applist`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `assignments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_name` VARCHAR(255) NOT NULL,
    `assigned_to` VARCHAR(255) NOT NULL,
    `due_date` DATE NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Example seed user (plain-text password matching the current app login logic)
-- INSERT INTO `users` (`username`, `password`) VALUES ('admin', 'admin123');
