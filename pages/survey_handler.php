<?php
header('Content-Type: application/json');
require __DIR__ . "/../config/config.php";
require __DIR__ . "/../function/function.php";

blockAccess();
$MAX_SECTIONS = 5;
$user_id = $_SESSION['id'];
$current_section = (int)($_POST['section_number'] ?? 0);

// Validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $current_section < 1 || $current_section > $MAX_SECTIONS) {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

try {
    // ======================================================
    // 1. GET SEMESTER VIA JOIN (User -> BatchSection -> Batches)
    // ======================================================
    


    $stmt = $connection->prepare("SELECT b.current_semester 
              FROM user u
              JOIN batch_sections bs ON u.batch_section_id = bs.id
              JOIN batches b ON bs.batch_id = b.id
              WHERE u.id = :uid LIMIT 1");
    $stmt->execute([':uid' => $user_id]);
    $batchData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$batchData) {
        echo json_encode(['success' => false, 'message' => 'Batch info not found for user.']);
        exit;
    }

    $semester = (int)$batchData['current_semester'];
    

    
    $current_year = date("Y");
    $session_name = "";
    if ($semester % 2 == 0) {
        $session_name = "Spring";
    } else {
        $session_name = "Fall";
    }
    $year_session = "$current_year($session_name)";


    $json_data = json_encode($_POST);
    $column_name = "section_" . $current_section;

    $stmt = $connection->prepare("INSERT INTO survey_progress (user_id, year_session, student_semester, $column_name) 
            VALUES (:uid, :ysession, :sem, :data) 
            ON DUPLICATE KEY UPDATE $column_name = :data_update");
    $stmt->execute([
        ':uid' => $user_id,
        ':ysession' => $year_session,
        ':sem' => $semester,
        ':data' => $json_data,
        ':data_update' => $json_data
    ]);


    
    $is_final = ($current_section == $MAX_SECTIONS);
    $activity_type = null;

    if ($current_section === 1) {
        $checkStmt = $connection->prepare("SELECT started_at, updated_at FROM survey_progress WHERE user_id = ? AND year_session = ?");
        $checkStmt->execute([$user_id, $year_session]);
        $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && $row['started_at'] == $row['updated_at']) {
            $activity_type = 'survey_start';
        }
    } elseif ($is_final) {
        $activity_type = 'survey_submit';
    }

    if ($activity_type) {
        $ua_details = parse_user_agent_details($_SERVER['HTTP_USER_AGENT']);
        $browser_name = is_object($ua_details) ? $ua_details->browser : ($ua_details['browser'] ?? 'Unknown');
        
        $log_details = json_encode([
            'browser' => $browser_name, 
            'section' => $current_section, 
            'session' => $year_session
        ]);

        $logStmt = $connection->prepare("INSERT INTO ActivityLog (user_id, activity_type, details, ip_address, user_agent) VALUES (:uid, :atype, :details, :ip, :ua)");
        $logStmt->execute([
            ':uid' => $user_id, ':atype' => $activity_type, ':details' => $log_details,
            ':ip' => $_SERVER['REMOTE_ADDR'], ':ua' => $_SERVER['HTTP_USER_AGENT']
        ]);
    }

    if ($is_final) {
        $userUpdateStmt = $connection->prepare("UPDATE user SET survey_progress = 'completed' WHERE id = :uid");
        $userUpdateStmt->execute([':uid' => $user_id]);
    }

    echo json_encode([
        'success' => true, 
        'message' => "Section $current_section saved.", 
        'next_section' => $current_section + 1,
        'is_final' => $is_final
    ]);

} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>