-- Create the todo app database
CREATE DATABASE IF NOT EXISTS `to_do_applist`;
USE `to_do_applist`;

-- Create the tasks table
CREATE TABLE IF NOT EXISTS `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `priority` ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    `status` ENUM('To Do', 'In Progress', 'Done') DEFAULT 'To Do',
    `due_date` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_status` (`status`),
    INDEX `idx_priority` (`priority`),
    INDEX `idx_due_date` (`due_date`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample tasks for testing
INSERT INTO `tasks` (`title`, `priority`, `status`, `due_date`) VALUES
('Complete project documentation', 'High', 'In Progress', '2026-06-10'),
('Review team pull requests', 'Medium', 'To Do', '2026-06-05'),
('Fix database connection issue', 'High', 'Done', '2026-06-01'),
('Update user interface design', 'Medium', 'To Do', '2026-06-15'),
('Write unit tests', 'High', 'In Progress', '2026-06-12');
