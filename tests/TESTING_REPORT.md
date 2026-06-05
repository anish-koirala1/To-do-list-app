# APSU Testing Report

**Generated:** 2026-06-05 10:19:21  
**Run id:** `2026-06-05_10-19-17`  
**Master log:** `logs/TEST_RUN_MASTER_2026-06-05_10-19-17.log`  
**Overall:** **PASS** (2/2 suites passed)

## Rubric alignment (Testing 10%)

| Requirement | Evidence |
|-------------|----------|
| Test plan | `tests/TEST_PLAN.md` |
| Test data | `tests/test_data.php` |
| Component tests | `run_users_tests.php` → 4 log files |
| Class tests | `run_model_class_tests.php` → 4 model log files |
| Complete logs | `tests/logs/` (this run only) |

## Suite results

| Suite | Owner | Script | Result |
|-------|-------|--------|--------|
| Users & Auth (component) | Anish Koirala | `run_users_tests.php` | **PASS** (exit 0) |
| Model classes (per-class) | Team | `run_model_class_tests.php` | **PASS** (exit 0) |

## Log files

- `logs/TEST_RUN_MASTER_2026-06-05_10-19-17.log`
- `logs/TEST_class_Assignment_2026-06-05_10-19-17.log`
- `logs/TEST_class_Exam_2026-06-05_10-19-17.log`
- `logs/TEST_class_Report_2026-06-05_10-19-17.log`
- `logs/TEST_class_ScheduledClass_2026-06-05_10-19-17.log`
- `logs/TEST_users_crud_2026-06-05_10-19-17.log`
- `logs/TEST_users_find_filter_2026-06-05_10-19-17.log`
- `logs/TEST_users_login_2026-06-05_10-19-17.log`
- `logs/TEST_users_validation_2026-06-05_10-19-17.log`

## Reproduce

```powershell
cd app
php tests/run_all_tests.php
```
