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
    if (!isset($_SESSION['admin_otp_id'], $_SESSION['admin_otp_email'], $_SESSION['admin_otp_code'])) {
        throw new Exception("Session expired. Please login again.");
    }

    $adminId = $_SESSION['admin_otp_id'];
    $email   = $_SESSION['admin_otp_email'];
    $code    = trim($_POST['code'] ?? '');

    if (empty($code)) {
        throw new Exception("Verification code is required.", 1);
    }

    if ($code !== $_SESSION['admin_otp_code']) {
        throw new Exception("Invalid verification code.", 1);
    }

    if (time() > $_SESSION['admin_otp_expires']) {
        throw new Exception("Code expired. Please login again.", 1);
    }

    $stmt = $connection->prepare("SELECT username, email, role FROM admins WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $adminId]);
    $admin = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$admin) {
        throw new Exception("Admin account not found.");
    }

    session_regenerate_id(true);
    $_SESSION['admin_id']       = $adminId;
    $_SESSION['admin_email']    = $admin->email;
    $_SESSION['admin_username'] = $admin->username;
    $_SESSION['role']           = 'admin';

   

    unset($_SESSION['admin_otp_id'], $_SESSION['admin_otp_email'], $_SESSION['admin_otp_code'], $_SESSION['admin_otp_expires']);

    $response = [
        "status"   => "success",
        "message"  => "Verification successful.",
        "redirect" => "/admin/admin.php"
    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['field']   = match($e->getCode()) {
        1 => "code",
        default => "general"
    };
} catch (PDOException $e) {
    error_log("Admin OTP DB Error: " . $e->getMessage());
    $response['message'] = "Database error occurred.";
}

echo json_encode($response);
exit;