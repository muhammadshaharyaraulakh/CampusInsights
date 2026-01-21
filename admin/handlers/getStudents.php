<?php
require_once __DIR__ . "/../../config/config.php";
require_once __DIR__."/../../function/function.php";
AdminAccess();
header("Content-Type: application/json");

$section_id = $_GET['section_id'] ?? '';

if (!$section_id || !ctype_digit($section_id)) {
    echo json_encode(["status"=>"error","message"=>"Invalid request"]);
    exit;
}

$stmt = $connection->prepare("
    SELECT u.id, u.username, u.registration_no, u.survey_progress, u.status, bs.section_name, b.batch_year
    FROM user u
    JOIN batch_sections bs ON u.batch_section_id = bs.id
    JOIN batches b ON bs.batch_id = b.id
    WHERE u.batch_section_id = :section_id
");
$stmt->execute([':section_id' => $section_id]);
$students = $stmt->fetchAll(PDO::FETCH_OBJ);

if (!$students) {
    echo json_encode(["status"=>"error","message"=>"No students found for this section"]);
    exit;
}

$total = count($students);
$pending = count(array_filter($students, fn($s) => $s->survey_progress === 'pending'));

echo json_encode([
    "status" => "success",
    "section_name" => $students[0]->section_name ?? '',
    "batch_year" => $students[0]->batch_year ?? '',
    "students" => array_map(fn($s) => [
        "id" => $s->id,
        "username" => $s->username,
        "registration_no" => $s->registration_no,
        "status" => ucfirst($s->survey_progress)
    ], $students),
    "total" => $total,
    "pending" => $pending
]);
