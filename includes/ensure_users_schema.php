<?php

/**
 * Login helper — expects users table per class diagram (id, username, email, …).
 * Import setup.sql for a fresh database.
 */
function findUserForLogin(PDO $pdo, string $login): ?array
{
    $login = trim($login);
    if ($login === '') {
        return null;
    }

    try {
        $fields = array_column($pdo->query('DESCRIBE users')->fetchAll(PDO::FETCH_ASSOC), 'Field');
    } catch (PDOException $e) {
        error_log('findUserForLogin: ' . $e->getMessage());
        return null;
    }

    $pk = in_array('id', $fields, true) ? 'id' : (in_array('user_id', $fields, true) ? 'user_id' : null);
    if ($pk === null || !in_array('password', $fields, true)) {
        return null;
    }

    if (filter_var($login, FILTER_VALIDATE_EMAIL) && in_array('email', $fields, true)) {
        $where = 'email = ?';
    } elseif (in_array('username', $fields, true)) {
        $where = 'username = ?';
    } elseif (in_array('email', $fields, true)) {
        $where = 'email = ?';
    } else {
        return null;
    }

    $nameCol = in_array('full_name', $fields, true) ? 'full_name' : 'name';
    $roleCol = in_array('role', $fields, true) ? 'role' : "'Student'";
    $activeCol = in_array('is_active', $fields, true) ? 'is_active' : '1';

    $sql = "SELECT {$pk} AS user_id, {$nameCol} AS full_name, email, password, {$roleCol} AS role, {$activeCol} AS is_active
            FROM users WHERE {$where} LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}
