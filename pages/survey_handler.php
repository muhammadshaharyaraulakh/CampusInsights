<?php
header('Content-Type: application/json');
require __DIR__."/../config/config.php";


// Check DB connection
if (!isset($connection)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Configuration Error: Database connection failed.']);
    exit;
}

$MAX_SECTIONS = 5;

// 1. Authentication Check
if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Session required.']);
    exit;
}

$user_id = $_SESSION['id'];

// 2. Validate Request and Inputs
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['section_number'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request method or missing section data.']);
    exit;
}

$current_section = (int)$_POST['section_number'];
if ($current_section < 1 || $current_section > $MAX_SECTIONS) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid section number provided.']);
    exit;
}

// Convert all submitted form data into a JSON string for flexible storage
$json_data = json_encode($_POST);

try {
    // 3. Check for existing progress
    $stmt = $connection->prepare("SELECT 1 FROM survey_progress WHERE user_id = :uid AND section_number = :snum");
    $stmt->execute([':uid' => $user_id, ':snum' => $current_section]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        // 4. UPDATE existing progress 
        $sql = "UPDATE survey_progress SET section_data = :data, submitted_at = NOW() 
                WHERE user_id = :uid AND section_number = :snum";
        $stmt = $connection->prepare($sql);
        $stmt->execute([
            ':data' => $json_data, 
            ':uid' => $user_id, 
            ':snum' => $current_section
        ]);
    } else {
        // 5. INSERT new progress
        $sql = "INSERT INTO survey_progress (user_id, section_number, section_data) 
                VALUES (:uid, :snum, :data)";
        $stmt = $connection->prepare($sql);
        $stmt->execute([
            ':uid' => $user_id, 
            ':snum' => $current_section, 
            ':data' => $json_data
        ]);
    }

    // 6. Log survey activity
    // $stmtLog = $connection->prepare("
    //     INSERT INTO user_activity_log (user_id, activity_type, ip_address, user_agent, meta_data)
    //     VALUES (:uid, 'survey_submission', :ip, :ua, :meta)
    // ");
    // $stmtLog->execute([
    //     ':uid' => $user_id,
    //     ':ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    //     ':ua' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    //     ':meta' => json_encode(['section_number' => $current_section])
    // ]);

    // 7. Determine the next step
    $next_section = $current_section + 1;
    $is_final = ($current_section == $MAX_SECTIONS);

    echo json_encode([
        'success' => true,
        'message' => "Section $current_section saved successfully.",
        'next_section' => $next_section,
        'is_final' => $is_final
    ]);

} catch (\PDOException $e) {
    error_log("DB Error in survey_handler: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal server error occurred during data processing.']);
}
?>
