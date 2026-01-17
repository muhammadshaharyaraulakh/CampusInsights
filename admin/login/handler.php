<?php
require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../function/function.php";
require_once __DIR__ . "/../../vendor/autoload.php";

blockDirectAccess();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json");

$response = [
    "status"  => "error",
    "message" => "Unexpected error occurred.",
    "field"   => "general"
];

try {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email)) {
        $field = "email";
        throw new Exception("Email is required.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $field = "email";
        throw new Exception("Invalid email format.");
    }
    if (empty($password)) {
        $field = "password";
        throw new Exception("Password is required.");
    }

    $stmt = $connection->prepare("
        SELECT id, username, email, password 
        FROM admins 
        WHERE email = :email LIMIT 1
    ");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$admin) {
        $field = "general";
        throw new Exception("Invalid credentials.");
    }

    if (!password_verify($password, $admin->password)) { 
        $field = "general";
        throw new Exception("Invalid credentials.");
    }

    $otp = generateOtp(16); 
    $expiresAt = time() + 300;

    $_SESSION['admin_otp_id']       = $admin->id;
    $_SESSION['admin_otp_email']    = $admin->email;
    $_SESSION['admin_otp_username'] = $admin->username;
    $_SESSION['admin_otp_code']     = $otp;
    $_SESSION['admin_otp_expires']  = $expiresAt;

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port       = SMTP_PORT;

    $mail->setFrom(SMTP_USER, 'University Admin System');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Admin Login Verification";
    $mail->Body = "
        <h2>Admin Access Verification</h2>
        <p>Your OTP code is:</p>
        <h1 style='color:#d9534f;'>$otp</h1>
    ";
    $mail->send();

    $response = [
        "status"   => "success",
        "message"  => "OTP sent to registered admin email.",
        "redirect" => "verifyCode.php" 
    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['field']   = $field ?? "general";
} catch (PDOException $e) {
    error_log("Admin Login DB Error: " . $e->getMessage());
    $response['message'] = "Database error occurred.";
}

echo json_encode($response);
exit;