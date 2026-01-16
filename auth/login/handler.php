<?php
// ==========================
// REQUEST METHOD VALIDATION
// ==========================
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /pages/404.php");
    exit;
}
require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ ."/../../function/function.php";
header("Content-Type: application/json");
// ==========================
// DEFAULT RESPONSE
// ==========================
$response = [
    "status" => "error",
    "message" => "Unexpected error occurred.",
    "field" => "general"
];

try {
    // ==========================
    // GET & TRIM INPUTS
    // ==========================
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    // ==========================
    // VALIDATE EMAIL
    // ==========================
    if (empty($email)) {
        $field = "email";
        throw new Exception("Please enter your email.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $field = "email";
        throw new Exception("Invalid email format.");
    }

    // ==========================
    // VALIDATE PASSWORD
    // ==========================
    if (empty($password)) {
        $field = "password";
        throw new Exception("Please enter your password.");
    }

    // ==========================
    // FETCH USER FROM DATABASE
    // ==========================
    $stmt = $connection->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$user) {
        $field = "email";
        throw new Exception("No account found with that email.");
    }

    // ==========================
    // VERIFY PASSWORD
    // ==========================
    if (!password_verify($password, $user->password)) {
        $field = "password";
        throw new Exception("Incorrect password.");
    }

    // ==========================
    // LOGIN SUCCESS: SET SESSION
    // ==========================
    session_regenerate_id(true);
    $_SESSION['id'] = $user->id;
    $_SESSION['username'] = $user->username;
    $_SESSION['email'] = $user->email;

    // ==========================
    // LOG LOGIN ACTIVITY
    // ==========================
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent_full = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $userAgent_details = parse_user_agent_details($userAgent_full);
    $details_json = json_encode([
        'device_summary' => $userAgent_details['browser'] . ' on ' . $userAgent_details['os']
    ]);
    
    $stmtLog = $connection->prepare("
        INSERT INTO ActivityLog (user_id, activity_type, details, ip_address, user_agent) 
        VALUES (:user_id, 'login', :details, :ip_address, :ua_full)
    ");
    
    $stmtLog->execute([
        ':user_id' => $user->id,
        ':details' => $details_json, 
        ':ip_address'  => $ip,
        ':ua_full'  => $userAgent_full 
    ]);

    // ==========================
    // SUCCESS RESPONSE
    // ==========================
    $response = [
        "status" => "success",
        "message" => "Login successful! Redirecting",
        "redirect" => "/index.php"
    ];
} catch (Exception $e) {
    // ==========================
    // HANDLE GENERAL EXCEPTIONS
    // ==========================
    $response["message"] = $e->getMessage();
    $response["field"] = $field ?? "general";
} catch (PDOException $e) {
    // ==========================
    // HANDLE DATABASE ERRORS
    // ==========================
    $response["message"] = "Database error occurred: " . $e->getMessage();
    $response["field"] = "general";
}
echo json_encode($response);
exit;
