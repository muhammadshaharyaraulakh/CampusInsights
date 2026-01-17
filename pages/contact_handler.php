<?php

header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../functions/functions.php';

blockDirectAccess() ;

$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$userName  = $_SESSION['name'] ?? $_SESSION['username'] ?? 'Unknown User';
$userEmail = $_SESSION['email'] ?? '';

if (empty($userEmail)) {
    echo json_encode(['success' => false, 'message' => 'User email not found in session.']);
    exit;
}
if (empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Subject and Message are required.']);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port       = SMTP_PORT;

    $mail->setFrom(SMTP_USER, 'Contact Form System'); 
    $mail->addAddress(SMTP_USER);                     
    $mail->addReplyTo($userEmail, $userName);         

    $mail->isHTML(true);
    $mail->Subject = "Contact Form: " . $subject;
    $mail->Body    = "
        <h3>New Contact Message</h3>
        <p><b>From:</b> " . htmlspecialchars($userName) . "</p>
        <p><b>Email:</b> " . htmlspecialchars($userEmail) . "</p>
        <hr>
        <p><b>Message:</b></p>
        <p>" . nl2br(htmlspecialchars($message)) . "</p>
    ";

    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);

} catch (Exception $e) {
    error_log("Mailer Error: {$mail->ErrorInfo}");
    echo json_encode(['success' => false, 'message' => 'Message could not be sent. Please try again later.']);
}
?>