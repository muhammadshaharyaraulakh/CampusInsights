<?php
header('Content-Type: application/json');

require __DIR__ . "/../../config/config.php"; 
require __DIR__ . "/../../vendor/autoload.php";
require __DIR__ . "/../../function/function.php"; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$response = [
    'status' => 'error',
    'field' => 'general',
    'message' => 'Unexpected error occurred.'
];

try {
    // ==========================
    // ACCESS CONTROL
    // ==========================
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $response['message'] = "You must log out to request a password reset.";
        echo json_encode($response);
        exit;
    }
    
    // ==========================
    // 1. INPUT HANDLING 
    // ==========================
    $data = json_decode(file_get_contents("php://input"), true);
    $email = trim($data['email'] ?? '');

    // ==========================
    // 2. VALIDATION
    // ==========================
    if (empty($email)) {
        $response['field'] = 'email';
        throw new Exception("Please enter your email.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['field'] = 'email';
        throw new Exception("Invalid email format.");
    }

    // ==========================
    // 3. CHECK USER EXISTENCE
    // ==========================
    $stmt = $connection->prepare("SELECT id FROM user WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$user) {
        $response['field'] = 'email';
        throw new Exception("No account found with that email.");
    }

    // ==========================
    // 4. GENERATE AND STORE CODE
    // ==========================
    $code = generateOtp(16); 
    $expires_at = date("Y-m-d H:i:s", time() + (10 * 60)); 

    $connection->prepare("DELETE FROM ResetPassword WHERE email = :email")->execute([':email'=>$email]);


    $stmt=$connection->prepare("
        INSERT INTO ResetPassword (email, token, expires_at)
        VALUES (:email, :token, :expires_at)
    ");
    $stmt->execute([
        ':email' => $email,
        ':token' => $code, 
        ':expires_at' => $expires_at
    ]);

    // ==========================
    // 5. SEND EMAIL
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
        $mail->Subject = 'Password Reset Code';
        $mail->Body = "<p>Your password reset code is: <b>$code</b></p>
                       <p>It will expire in 10 minutes.</p>";

        $mail->send();
    } catch (Exception $e) {
        $connection->prepare("DELETE FROM ResetPassword WHERE email = :email")->execute([':email'=>$email]);
        throw new Exception("Failed to send the reset email. Please try again.");
    }

    // ==========================
    // 6. SUCCESS
    // ==========================
    $response = [
        'status' => 'success',
        'message' => 'Reset code sent to your email.'
    ];

} catch (Exception $e) {
    if (!isset($response['field']) || $response['field'] == 'general') {
         $response['field'] = 'general';
    }
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;