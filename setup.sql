-- TO-DO LIST MANAGEMENT SYSTEM / Integrated Academic Management System
-- Team class diagram schema

CREATE DATABASE IF NOT EXISTS academic_management_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE academic_management_system;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS exam_attempt_answers;
DROP TABLE IF EXISTS exam_attempts;
DROP TABLE IF EXISTS exam_questions;
DROP TABLE IF EXISTS exams;
DROP TABLE IF EXISTS scheduled_classes;
DROP TABLE IF EXISTS reports;
DROP TABLE IF EXISTS assignment_submissions;
DROP TABLE IF EXISTS assignments;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- User (Anish Koirala)
CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    full_name   VARCHAR(100) NOT NULL,
    username    VARCHAR(100) NOT NULL UNIQUE,
    email       VARCHAR(255) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('Admin', 'Teacher', 'Student') NOT NULL,
    is_active   BOOLEAN NOT NULL DEFAULT TRUE,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO users (full_name, username, email, password, role, is_active) VALUES
('System Admin', 'admin', 'admin@example.com', '$2y$12$ltHZHcRx9Qsh3JxJjt1KY.aeoAqAqb/cbkVWHl4W9.JktqW.XfSl6', 'Admin', TRUE),
('Demo Teacher', 'teacher', 'teacher@example.com', '$2y$12$Hzb7Hd1hdzKGLg3E/Dolv.is7A8I.u/WsPt.QyLWwsiae69qXKhcq', 'Teacher', TRUE),
('Demo Student', 'student', 'student@example.com', '$2y$12$iAsJ1KU3HIUNMdJuCE6weeofypNlwo6unv0d1OjQmlHiRyJPSl8Vm', 'Student', TRUE);

-- Report (Utsav Luitel) — User 1-* Report
CREATE TABLE reports (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    title       VARCHAR(255) NOT NULL,
    subject     VARCHAR(255) NOT NULL,
    priority    ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Medium',
    assign_date DATE NOT NULL,
    due_date    DATE NOT NULL,
    status      ENUM('Pending', 'In Progress', 'Completed') NOT NULL DEFAULT 'Pending',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ScheduledClass (Puskar) — Report 1-* ScheduledClass
CREATE TABLE scheduled_classes (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    report_id   INT NULL,
    user_id     INT NOT NULL,
    title       VARCHAR(255) NOT NULL,
    instructor  VARCHAR(255) NOT NULL,
    classroom   VARCHAR(255) NOT NULL,
    start_time  DATETIME NOT NULL,
    end_time    DATETIME NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Exam, Question, Attempt (Puskar Bastola) — ScheduledClass 1-* Exam
CREATE TABLE exams (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    scheduled_class_id INT NULL,
    user_id          INT NOT NULL,
    title            VARCHAR(255) NOT NULL,
    subject          VARCHAR(255) NOT NULL,
    duration_minutes INT NOT NULL DEFAULT 60,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (scheduled_class_id) REFERENCES scheduled_classes(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE exam_questions (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    exam_id         INT NOT NULL,
    user_id         INT NOT NULL,
    question_text   TEXT NOT NULL,
    option_a        VARCHAR(255) NOT NULL,
    option_b        VARCHAR(255) NOT NULL,
    option_c        VARCHAR(255) NOT NULL,
    option_d        VARCHAR(255) NOT NULL,
    correct_option  CHAR(1) NOT NULL,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE exam_attempts (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NOT NULL,
    exam_id          INT NOT NULL,
    score            INT NOT NULL DEFAULT 0,
    total_questions  INT NOT NULL DEFAULT 0,
    started_at       DATETIME NOT NULL,
    completed_at     DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE exam_attempt_answers (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id       INT NOT NULL,
    question_id      INT NOT NULL,
    selected_option  CHAR(1) NOT NULL,
    FOREIGN KEY (attempt_id) REFERENCES exam_attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES exam_questions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Assignment (Sunil Kumar BK) — teacher_id FK User
CREATE TABLE assignments (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id  INT NOT NULL,
    title       VARCHAR(255) NOT NULL,
    subject     VARCHAR(255) NOT NULL,
    assign_date DATE NOT NULL,
    due_date    DATE NOT NULL,
    status      ENUM('Open', 'Closed') NOT NULL DEFAULT 'Open',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE assignment_submissions (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id   INT NOT NULL,
    student_id      INT NOT NULL,
    submission_text TEXT NOT NULL,
    submitted_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    marks           INT NULL,
    feedback        TEXT NULL,
    status          ENUM('Submitted', 'Marked', 'Late') NOT NULL DEFAULT 'Submitted',
    FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY uq_assignment_student (assignment_id, student_id)
) ENGINE=InnoDB;

-- Sample data
INSERT INTO reports (user_id, title, subject, priority, assign_date, due_date, status) VALUES
(2, 'Semester Progress Report', 'Computer Science', 'High', '2026-05-01', '2026-06-30', 'In Progress'),
(3, 'Weekly Study Log', 'Mathematics', 'Medium', '2026-06-01', '2026-06-15', 'Pending');

INSERT INTO scheduled_classes (report_id, user_id, title, instructor, classroom, start_time, end_time) VALUES
(1, 3, 'Database Systems', 'Dr. Smith', 'Room 204', '2026-06-10 09:00:00', '2026-06-10 10:30:00'),
(1, 3, 'Web Development Lab', 'Prof. Lee', 'Lab B', '2026-06-11 14:00:00', '2026-06-11 16:00:00');

INSERT INTO exams (scheduled_class_id, user_id, title, subject, duration_minutes) VALUES
(1, 2, 'DB Midterm', 'Database Systems', 90),
(1, 2, 'DB Quiz 1', 'Database Systems', 30);

INSERT INTO exam_questions (exam_id, user_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES
(1, 2, 'What does SQL stand for?', 'Structured Query Language', 'Simple Query Language', 'Sequential Query Language', 'None', 'A'),
(1, 2, 'Primary key must be?', 'Nullable', 'Unique', 'Duplicate', 'Optional', 'B');

INSERT INTO assignments (teacher_id, title, subject, assign_date, due_date, status) VALUES
(2, 'ER Diagram Assignment', 'Database Systems', '2026-06-01', '2026-06-20', 'Open'),
(2, 'PHP CRUD Project', 'Web Development', '2026-06-05', '2026-06-25', 'Open');

INSERT INTO assignment_submissions (assignment_id, student_id, submission_text, status) VALUES
(1, 3, 'Submitted ER diagram for library system.', 'Submitted');
