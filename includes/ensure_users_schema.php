<?php
/**
 * User Login Helper - ensure_users_schema.php
 * 
 * This file contains the findUserForLogin() function which is database-agnostic.
 * It dynamically adapts to the actual schema of the users table, supporting
 * different column naming conventions (id vs user_id, full_name vs name, etc).
 * 
 * This flexibility allows the system to work with various database designs
 * without code changes.
 * 
 * Requirements:
 * - Import setup.sql to create a fresh database with the correct schema
 * - The users table must have: id (or user_id), email, password, full_name, role, is_active
 */

/**
 * findUserForLogin() - Find and return a user by email or username
 * 
 * This function is schema-flexible, automatically detecting:
 * - Primary key column name (id or user_id)
 * - Name column name (full_name or name)
 * - Whether to look up by email or username
 * 
 * @param PDO $pdo The database connection
 * @param string $login The email or username to search for
 * @return array|null The user record with keys: user_id, full_name, email, password, role, is_active
 *                    Returns null if user not found or database error occurs
 */
function findUserForLogin(PDO $pdo, string $login): ?array
{
    // Normalize input: trim whitespace
    $login = trim($login);
    if ($login === '') {
        return null;
    }

    try {
        // Get all column names from the users table
        $fields = array_column($pdo->query('DESCRIBE users')->fetchAll(PDO::FETCH_ASSOC), 'Field');
    } catch (PDOException $e) {
        // Log database errors for debugging
        error_log('findUserForLogin: ' . $e->getMessage());
        return null;
    }

    // Detect the primary key column name (usually 'id' or 'user_id')
    $pk = in_array('id', $fields, true) ? 'id' : (in_array('user_id', $fields, true) ? 'user_id' : null);
    if ($pk === null || !in_array('password', $fields, true)) {
        return null;  // Required columns don't exist
    }

    // Determine WHERE clause: prefer email if it's a valid email address and the column exists
    if (filter_var($login, FILTER_VALIDATE_EMAIL) && in_array('email', $fields, true)) {
        $where = 'email = ?';
    } elseif (in_array('username', $fields, true)) {
        // Fall back to username column
        $where = 'username = ?';
    } elseif (in_array('email', $fields, true)) {
        // Or try email if not recognized as email
        $where = 'email = ?';
    } else {
        return null;  // No suitable lookup column found
    }

    // Detect the name column: prefer 'full_name' over 'name'
    $nameCol = in_array('full_name', $fields, true) ? 'full_name' : 'name';
    
    // Detect the role column: use 'role' if it exists, otherwise default to 'Student'
    $roleCol = in_array('role', $fields, true) ? 'role' : "'Student'";
    
    // Detect the active status column: use 'is_active' if it exists, otherwise assume all users are active
    $activeCol = in_array('is_active', $fields, true) ? 'is_active' : '1';

    // Build the SQL query with the detected column names
    $sql = "SELECT {$pk} AS user_id, {$nameCol} AS full_name, email, password, {$roleCol} AS role, {$activeCol} AS is_active
            FROM users WHERE {$where} LIMIT 1";

    // Execute the query with the login value
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the user record or null if not found
    return $user ?: null;
}
