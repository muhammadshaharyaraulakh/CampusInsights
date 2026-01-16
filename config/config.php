<?php
// Start session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent direct access to this file
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    header("Location: /pages/404.php");
    exit;
}
// ==========================
// EMAIL CONFIGURATION (SMTP)
// ==========================
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'muhammadshaharyaraulakh@gmail.com');
define('SMTP_PASSWORD', 'sfyw hous shvc bytw'); 
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
// ==========================
// Database Configuration
// ==========================

$host = "localhost";
$dataBase = "SurveyProgram";
$db_user = "root";
$db_password = "1234";
$charset = "utf8mb4";

// Create DSN (Data Source Name) for PDO
$dataSourceName = "mysql:host=$host;dbname=$dataBase;charset=$charset";

// ==========================
// PDO Options 
// ==========================
// ATTR_ERRMODE: Throw errors as exceptions
// ATTR_DEFAULT_FETCH_MODE: Fetch results as objects
// ATTR_EMULATE_PREPARES: Use real prepared statements 

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// ==========================
// Connect to Database
// ==========================

try {
    $connection = new PDO($dataSourceName, $db_user, $db_password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . htmlspecialchars($e->getMessage()));
}
?>