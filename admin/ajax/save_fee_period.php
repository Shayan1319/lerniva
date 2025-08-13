<?php
session_start();
require '../sass/db_config.php';

$school_id = $_POST['school_id'] ?? 0;
$id = $_POST['id'] ?? '';
$name = $_POST['period_name'] ?? '';
$type = $_POST['period_type'] ?? '';
$start = $_POST['start_date'] ?? '';
$end = $_POST['end_date'] ?? '';

if ($id) {
    $stmt = $conn->prepare("UPDATE fee_periods SET period_name=?, period_type=?, start_date=?, end_date=? WHERE id=? AND school_id=?");
    $stmt->bind_param("ssssii", $name, $type, $start, $end, $id, $school_id);
} else {
    $stmt = $conn->prepare("INSERT INTO fee_periods (school_id, period_name, period_type, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $school_id, $name, $type, $start, $end);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Fee period saved']);
} else {
    echo json_encode(['status' => 'danger', 'message' => 'Database error']);
}