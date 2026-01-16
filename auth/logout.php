<?php
require_once __DIR__."/../config/config.php";
require_once __DIR__."/../function/function.php";
$userId = $_SESSION['id'] ?? null;

if ($userId) {
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent_full = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $userAgent_details = parse_user_agent_details($userAgent_full);
    $details_json = json_encode([
        'device_summary' => $userAgent_details['browser'] . ' on ' . $userAgent_details['os']
    ]);
    
    $stmtLog = $connection->prepare("
        INSERT INTO ActivityLog (user_id, activity_type, details, ip_address, user_agent) 
        VALUES (:user_id, 'logout', :details, :ip_address, :ua_full)
    ");
    
    $stmtLog->execute([
        ':user_id' => $userId,
        ':details' => $details_json, 
        ':ip_address'  => $ip,
        ':ua_full'  => $userAgent_full 
    ]);
}

$_SESSION = [];
session_unset();
session_destroy();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

header("Location: /index.php");
exit;
