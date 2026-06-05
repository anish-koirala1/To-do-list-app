# APSU Test Plan — Testing Criterion (10%)

**Component owner (Users & Auth):** Anish Koirala  
**Project:** Academic Portal System for University (APSU)  
**Database:** `academic_management_system`

---

## 1. How to run (single command)

```powershell
cd "c:\Users\59094\Downloads\TO_DO_APP(ANISH)\app"
C:\xampp\php\php.exe tests\run_all_tests.php
```

This command:

1. Deletes any **previous** logs in `tests/logs/`
2. Runs all test suites
3. Writes fresh logs and `tests/TESTING_REPORT.md`

**Prerequisite:** MySQL running; `setup.sql` imported.

---

## 2. Test structure

```
tests/
├── run_all_tests.php          ← only entry point
├── run_users_tests.php        ← Users & Auth (Anish)
├── run_model_class_tests.php  ← Report, ScheduledClass, Exam, Assignment
├── test_data.php              ← typed fixtures
├── TEST_PLAN.md               ← this document
├── TESTING_REPORT.md          ← generated results
├── lib/
│   ├── TestLogger.php
│   └── UserTestHelpers.php
└── logs/                      ← latest run only (9 files)
```

---

## 3. Test data (`test_data.php`)

| Data type | Sample | Used in |
|-----------|--------|---------|
| INT | `user_id`, marks `88`, duration `90` | CRUD, Exam, Assignment |
| VARCHAR | `Łukasz O'Brien`, `lukasz.obrien-staff` | Users, classes |
| TEXT | Assignment submission paragraph | Assignment submit |
| ENUM | `Admin`, `High`, `Open`, `Pending` | Users, reports, assignments |
| BOOLEAN | `is_active` 0 / 1 | Login, disable |
| DATE | `2026-06-01`, `2026-08-15` | Reports, assignments |
| DATETIME | `2026-09-15T09:00` | Scheduled classes |
| PASSWORD | bcrypt `Admin@1234` etc. | Login tests |
| Invalid / boundary | 101-char name, bad email, end before start | Validation suites |

---

## 4. Log files (one run = 9 files)

| Log file | Class / function | Owner |
|----------|------------------|-------|
| `TEST_users_login_*.log` | `findUserForLogin()` | Anish Koirala |
| `TEST_users_crud_*.log` | Users table ADD/EDIT/DELETE/LIST | Anish Koirala |
| `TEST_users_find_filter_*.log` | Search & role/status filter | Anish Koirala |
| `TEST_users_validation_*.log` | `validateUserCreateData()` | Anish Koirala |
| `TEST_class_Report_*.log` | `Report` model | Utsav Luitel |
| `TEST_class_ScheduledClass_*.log` | `ScheduledClass` model | Puskar Bastola |
| `TEST_class_Exam_*.log` | `Exam` model | Puskar Bastola |
| `TEST_class_Assignment_*.log` | `Assignment` model | Sunil Kumar BK |
| `TEST_RUN_MASTER_*.log` | Combined console output | — |

---

## 5. Users & Auth test cases (Anish Koirala)

### Login
- Valid login by username and email for Admin, Teacher, Student
- Empty login, unknown user, wrong password → rejected
- Disabled account (`is_active=0`)

### CRUD (transactional, rolled back)
- INSERT with unicode name
- UPDATE name and role
- SELECT verifies change
- Toggle `is_active`
- DELETE removes row

### Find & Filter
- Search: partial name, email domain, no-match term
- Filter: each role + active/disabled

### Validation
- Valid and boundary (100-char name) payloads
- 9 invalid cases from `test_data.php`
- Duplicate username rejected

---

## 6. Model class test cases

Each model class is tested for **list/read, filter/search, create, update, delete** (where applicable) using fixtures from `test_data.php`. Destructive tests use DB transactions and roll back.

---

## 7. Pass criteria

- `php tests/run_all_tests.php` exits **0**
- Every log shows **FAIL: 0**
- `TESTING_REPORT.md` shows **Overall: PASS**

---

## 8. Submission evidence

1. `tests/TEST_PLAN.md`
2. `tests/test_data.php`
3. `tests/TESTING_REPORT.md`
4. All files in `tests/logs/` from your final run
