<?php

// Database connection settings.
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'user_management');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Return a shared PDO connection for the whole request.
function getDB(): PDO {
    // Keep one connection instance so repeated calls do not reconnect.
    static $pdo = null;

    if ($pdo === null) {
        // Build the MySQL DSN using the configured host, database, and charset.
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

        // Configure PDO to throw exceptions and return rows as associative arrays.
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            // Create the database connection.
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log the real error for developers, but show users a safe message.
            error_log('Database connection failed: ' . $e->getMessage());
            die('A database error occurred. Please try again later.');
        }
    }

    // Return the existing connection.
    return $pdo;
}
