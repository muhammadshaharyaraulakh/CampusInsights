<?php
require __DIR__ . "/../../config/config.php";
header("Content-Type: application/json");

$response = ["status" => "error", "message" => "Unexpected error.", "field" => ""];

try {
    // ==========================
    // ENSURE REQUEST METHOD IS POST
    // ==========================
    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        header("Location:/pages/404.php");
        exit;
    }

    // ==========================
    // CHECK IF THERE IS A PENDING SIGNUP
    // ==========================
    if (!isset($_SESSION['pending_signup'])) {
        header("Location:/pages/404.php");
        exit;
    }

    // ==========================
    // VALIDATE OTP INPUT
    // ==========================
    $inputCode = trim($_POST['code'] ?? '');
    if (empty($inputCode)) {
        $response["field"] = "code";
        throw new Exception("Please enter the verification code.");
    }

    $pending = $_SESSION['pending_signup'];

    // ==========================
    // CHECK IF OTP HAS EXPIRED
    // ==========================
    if (time() > $pending['otp_expire']) {
        unset($_SESSION['pending_signup']);
        throw new Exception("Verification code has expired. Please sign up again.");
    }

    // ==========================
    // CHECK IF OTP MATCHES
    // ==========================
    if ($inputCode != $pending['otp']) {
        $response["field"] = "code";
        throw new Exception("Invalid verification code.");
    }

    // ----------------------------------
    // INSERT THE USER SAFELY
    // ----------------------------------
    try {
        $stmt = $connection->prepare("
            INSERT INTO user (username, email, password)
            VALUES (:username, :email, :password)
        ");
        $stmt->execute([
            ":username" => $pending['username'],
            ":email" => $pending['email'],
            ":password" => $pending['password']
        ]);
    } catch (PDOException $ex) {
        throw new Exception("A database error occurred. Please try again.");
    }

    // ==========================
    // CLEANUP AND SUCCESS RESPONSE
    // ==========================
    unset($_SESSION['pending_signup']);

    echo json_encode([
        "status" => "success",
        "message" => "Your account has been verified successfully!",
        "redirect"=>"/auth/login/login.php"
    ]);
    exit;
} catch (Exception $e) {
    // ==========================
    // RETURN ERROR RESPONSE
    // ==========================
    $response["message"] = $e->getMessage();
    echo json_encode($response);
    exit;
}
