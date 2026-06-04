<?php
/**
 * Database Configuration - database.php
 * 
 * Centralized database connection configuration and management.
 * This file defines all database credentials and provides a singleton PDO connection
 * that is reused throughout the application to avoid connection overhead.
 */

// Database connection settings
define('DB_HOST', '127.0.0.1');      // MySQL server hostname
define('DB_NAME', 'academic_management_system');  // Database name
define('DB_USER', 'root');           // Database username
define('DB_PASS', '');               // Database password
define('DB_CHARSET', 'utf8mb4');     // Character set for Unicode support

/**
 * getDB() - Get a shared PDO database connection
 * 
 * Returns a singleton PDO instance to the MySQL database.
 * This ensures only one connection is created per request,
 * improving performance and reducing server overhead.
 * 
 * @return PDO The database connection object
 * @throws PDOException if connection fails (caught and logged)
 */
function getDB(): PDO {
    // Use static variable to maintain connection across multiple calls
    static $pdo = null;

    // Only initialize if not already connected
    if ($pdo === null) {
        // Build the MySQL DSN (Data Source Name) with connection parameters
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

        // Configure PDO options for security and error handling
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Return rows as associative arrays
            PDO::ATTR_EMULATE_PREPARES   => false,                    // Use native prepared statements
        ];

        try {
            // Establish the PDO connection using credentials
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log the detailed error for debugging
            error_log('Database connection failed: ' . $e->getMessage());
            // Display generic error message to users for security
            die('A database error occurred. Please try again later.');
        }
    }

    // Return the persistent connection
    return $pdo;
}