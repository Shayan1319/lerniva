<?php
require_once '../admin/sass/db_config.php';
header('Content-Type: application/json; charset=UTF-8');

// ============================
// 1. Get POST Data
// ============================
$receiver_id = intval($_POST['teacher_id'] ?? 0);   // teacher ID (receiver)
$sender_id = intval($_POST['sender_id'] ?? 0);      // sender ID
$sender_designation = strtolower(trim($_POST['sender_designation'] ?? ''));

if ($receiver_id <= 0 || $sender_id <= 0 || empty($sender_designation)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
    exit;
}

// ============================
// 2. Update Messages to 'read'
// ============================
$sql = "
    UPDATE messages 
    SET status = 'read' 
    WHERE sender_id = ? 
      AND sender_designation = ? 
      AND receiver_id = ? 
      AND receiver_designation = 'teacher' 
      AND status = 'unread'
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $sender_id, $sender_designation, $receiver_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Messages marked as read']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>