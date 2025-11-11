<?php
header('Content-Type: application/json');
require_once '../sass/db_config.php';

$class_id = intval($_GET['class_id']);

$sql = "
    SELECT d.*, f.full_name AS teacher_name
    FROM class_timetable_details d
    LEFT JOIN faculty f ON d.teacher_id = f.id
    WHERE d.timing_meta_id = ?
    ORDER BY d.period_number ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();

$periods = [];
while ($row = $result->fetch_assoc()) {
    $periods[] = $row;
}

echo json_encode($periods, JSON_PRETTY_PRINT);
$conn->close();
?>