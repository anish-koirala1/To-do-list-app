<?php
/**
 * Server-side validation for reports and scheduled classes forms.
 */

/** @return array{data: array, errors: array<string, string>} */
function validateReportData(array $post): array
{
    $data = [
        'title'       => trim($post['title'] ?? ''),
        'subject'     => trim($post['subject'] ?? ''),
        'priority'    => $post['priority'] ?? '',
        'assign_date' => $post['assign_date'] ?? '',
        'due_date'    => $post['due_date'] ?? '',
        'status'      => $post['status'] ?? '',
    ];
    $errors = [];

    if ($data['title'] === '') {
        $errors['title'] = 'Title is required.';
    } elseif (mb_strlen($data['title']) > 255) {
        $errors['title'] = 'Title must not exceed 255 characters.';
    }

    if ($data['subject'] === '') {
        $errors['subject'] = 'Subject is required.';
    } elseif (mb_strlen($data['subject']) > 255) {
        $errors['subject'] = 'Subject must not exceed 255 characters.';
    }

    $priorities = ['Low', 'Medium', 'High'];
    if (!in_array($data['priority'], $priorities, true)) {
        $errors['priority'] = 'Please select a valid priority.';
    }

    $statuses = ['Pending', 'In Progress', 'Completed'];
    if (!in_array($data['status'], $statuses, true)) {
        $errors['status'] = 'Please select a valid status.';
    }

    if ($data['assign_date'] === '') {
        $errors['assign_date'] = 'Assign date is required.';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['assign_date'])) {
        $errors['assign_date'] = 'Assign date must be a valid date.';
    }

    if ($data['due_date'] === '') {
        $errors['due_date'] = 'Due date is required.';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['due_date'])) {
        $errors['due_date'] = 'Due date must be a valid date.';
    }

    if (empty($errors['assign_date']) && empty($errors['due_date'])
        && strtotime($data['due_date']) < strtotime($data['assign_date'])) {
        $errors['due_date'] = 'Due date must be on or after the assign date.';
    }

    return ['data' => $data, 'errors' => $errors];
}

/** @return array{data: array, errors: array<string, string>} */
function validateClassData(array $post, PDO $pdo): array
{
    $reportId = (int)($post['report_id'] ?? 0) ?: null;
    $data = [
        'report_id'   => $reportId,
        'user_id'     => (int)($post['user_id'] ?? 0),
        'title'       => trim($post['title'] ?? ''),
        'instructor'  => trim($post['instructor'] ?? ''),
        'classroom'   => trim($post['classroom'] ?? ''),
        'start_time'  => trim($post['start_time'] ?? ''),
        'end_time'    => trim($post['end_time'] ?? ''),
    ];
    $errors = [];

    if ($data['title'] === '') {
        $errors['title'] = 'Class title is required.';
    } elseif (mb_strlen($data['title']) > 255) {
        $errors['title'] = 'Title must not exceed 255 characters.';
    }

    if ($data['instructor'] === '') {
        $errors['instructor'] = 'Instructor name is required.';
    } elseif (mb_strlen($data['instructor']) > 255) {
        $errors['instructor'] = 'Instructor must not exceed 255 characters.';
    }

    if ($data['classroom'] === '') {
        $errors['classroom'] = 'Classroom is required.';
    } elseif (mb_strlen($data['classroom']) > 255) {
        $errors['classroom'] = 'Classroom must not exceed 255 characters.';
    }

    if ($reportId !== null) {
        $chk = $pdo->prepare('SELECT id FROM reports WHERE id = ?');
        $chk->execute([$reportId]);
        if (!$chk->fetch()) {
            $errors['report_id'] = 'Selected report does not exist.';
        }
    }

    $startTs = strtotime(str_replace('T', ' ', $data['start_time']));
    $endTs   = strtotime(str_replace('T', ' ', $data['end_time']));

    if ($data['start_time'] === '') {
        $errors['start_time'] = 'Start time is required.';
    } elseif ($startTs === false) {
        $errors['start_time'] = 'Start time must be a valid date and time.';
    }

    if ($data['end_time'] === '') {
        $errors['end_time'] = 'End time is required.';
    } elseif ($endTs === false) {
        $errors['end_time'] = 'End time must be a valid date and time.';
    }

    if ($startTs !== false && $endTs !== false && $endTs <= $startTs) {
        $errors['end_time'] = 'End time must be after the start time.';
    }

    // Normalise datetime for MySQL
    if ($startTs !== false) {
        $data['start_time'] = date('Y-m-d H:i:s', $startTs);
    }
    if ($endTs !== false) {
        $data['end_time'] = date('Y-m-d H:i:s', $endTs);
    }

    return ['data' => $data, 'errors' => $errors];
}
