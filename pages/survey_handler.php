<?php
header('Content-Type: application/json');
require __DIR__ . "/../config/config.php";
require __DIR__ . "/../function/function.php";

blockAccess();
$MAX_SECTIONS = 5;
$user_id = $_SESSION['id'];
$current_section = (int)($_POST['section_number'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $current_section < 1 || $current_section > $MAX_SECTIONS) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request or invalid section number.'
    ]);
    exit;
}

$json_data = json_encode($_POST);

try {
    $stmt = $connection->prepare("SELECT 1 FROM survey_progress WHERE user_id = :uid AND section_number = :snum");
    $stmt->execute([':uid' => $user_id, ':snum' => $current_section]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        $stmt = $connection->prepare("
            UPDATE survey_progress 
            SET section_data = :data, submitted_at = NOW() 
            WHERE user_id = :uid AND section_number = :snum
        ");
        $stmt->execute([
            ':data' => $json_data,
            ':uid' => $user_id,
            ':snum' => $current_section
        ]);
    } else {
        $stmt = $connection->prepare("
            INSERT INTO survey_progress (user_id, section_number, section_data) 
            VALUES (:uid, :snum, :data)
        ");
        $stmt->execute([
            ':uid' => $user_id,
            ':snum' => $current_section,
            ':data' => $json_data
        ]);
    }

    $activity_type = null;
    
    if ($current_section === 1 && !$exists) {
        $activity_type = 'survey_start';
    } 
    elseif ($current_section === $MAX_SECTIONS) {
        $activity_type = 'survey_submit';
    }

    if ($activity_type) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        $ua_details = parse_user_agent_details($user_agent); 
        
        $log_details = json_encode([
            'browser' => $ua_details['browser'],
            'os'      => $ua_details['os'],
            'section' => $current_section
        ]);

        $logStmt = $connection->prepare("
            INSERT INTO ActivityLog (user_id, activity_type, details, ip_address, user_agent) 
            VALUES (:uid, :atype, :details, :ip, :ua)
        ");
        
        $logStmt->execute([
            ':uid'     => $user_id,
            ':atype'   => $activity_type,
            ':details' => $log_details,
            ':ip'      => $ip_address,
            ':ua'      => $user_agent
        ]);
    }

    $next_section = $current_section + 1;
    $is_final = ($current_section == $MAX_SECTIONS);

    echo json_encode([
        'success' => true,
        'message' => "Section $current_section saved successfully.",
        'next_section' => $next_section,
        'is_final' => $is_final
    ]);

} catch (PDOException $e) {
    error_log("DB Error in survey_handler: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'Internal server error occurred while processing your request.'
    ]);
}
?>