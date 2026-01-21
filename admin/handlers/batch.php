<?php
require_once __DIR__ . "/../../config/config.php";
require_once __DIR__."/../../function/function.php";
AdminAccess();
blockDirectAccess();
header("Content-Type: application/json");

$response = [
    "status"  => "error",
    "message" => "Unexpected error occurred.",
    "field"   => "general"
];

try {

    $batch_year = trim($_POST['batch_year'] ?? '');
    $sections   = $_POST['sections'] ?? [];

    if (empty($batch_year)) {
        $field = "batch_year";
        throw new Exception("Batch year is required.");
    }

    if (!preg_match('/^\d{4}-\d{4}$/', $batch_year)) {
        $field = "batch_year";
        throw new Exception("Format must be YYYY-YYYY.");
    }

    [$start, $end] = explode('-', $batch_year);

    if (((int)$end - (int)$start) !== 4) {
        $field = "batch_year";
        throw new Exception("Batch duration must be exactly 4 years.");
    }

    if (empty($sections) || !is_array($sections)) {
        $field = "sections";
        throw new Exception("Please select at least one section.");
    }

    $stmt = $connection->prepare(
        "SELECT id FROM batches WHERE batch_year = :year LIMIT 1"
    );
    $stmt->execute([':year' => $batch_year]);

    if ($stmt->fetch()) {
        $field = "batch_year";
        throw new Exception("This batch already exists.");
    }

    $stmt = $connection->prepare(
        "INSERT INTO batches (batch_year, current_semester)
         VALUES (:year, 1)"
    );
    $stmt->execute([':year' => $batch_year]);

    $batchId = $connection->lastInsertId();

    $stmtSec = $connection->prepare(
        "INSERT INTO batch_sections (batch_id, section_name)
         VALUES (:bid, :section)"
    );

    foreach ($sections as $sec) {
        $stmtSec->execute([
            ':bid'     => $batchId,
            ':section' => $sec
        ]);
    }

    $response = [
        "status"  => "success",
        "message" => "Batch created successfully."
    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['field']   = $field ?? "general";
}

echo json_encode($response);
exit;
