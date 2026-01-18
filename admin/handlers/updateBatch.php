<?php
require_once __DIR__ . "/../../config/config.php";

header("Content-Type: application/json");

$response = [
    "status"  => "error",
    "message" => "Unexpected error occurred.",
    "field"   => "general"
];

try {

    $batch_id        = trim($_POST['batch_id'] ?? '');
    $batch_year      = trim($_POST['batch_year'] ?? '');
    $update_semester = trim($_POST['update_semester'] ?? '');
    $status          = trim($_POST['status'] ?? '');

    if ($batch_id === '' || !ctype_digit($batch_id)) {
        $field = "batch_id";
        throw new Exception("Please select a batch.");
    }

    $stmt = $connection->prepare(
        "SELECT * FROM batches WHERE id = $batch_id LIMIT 1"
    );
    $stmt->execute();
    $batch = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$batch) {
        throw new Exception("Batch not found.");
    }

    $updates = [];

    if ($batch_year !== '' && $batch_year !== $batch['batch_year']) {

        if (!preg_match('/^\d{4}-\d{4}$/', $batch_year)) {
            $field = "batch_year";
            throw new Exception("Format must be YYYY-YYYY.");
        }

        [$start, $end] = explode('-', $batch_year);
        if (((int)$end - (int)$start) !== 4) {
            $field = "batch_year";
            throw new Exception("Batch duration must be exactly 4 years.");
        }

        $stmt = $connection->prepare(
            "SELECT id FROM batches 
             WHERE batch_year = '$batch_year' AND id != $batch_id 
             LIMIT 1"
        );
        $stmt->execute();

        if ($stmt->fetch()) {
            $field = "batch_year";
            throw new Exception("This batch year already exists.");
        }

        $updates[] = "batch_year = '$batch_year'";
    }

    if ($update_semester !== '') {

        if (!ctype_digit($update_semester) || $update_semester < 1 || $update_semester > 8) {
            $field = "update_semester";
            throw new Exception("Invalid semester selected.");
        }

        $updates[] = "current_semester = $update_semester";
    }

    if ($status !== '') {

        if (!in_array($status, ['enable', 'disable'], true)) {
            $field = "status";
            throw new Exception("Invalid status selected.");
        }

        $updates[] = "status = '$status'";
    }

    if (empty($updates)) {
        throw new Exception("No changes detected.");
    }

    $sql = "
        UPDATE batches 
        SET " . implode(', ', $updates) . "
        WHERE id = $batch_id
        LIMIT 1
    ";

    $stmt = $connection->prepare($sql);
    $stmt->execute();

    $response = [
        "status"  => "success",
        "message" => "Batch updated successfully."
    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['field']   = $field ?? "general";
}

echo json_encode($response);
exit;
