<?php
require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../function/function.php";
blockDirectAccess();
header("Content-Type: application/json");

$response = [
    "status" => "error",
    "message" => "Unexpected error occurred.",
    "field" => "general"
];

try {
    
    if (!isset($_SESSION['otp_student_id'], $_SESSION['otp_email'], $_SESSION['otp_code'])) {
        throw new Exception("Session expired. Please login again.");
    }

    $studentId = $_SESSION['otp_student_id'];
    $email     = $_SESSION['otp_email'];
    $code      = trim($_POST['code'] ?? '');

    if (empty($code)) {
        throw new Exception("Verification code is required.", 1);
    }

    if ($code !== $_SESSION['otp_code']) {
        throw new Exception("Invalid verification code.", 1);
    }

    $stmt = $connection->prepare("SELECT username, email FROM user WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $studentId]);
    $student = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$student) {
        throw new Exception("User not found.");
    }

    session_regenerate_id(true);
    $_SESSION['id']       = $studentId;
    $_SESSION['email']    = $student->email;
    $_SESSION['username'] = $student->username;

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgentFull = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $uaDetails = parse_user_agent_details($userAgentFull);

    $stmt = $connection->prepare("
        INSERT INTO ActivityLog (user_id, activity_type, details, ip_address, user_agent)
        VALUES (:uid, 'login', :details, :ip, :ua)
    ");
    $stmt->execute([
        ':uid' => $studentId,
        ':details' => json_encode([
            'device_summary' => $uaDetails['browser'] . ' on ' . $uaDetails['os']
        ]),
        ':ip' => $ip,
        ':ua' => $userAgentFull
    ]);

    unset($_SESSION['otp_student_id'], $_SESSION['otp_email'], $_SESSION['otp_code']);

    $response = [
        "status"   => "success",
        "message"  => "Verification successful.",
        "redirect" => "/index.php"
    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['field']   = match($e->getCode()) {
        1 => "code",
        default => "general"
    };
} catch (PDOException $e) {
    error_log("OTP Verification DB Error: " . $e->getMessage());
    $response['message'] = "Database error occurred.";
}

echo json_encode($response);
exit;
