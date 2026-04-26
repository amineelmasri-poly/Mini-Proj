<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  
define('DB_NAME', 'cafe_local');

define('SITE_NAME', 'Le Café Local');
define('SITE_EMAIL', 'amineelmasri@outlook.com');
define('SITE_URL', 'http://localhost:8000'); 

define('ADMIN_EMAIL', 'amineelmasri@outlook.com');
date_default_timezone_set('Africa/Tunis');

error_reporting(E_ALL);
ini_set('display_errors', 1);

function getDBConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $conn;
    } catch(PDOException $e) {
        throw new RuntimeException("Database connection failed", 0, $e);
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
?>
