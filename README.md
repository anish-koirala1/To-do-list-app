# APSU — Academic Portal

**APSU** (Academic Portal System for University) is a PHP web application for managing users, academic reports, scheduled classes, exams, and assignments. It follows the team **class diagram** schema and is built for XAMPP/WAMP with MySQL (phpMyAdmin).

**Repository:** [https://github.com/anish-koirala1/To-do-list-app](https://github.com/anish-koirala1/To-do-list-app)

---

## Team modules

| Module | Owner | Features |
|--------|--------|----------|
| **Users & auth** | Anish Koirala | Login, roles (Admin / Teacher / Student), add, edit, delete, enable/disable users |
| **Reports & progress** | Utsav Luitel | Report CRUD, status, priority, progress tracking |
| **Scheduled classes** | Puskar Bastola | Class schedules linked to reports |
| **Exams** | Puskar Bastola | Exams per class, MCQ questions, student attempts, scoring |
| **Assignments** | Sunil Kumar BK | Assignments, student submissions, teacher marking |

---

## Requirements

- **PHP** 8.0+ (with PDO MySQL)
- **MySQL** 5.7+ or MariaDB
- **Apache** (XAMPP, WAMP, or similar)
- **phpMyAdmin** (optional, for importing SQL)

---

## Quick start

### 1. Copy the project

Place the `app` folder under your web server document root, for example:

```
C:\xampp\htdocs\TO_DO_APP\app\
```

### 2. Configure the database

Edit `config/database.php` if your MySQL settings differ:

```php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'academic_management_system');
define('DB_USER', 'root');
define('DB_PASS', '');  // your MySQL password
```

### 3. Create the database

In **phpMyAdmin** (or MySQL CLI):

1. Open the **Import** tab or SQL tab.
2. Run the full script: **`setup.sql`**

This creates database `academic_management_system`, all tables, sample data, and default users.

To reset **only users** (keep other tables), run **`set_users.sql`** instead.

### 4. Open the app

| Page | URL (example) |
|------|----------------|
| Landing | `http://localhost/TO_DO_APP/app/index.php` |
| Login | `http://localhost/TO_DO_APP/app/auth/login.php` |

After login, you are redirected to the **Users** dashboard.

---

## Default login accounts

Sign in with **username** or **email** and the password below.

| Role | Username | Email | Password |
|------|----------|--------|----------|
| Admin | `admin` | `admin@example.com` | `Admin@1234` |
| Teacher | `teacher` | `teacher@example.com` | `Teacher@1234` |
| Student | `student` | `student@example.com` | `Student@1234` |

Passwords are stored as **bcrypt** hashes in the database.

---

## Project structure

```
app/
├── index.php                 # Public landing page
├── setup.sql                 # Full database install + sample data
├── set_users.sql             # Default admin/teacher/student users only
├── config/
│   └── database.php          # PDO connection settings
├── auth/
│   ├── login.php
│   ├── authenticate.php
│   └── logout.php
├── users/                    # User management (Anish)
│   ├── list_users.php
│   ├── add_user.php
│   ├── edit_user.php
│   └── ...
├── reports/                  # Reports & progress (Utsav)
│   ├── index.php
│   ├── model/Report.php
│   └── views/
├── classes/                  # Scheduled classes (Puskar)
│   ├── index.php
│   ├── model/ScheduledClass.php
│   └── views/
├── exams/                    # Exams, questions, attempts (Puskar)
│   ├── index.php
│   ├── model/Exam.php
│   └── views/
├── assignments/              # Assignments & submissions (Sunil)
│   ├── index.php
│   ├── model/Assignment.php
│   └── views/
├── includes/
│   ├── header.php / footer.php
│   ├── auth_check.php        # Session guard, isAdmin(), requireAdmin()
│   ├── helpers.php           # isTeacher(), isStudent(), flash redirects
│   └── ensure_users_schema.php  # Login lookup (email or username)
└── assets/
    ├── css/style.css         # University theme (navy & gold)
    └── js/main.js
```

---

## Database schema

Database name: **`academic_management_system`**

| Table | Description |
|-------|-------------|
| `users` | Accounts with role: Admin, Teacher, Student |
| `reports` | Academic reports (FK → `users`) |
| `scheduled_classes` | Class schedule (FK → `reports`, `users`) |
| `exams` | Exams (FK → `scheduled_classes`, `users`) |
| `exam_questions` | MCQ questions per exam |
| `exam_attempts` | Student exam attempts and scores |
| `exam_attempt_answers` | Answers per attempt |
| `assignments` | Teacher assignments |
| `assignment_submissions` | Student submissions and marks |

Primary key on users is **`id`** (not `user_id`). The app uses `id` in SQL and `user_id` in session after login.

---

## Navigation (after login)

| Menu | Path | Who can use |
|------|------|-------------|
| Users | `users/list_users.php` | All logged-in users; add/edit/delete for **Admin** |
| Reports | `reports/index.php` | All roles (CRUD per permissions in module) |
| Classes | `classes/index.php` | Teachers & admins create; students view |
| Exams | `exams/index.php` | Teachers add questions; students take exams |
| Assignments | `assignments/index.php` | Teachers manage; students submit |

---

## Roles

| Role | Typical access |
|------|----------------|
| **Admin** | Full user management, all modules |
| **Teacher** | Reports, classes, exams, assignments (create/mark) |
| **Student** | View reports/classes, take exams, submit assignments |

Helper functions: `isAdmin()`, `isTeacher()`, `isStudent()` in `includes/auth_check.php` and `includes/helpers.php`.

---

## Git

The Git repository root is the **`app`** folder:

```bash
cd app
git status
git push origin main
```

Remote: `https://github.com/anish-koirala1/To-do-list-app.git`

---

## Troubleshooting

| Problem | What to do |
|---------|------------|
| Database connection error | Check Apache/MySQL are running; verify `config/database.php` |
| Invalid login | Re-import `setup.sql` or run `set_users.sql`; use credentials above |
| Unknown column `user_id` | Re-run `setup.sql` so schema matches the app (`id` on `users`) |
| Blank page / PHP error | Enable errors in `php.ini` or check Apache `error.log` |
| CSS not loading | Confirm URL path matches folder name; hard refresh (`Ctrl+F5`) |

---

## Security notes (development)

- Default passwords are for **local demo only** — change them in production.
- Use HTTPS and strong secrets on a real server.
- Do not commit real database passwords to Git.

---

## License & credits

Academic group project — APSU portal integrated from team branches (users, reports, classes, exams, assignments).

For issues or updates, use the GitHub repository above.
