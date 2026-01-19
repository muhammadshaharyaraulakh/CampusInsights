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

$json_data = json_encode($_POST);
$column_name = "section_" . $current_section;

try {
    // 1. Insert/Update Survey Data
    $sql = "INSERT INTO survey_progress (user_id, $column_name) 
            VALUES (:uid, :data) 
            ON DUPLICATE KEY UPDATE $column_name = :data_update";
            
    $stmt = $connection->prepare($sql);
    $stmt->execute([
        ':uid' => $user_id,
        ':data' => $json_data,
        ':data_update' => $json_data
    ]);

    // 2. Activity Log Logic
    $is_final = ($current_section == $MAX_SECTIONS);
    $activity_type = null;

    if ($current_section === 1) {
        $checkStmt = $connection->prepare("SELECT started_at, updated_at FROM survey_progress WHERE user_id = ?");
        $checkStmt->execute([$user_id]);
        
        // === FIX IS HERE: Force FETCH_ASSOC to get an array ===
        $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && $row['started_at'] == $row['updated_at']) {
            $activity_type = 'survey_start';
        }
    } elseif ($is_final) {
        $activity_type = 'survey_submit';
    }

    if ($activity_type) {
        $ua_details = parse_user_agent_details($_SERVER['HTTP_USER_AGENT']);
        
        // Safety check: Ensure ua_details is handled correctly (Object vs Array)
        $browser_name = is_object($ua_details) ? $ua_details->browser : ($ua_details['browser'] ?? 'Unknown');
        
        $log_details = json_encode(['browser' => $browser_name, 'section' => $current_section]);

        $logStmt = $connection->prepare("INSERT INTO ActivityLog (user_id, activity_type, details, ip_address, user_agent) VALUES (:uid, :atype, :details, :ip, :ua)");
        $logStmt->execute([
            ':uid' => $user_id, ':atype' => $activity_type, ':details' => $log_details,
            ':ip' => $_SERVER['REMOTE_ADDR'], ':ua' => $_SERVER['HTTP_USER_AGENT']
        ]);
    }

    // 3. Final Submission Status Update
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