<?php
header('Content-Type: application/json');
require __DIR__ . "/../../config/config.php"; 
require __DIR__ . "/../../function/function.php";

$response = ["status" => "error", "message" => "Unexpected error occurred.", "field" => ""];

try {
    // ==========================
    // ACCESS CONTROL
    // ==========================
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        header("Location:/pages/404.php");
        exit;
    }

    // ==========================
    // RECEIVE JSON BODY
    // ==========================
    $data = json_decode(file_get_contents("php://input"), true);

    $email    = trim($data['email'] ?? '');
    $code     = trim($data['code'] ?? '');
    $password = $data['password'] ?? '';
    $confirm  = $data['confirm_password'] ?? '';

    // ==========================
    // VALIDATION
    // ==========================

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $field = "email";
        throw new Exception("Invalid email format.");
    }

    if (empty($code)) {
        $field = "code";
        throw new Exception("Please enter the reset code.");
    }

    if (empty($password)) {
        $field = "password";
        throw new Exception("Please enter a new password.");
    }

    if (strlen($password) < 6 || 
        !preg_match('/[A-Z]/', $password) || 
        !preg_match('/[a-z]/', $password) || 
        !preg_match('/[0-9]/', $password)) 
    {
        $field = "password";
        throw new Exception("Password must include uppercase, lowercase, number and be at least 6 chars.");
    }

    if ($password !== $confirm) {
        $field = "confirm_password";
        throw new Exception("Passwords do not match.");
    }

    // ==========================
    // CHECK RESET CODE
    // ==========================
    $stmt = $connection->prepare("SELECT * FROM ResetPassword WHERE email = :email AND token = :token LIMIT 1");
    $stmt->execute([':email'=>$email, ':token'=>$code]);
    $reset = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$reset) {
        $field = "code";
        throw new Exception("Invalid reset code or email.");
    }

    if (strtotime($reset->expires_at) < time()) {
        $field = "code";
        throw new Exception("Reset code expired.");
    }

    // ==========================
    // GET USER
    // ==========================
    $stmtUser = $connection->prepare("SELECT id FROM user WHERE email=:email LIMIT 1");
    $stmtUser->execute([':email'=>$email]);
    $user = $stmtUser->fetch(PDO::FETCH_OBJ);

    if (!$user) {
        $field = "email";
        throw new Exception("Account not found.");
    }

    // ==========================
    // UPDATE PASSWORD
    // ==========================
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $update = $connection->prepare("UPDATE user SET password=:password WHERE email=:email");
    $update->execute([':password'=>$hash, ':email'=>$email]);

    // ==========================
    // LOG ACTIVITY
    // ==========================
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent_full = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $details = parse_user_agent_details($userAgent_full);

    $details_json = json_encode([
        'device_summary' => $details['browser'] . ' on ' . $details['os']
    ]);

    $stmtLog = $connection->prepare("
        INSERT INTO ActivityLog (user_id, activity_type, details, ip_address, user_agent)
        VALUES (:user_id, 'password_reset', :details, :ip, :ua)
    ");

    $stmtLog->execute([
        ':user_id' => $user->id,
        ':details' => $details_json,
        ':ip'      => $ip,
        ':ua'      => $userAgent_full
    ]);

    // ==========================
    // DELETE TOKEN
    // ==========================
    $connection->prepare("DELETE FROM ResetPassword WHERE email=:email")
               ->execute([':email'=>$email]);

    // ==========================
    // SUCCESS RESPONSE
    // ==========================
    echo json_encode([
        "status"   => "success",
        "message"  => "Password reset successfully! You can now log in.",
        "redirect" => "/auth/login/login.php"
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode([
        "status"  => "error",
        "message" => $e->getMessage(),
        "field"   => $field ?? ""
    ]);
    exit;
}
