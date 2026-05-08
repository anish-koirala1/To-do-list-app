<?php
 
$host = "localhost";
$dbname = "to_do_applist";
$username = "root";
$password = "";
 
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname",
        $username,
        $password
    );
 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}