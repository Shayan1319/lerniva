<?php
session_start();
require_once '../sass/db_config.php';

$school_id = $_SESSION['school_id'] ?? 0;

$sql = "SELECT id, full_name FROM faculty WHERE campus_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $school_id);
$stmt->execute();

$result = $stmt->get_result();
$teachers = [];

while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;
}

echo json_encode($teachers);