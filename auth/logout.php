<?php
/**
 * Logout Handler - logout.php
 * 
 * Terminates the user's session and clears all authentication data.
 * Redirects the user back to the login page after logout.
 */

// Start the session to access session data
session_start();

// Unset all session variables to clear user data
session_unset();

// Destroy the entire session to terminate the user's login
session_destroy();

// Redirect back to login page
header('Location: login.php');
exit;