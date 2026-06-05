<?php
/**
 * Test data fixtures — imaginative, typed samples for all APSU modules.
 *
 * Data types covered:
 *   INT, VARCHAR, TEXT, ENUM, BOOLEAN, DATE, DATETIME, TIMESTAMP, bcrypt PASSWORD
 *
 * Used by: run_users_tests.php, run_model_class_tests.php, run_validation_tests.php
 */
declare(strict_types=1);

return [
    'meta' => [
        'project'   => 'APSU Academic Portal',
        'database'  => 'academic_management_system',
        'generated' => 'Manual fixture set for module testing',
    ],

    // --- Users & auth (Anish Koirala) ---
    'users' => [
        'valid_logins' => [
            ['login' => 'admin', 'password' => 'Admin@1234', 'role' => 'Admin', 'type' => 'username'],
            ['login' => 'admin@example.com', 'password' => 'Admin@1234', 'role' => 'Admin', 'type' => 'email'],
            ['login' => 'teacher', 'password' => 'Teacher@1234', 'role' => 'Teacher', 'type' => 'username'],
            ['login' => 'teacher@example.com', 'password' => 'Teacher@1234', 'role' => 'Teacher', 'type' => 'email'],
            ['login' => 'student', 'password' => 'Student@1234', 'role' => 'Student', 'type' => 'username'],
        ],
        'invalid_logins' => [
            ['login' => '', 'password' => 'Admin@1234', 'reason' => 'empty identifier'],
            ['login' => 'ghost@university.edu', 'password' => 'WrongPass1', 'reason' => 'unknown account'],
            ['login' => 'admin', 'password' => 'wrong', 'reason' => 'bad password'],
        ],
        'create_valid' => [
            'full_name' => "Łukasz O'Brien",       // VARCHAR + unicode + apostrophe
            'username'  => 'lukasz.obrien-staff',  // VARCHAR pattern with . and -
            'email'     => 'lukasz.obrien@uni.edu.dk',
            'password'  => 'SecurePass9',
            'role'      => 'Teacher',              // ENUM
            'is_active' => 1,                      // BOOLEAN as TINYINT
        ],
        'create_boundary' => [
            'full_name' => str_repeat('A', 100),   // max length VARCHAR(100)
            'username'  => 'abc',                  // min length 3
            'email'     => 'a@b.co',
            'password'  => 'Abcd1234',
            'role'      => 'Student',
        ],
        'create_invalid' => [
            ['field' => 'full_name', 'value' => '', 'expect' => 'required'],
            ['field' => 'full_name', 'value' => 'John Smith 2', 'expect' => 'no digits'],
            ['field' => 'full_name', 'value' => str_repeat('X', 101), 'expect' => 'max 100'],
            ['field' => 'username', 'value' => 'ab', 'expect' => 'min 3 chars'],
            ['field' => 'username', 'value' => 'bad user!', 'expect' => 'pattern'],
            ['field' => 'email', 'value' => 'not-an-email', 'expect' => 'format'],
            ['field' => 'password', 'value' => 'short', 'expect' => 'min 8'],
            ['field' => 'password', 'value' => 'alllowercase1', 'expect' => 'uppercase'],
            ['field' => 'role', 'value' => 'SuperUser', 'expect' => 'invalid enum'],
        ],
        'search_terms' => [
            ['q' => 'admin', 'expect_min' => 1, 'type' => 'partial name'],
            ['q' => 'example.com', 'expect_min' => 1, 'type' => 'email domain'],
            ['q' => 'teacher', 'expect_min' => 1, 'type' => 'role keyword in data'],
            ['q' => 'ZZZNOMATCH999', 'expect_min' => 0, 'type' => 'no results'],
        ],
        'filter_cases' => [
            ['role' => 'Admin', 'status' => '1', 'label' => 'active admins'],
            ['role' => 'Teacher', 'status' => '1', 'label' => 'active teachers'],
            ['role' => 'Student', 'status' => '1', 'label' => 'active students'],
            ['role' => '', 'status' => '0', 'label' => 'disabled any role'],
        ],
    ],

    // --- Report model (Utsav Luitel) ---
    'reports' => [
        'valid' => [
            'title' => 'Internship Portfolio Review',
            'subject' => 'Software Engineering',
            'priority' => 'High',
            'assign_date' => '2026-05-01',
            'due_date' => '2026-08-15',
            'status' => 'In Progress',
        ],
        'invalid' => [
            ['title' => '', 'due_before_assign' => true],
            ['priority' => 'Urgent', 'status' => 'Pending'],
        ],
        'filter_status' => ['Pending', 'In Progress', 'Completed'],
        'filter_priority' => ['Low', 'Medium', 'High'],
    ],

    // --- ScheduledClass model (Puskar Bastola) ---
    'classes' => [
        'valid' => [
            'title' => 'Advanced Database Systems',
            'instructor' => 'Prof. Elena Vasquez',
            'classroom' => 'Building C — Lab 3.14',
            'start_time' => '2026-09-15T09:00',
            'end_time' => '2026-09-15T11:30',
        ],
        'invalid' => [
            'end_before_start' => [
                'start_time' => '2026-09-15T14:00',
                'end_time' => '2026-09-15T10:00',
            ],
        ],
    ],

    // --- Exam model (Puskar Bastola) ---
    'exams' => [
        'mcq_sample' => [
            'question_text' => 'Which normal form removes transitive dependencies?',
            'option_a' => '1NF', 'option_b' => '2NF', 'option_c' => '3NF', 'option_d' => 'BCNF',
            'correct_option' => 'C',
        ],
        'duration_minutes' => [30, 60, 90, 120],
    ],

    // --- Assignment model (Sunil Kumar BK) ---
    'assignments' => [
        'valid' => [
            'title' => 'REST API Design Document',
            'subject' => 'Web Services',
            'assign_date' => '2026-06-01',
            'due_date' => '2026-06-28',
            'status' => 'Open',
        ],
        'submission_text' => 'Implemented CRUD endpoints with JWT auth and PHPUnit tests.',
        'search_term' => 'ER',
        'status_filter' => ['Open', 'Closed'],
    ],
];
