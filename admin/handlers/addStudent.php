<?php
require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../function/function.php";
AdminAccess();
blockDirectAccess();

header("Content-Type: application/json");

$response = [
    "status"  => "error",
    "message" => "Unexpected error occurred.",
    "field"   => "general"
];

try {
    $batch_id  = $_POST['batch_id'] ?? '';
    $section   = $_POST['section'] ?? '';
    $semester  = $_POST['semester'] ?? '';
    $name      = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $reg_no    = trim($_POST['reg_no'] ?? '');


    if (!$batch_id || !ctype_digit($batch_id)) {
        $field = "batch_id";
        throw new Exception("Please select a valid batch.");
    }

    if (!preg_match('/^(M|E)[1-3]$/', $section)) {
        $field = "section";
        throw new Exception("Please select a valid section.");
    }

    if (!$semester || !ctype_digit($semester) || $semester < 1 || $semester > 8) {
        $field = "semester";
        throw new Exception("Please select a valid semester.");
    }

    if (strlen($name) < 8) {
        $field = "full_name";
        throw new Exception("Full name must be at least 8 characters.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $field = "email";
        throw new Exception("Invalid email address.");
    }


    if (!preg_match('/^[A-Z]{2,5}-(M|E)[1-3]-\d{2}-\d{2}$/', $reg_no)) {
        $field = "reg_no";
        throw new Exception("Invalid registration number format.");
    } elseif (!str_contains($reg_no, $section)) {
        $field = "reg_no";
        throw new Exception("Registration number does not match selected section.");
    }

    $stmt = $connection->prepare("SELECT current_semester FROM batches WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $batch_id]);
    $batch = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$batch) {
        $field = "batch_id";
        throw new Exception("Selected batch not found.");
    }

    if ((int)$semester !== (int)$batch->current_semester) {
        $field = "semester";
        throw new Exception("Semester does not match the batch's current semester.");
    }

    $secStmt = $connection->prepare("SELECT id FROM batch_sections WHERE batch_id = :bid AND section_name = :sec LIMIT 1");
    $secStmt->execute([':bid' => $batch_id, ':sec' => $section]);
    $sectionRow = $secStmt->fetch(PDO::FETCH_OBJ);

    $final_section_id = null;

    if ($sectionRow) {
        $final_section_id = $sectionRow->id;
    } else {
        $createSec = $connection->prepare("INSERT INTO batch_sections (batch_id, section_name) VALUES (:bid, :sec)");
        $createSec->execute([':bid' => $batch_id, ':sec' => $section]);
        $final_section_id = $connection->lastInsertId();
    }


    $stmt = $connection->prepare("
        SELECT id FROM user WHERE email = :email OR registration_no = :reg_no LIMIT 1
    ");
    $stmt->execute([
        ':email' => $email,
        ':reg_no' => $reg_no
    ]);

    if ($stmt->fetch()) {
        $field = "reg_no";
        throw new Exception("Email or registration number already exists.");
    }


    $stmt = $connection->prepare("
        INSERT INTO user (username, email, registration_no, batch_section_id) 
        VALUES (:name, :email, :reg_no, :final_sec_id)
    ");
    $stmt->execute([
        ':name'         => $name,
        ':email'        => $email,
        ':reg_no'       => $reg_no,
        ':final_sec_id' => $final_section_id
    ]);

    $response = [
        "status"  => "success",
        "message" => "Student added successfully to Batch Section."
    ];
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['field']   = $field ?? "general";
} catch (PDOException $e) {
    error_log("Add Student DB Error: " . $e->getMessage());
    $response['message'] = "Database error occurred.";
}

echo json_encode($response);
exit;
