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

    $email        = trim($_POST['email'] ?? '');
    $registration = trim($_POST['registration_no'] ?? '');
    if (empty($email)) {
        $field = "email";
        throw new Exception("Email is required.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $field = "email";
        throw new Exception("Invalid email format.");
    }
    if (empty($registration)) {
        $field = "registration_no";
        throw new Exception("Registration number is required.");
    }

    $stmt = $connection->prepare("
        SELECT id, username, email 
        FROM user
        WHERE email = :email
          AND registration_no = :reg
          AND status = 'active'
        LIMIT 1
    ");
    $stmt->execute([
        ':email' => $email,
        ':reg'   => $registration
    ]);
    $student = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$student) {
        $field = "general";
        throw new Exception("Email and registration number do not match.");
    }

    $otp = generateOtp(16);
    $expiresAt = time() + 300;

    $_SESSION['otp_student_id'] = $student->id;
    $_SESSION['otp_email']      = $student->email;
    $_SESSION['otp_username']   = $student->username;
    $_SESSION['otp_code']       = $otp;
    $_SESSION['otp_expires']    = $expiresAt;

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port       = SMTP_PORT;

    $mail->setFrom(SMTP_USER, 'University Survey');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Login Verification Code";
    $mail->Body = "
        <h2>Email Verification</h2>
        <p>Your verification code is:</p>
        <h1 style='color:#d9534f;'>$otp</h1>

    ";
    $mail->send();

    $response = [
        "status"   => "success",
        "message"  => "Verification code sent to your email.",
        "redirect" => "/auth/login/verifyCode.php"
    ];
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['field']   = $field ?? "general";
} catch (PDOException $e) {
    error_log("OTP Login DB Error: " . $e->getMessage());
    $response['message'] = "Database error occurred.";
}

echo json_encode($response);
exit;
