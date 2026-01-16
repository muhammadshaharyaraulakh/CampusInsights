<?php
require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../function/function.php";
require_once __DIR__ . "/../../vendor/autoload.php";

blockDirectAccess();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json");

// ==========================
// DEFAULT RESPONSE
// ==========================
$response = [
    "status"  => "error",
    "message" => "Unexpected error occurred.",
    "field"   => "general"
];

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid request.");
    }

    // ==========================
    // GET INPUTS
    // ==========================
    $email        = trim($_POST['email'] ?? '');
    $registration = trim($_POST['registration_no'] ?? '');

    // ==========================
    // VALIDATIONS
    // ==========================
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

    // ==========================
    // VERIFY STUDENT
    // ==========================
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

    // ==========================
    // GENERATE OTP
    // ==========================
    $otp = generateOtp(6); // or 16 if you want longer
    $expiresAt = time() + 300; // 5 minutes from now

    // ==========================
    // STORE OTP IN SESSION
    // ==========================
    $_SESSION['otp_student_id'] = $student->id;
    $_SESSION['otp_email']      = $student->email;
    $_SESSION['otp_username']   = $student->username;
    $_SESSION['otp_code']       = $otp;
    $_SESSION['otp_expires']    = $expiresAt;

    // ==========================
    // SEND OTP EMAIL
    // ==========================
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
        <p>This code will expire in 5 minutes.</p>
    ";
    $mail->send();

    // ==========================
    // SUCCESS RESPONSE
    // ==========================
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
