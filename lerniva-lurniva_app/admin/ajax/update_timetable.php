<?php
session_start();
require_once '../sass/db_config.php';

header('Content-Type: application/json');

$user_id = $_SESSION['admin_id'] ?? 0;

if (!isset($_POST['periods']) || !isset($_POST['class_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}

$class_id = intval($_POST['class_id']);
$periods = json_decode($_POST['periods'], true);

if (empty($periods)) {
    echo json_encode(['status' => 'error', 'message' => 'No periods data provided.']);
    exit;
}

// Allowed ENUM values
$allowed_types = ['Normal', 'Lab', 'Break', 'Sports', 'Library'];

$conn->begin_transaction();

try {
    foreach ($periods as $p) {
        $period_name = $conn->real_escape_string(trim($p['period_name'] ?? ''));
        $start_time = $conn->real_escape_string($p['start_time'] ?? '');
        $end_time = $conn->real_escape_string($p['end_time'] ?? '');
        $teacher_id = intval($p['teacher_id'] ?? 0);
        $is_break = intval($p['is_break'] ?? 0);

        // Validate period_type
        $period_type = $p['period_type'] ?? 'Normal';
        if (!in_array($period_type, $allowed_types)) {
            $period_type = 'Normal';
        }
        $period_type = $conn->real_escape_string($period_type);

        if ($p['id'] === 'new') {
                $period_number = intval($p['period_number'] ?? 0); // if you send this from frontend
                if ($period_number === 0) {
                    // fallback: find next number automatically
                    $res = $conn->query("SELECT IFNULL(MAX(period_number), 0) + 1 AS next_num FROM class_timetable_details WHERE timing_meta_id = $class_id");
                    $row = $res->fetch_assoc();
                    $period_number = $row['next_num'] ?? 1;
                }

                $stmt = $conn->prepare("INSERT INTO class_timetable_details 
                    (timing_meta_id, period_name, start_time, end_time, teacher_id, period_type, is_break, period_number, created_by, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("isssisiii", $class_id, $period_name, $start_time, $end_time, $teacher_id, $period_type, $is_break, $period_number, $user_id);
                $stmt->execute();
                $stmt->close();
            }else {
            // UPDATE existing period
            $period_id = intval($p['id']);
            $stmt = $conn->prepare("UPDATE class_timetable_details 
                SET period_name = ?, start_time = ?, end_time = ?, teacher_id = ?, period_type = ?, is_break = ?, created_by = ?, created_at = NOW()
                WHERE id = ? AND timing_meta_id = ?");
            $stmt->bind_param("sssisiiii", $period_name, $start_time, $end_time, $teacher_id, $period_type, $is_break, $user_id, $period_id, $class_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Timetable updated successfully.']);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>