<?php
require_once __DIR__ . "/../../config/config.php";
require_once __DIR__."/../../function/function.php";
AdminAccess();
$section_id = $_GET['section_id'] ?? '';

if (!$section_id || !ctype_digit($section_id)) {
    exit('Invalid request');
}

$stmt = $connection->prepare("
    SELECT 
        u.username,
        u.registration_no,
        bs.section_name,
        b.batch_year
    FROM user u
    JOIN batch_sections bs ON u.batch_section_id = bs.id
    JOIN batches b ON bs.batch_id = b.id
    WHERE u.batch_section_id = :section_id
      AND u.survey_progress = 'pending'
");
$stmt->execute([':section_id' => $section_id]);
$students = $stmt->fetchAll(PDO::FETCH_OBJ);

if (!$students) {
    exit('No pending students found');
}

$startYear = (int)$students[0]->batch_year;
$endYear   = $startYear + 4;

$session = "{$startYear}-{$endYear}";

$filename = "pending_students_session_{$session}_section_{$students[0]->section_name}.csv";


header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename=\"$filename\"");

$output = fopen('php://output', 'w');

/*
 SAMPLE FORMAT
 Section: A | Batch: 2023
 Name,Roll Number
 Ali,FA21-BCS-001
 ...
*/

// first row (info)
fputcsv($output, [
    "Section: {$students[0]->section_name}",
    "Batch: {$students[0]->batch_year}"
]);

// empty line
fputcsv($output, []);


fputcsv($output, ['Name', 'Roll Number']);

foreach ($students as $s) {
    fputcsv($output, [$s->username, $s->registration_no]);
}

fclose($output);
exit;
