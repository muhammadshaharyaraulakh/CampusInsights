<?php
require_once __DIR__ . "/../../config/config.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /pages/404.php");
    exit;
}
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    header("Location: /index.php");
    exit;
}

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../function/function.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json");
$response = ["status" => "error", "message" => "Unexpected error occurred.", "field" => ""];

try {
    // ==========================
    // GET USER INPUT
    // ==========================
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $terms    = $_POST['terms'] ?? '';

    // ==========================
    // VALIDATIONS
    // ==========================
    if (empty($username)) {
        $field = "username";
        throw new Exception("Please enter a username.");
    }
    if (!preg_match('/^[a-zA-Z0-9_]{6,20}$/', $username)) {
        $field = "username";
        throw new Exception("Username must be 6-20 length containing letters/numbers/underscore.");
    }
    if (empty($email)) {
        $field = "email";
        throw new Exception("Please enter your email.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $field = "email";
        throw new Exception("Invalid email format.");
    }
    if (empty($password)) {
        $field = "password";
        throw new Exception("Please enter a password.");
    }
    if (strlen($password) < 6 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $field = "password";
        throw new Exception("Password must include uppercase, lowercase, number and be at least 6 chars.");
    }
    if ($password !== $confirm) {
        $field = "confirm_password";
        throw new Exception("Passwords do not match.");
    }
    if (!$terms) {
        $field = "terms";
        throw new Exception("You must agree to the Terms and Privacy Policy.");
    }

    // ==========================
    //  CHECK GMAIL OR USERNAME EXISTENCE
    // ==========================
    $stmt = $connection->prepare("SELECT * FROM user WHERE username = :user OR email = :email LIMIT 1");
    $stmt->execute([":user" => $username, ":email" => $email]);
    if ($stmt->fetch(PDO::FETCH_OBJ)) {
        $field = "username";
        throw new Exception("Username or email already exists.");
    }

    // ==========================
    //  GENERATE OTP
    // ==========================

    $otp = generateOtp(16);

    // ==========================
    //  SAVE SIGNUP DATA IN SESSION
    // ==========================
    $_SESSION['pending_signup'] = [
        "username"   => $username,
        "email"      => $email,
        "password"   => password_hash($password, PASSWORD_DEFAULT),
        "otp"        => $otp,
        "otp_expire" => time() + 300
    ];

    // ==========================
    //  SEND OTP EMAIL
    // ==========================
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USER, 'Mail');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Your  Verification Code";
        $mail->Body = "
            <h2>Email Verification</h2>
            <p>Your verification code is:</p>
            <h3 style='color:red'>$otp</h3>
            <p>This code will expire in <b>5 minutes</b>.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        throw new Exception("Failed to send email. Please try again.");
    }

    // ==========================
    // SUCCESS
    // ==========================
    echo json_encode([
        "status"   => "success",
        "message"  => "A verification code has been sent to your email.",
        "redirect" => "/auth/signup/verifyCode.php"
    ]);
    exit;
} catch (Exception $e) {
    $response["message"] = $e->getMessage();
    $response["field"]   = $field ?? "";
    echo json_encode($response);
    exit;
};
