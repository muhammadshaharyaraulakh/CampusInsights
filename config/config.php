<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    header("Location: /pages/404.php");
    exit;
}

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', '');
define('SMTP_PASSWORD', ''); 
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');


$host = "localhost";
$dataBase = "SurveyProgram";
$db_user = "root";
$db_password = "1234";
$charset = "utf8mb4";

$dataSourceName = "mysql:host=$host;dbname=$dataBase;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES => false,
];


try {
    $connection = new PDO($dataSourceName, $db_user, $db_password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . htmlspecialchars($e->getMessage()));
}
?>