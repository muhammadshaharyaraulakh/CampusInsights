<?php
require_once __DIR__ . "/../../config/config.php";
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status"=>"error","message"=>"Invalid request"]);
    exit;
}

$id = $_POST['id'] ?? '';

if (!$id || !ctype_digit($id)) {
    echo json_encode(["status"=>"error","message"=>"Invalid student ID"]);
    exit;
}

$stmt = $connection->prepare("DELETE FROM user WHERE id = :id");
$deleted = $stmt->execute([':id' => $id]);

if ($deleted && $stmt->rowCount()) {
    echo json_encode([
        "status" => "success",
        "message" => "Student and all related data permanently deleted"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Student not found or already deleted"
    ]);
}
