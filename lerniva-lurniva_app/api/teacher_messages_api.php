<?php
require_once '../admin/sass/db_config.php';
header('Content-Type: application/json; charset=UTF-8');

// ============================
// 1. Decode JSON Input (RAW)
// ============================
$input = json_decode(file_get_contents("php://input"), true);

$teacher_id = intval($input['teacher_id'] ?? 0);
$sender_id = intval($input['sender_id'] ?? 0);
$sender_designation = strtolower(trim($input['sender_designation'] ?? ''));

if (!$teacher_id || !$sender_id || empty($sender_designation)) {
    echo json_encode(["status" => "error", "message" => "Missing required parameters"]);
    exit;
}

// ============================
// 2. Fetch Messages
// ============================
$sql = "
SELECT 
    m.*,
    COALESCE(
        IF(LOWER(m.sender_designation) = 'student', s.profile_photo, NULL),
        IF(LOWER(m.sender_designation) IN ('faculty', 'teacher'), f.photo, NULL),
        IF(LOWER(m.sender_designation) = 'admin', sch.logo, NULL)
    ) AS sender_image
FROM messages m
LEFT JOIN students s 
    ON LOWER(m.sender_designation) = 'student' AND m.sender_id = s.id
LEFT JOIN faculty f 
    ON LOWER(m.sender_designation) IN ('faculty', 'teacher') AND m.sender_id = f.id
LEFT JOIN schools sch 
    ON LOWER(m.sender_designation) = 'admin' AND m.sender_id = sch.id
WHERE 
(
    (m.sender_id = ? AND m.sender_designation = ? AND m.receiver_id = ? AND m.receiver_designation = 'teacher')
    OR
    (m.receiver_id = ? AND m.receiver_designation = ? AND m.sender_id = ? AND m.sender_designation = 'teacher')
)
ORDER BY m.sent_at ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isiisi", $sender_id, $sender_designation, $teacher_id, $sender_id, $sender_designation, $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

// ============================
// 3. Process Messages
// ============================
$messages = [];
while ($row = $result->fetch_assoc()) {

    $baseUrl = rtrim((isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'], '/') . '/Faculty Dashboard/';

    // Determine correct folder for sender image
    if ($row['sender_designation'] === 'admin') {
        $imagePath = $row['sender_image'] ? $baseUrl . "admin/uploads/logos/" . $row['sender_image'] : $baseUrl . "assets/img/default-avatar.png";
    } elseif (in_array($row['sender_designation'], ['faculty', 'teacher'])) {
        $imagePath = $row['sender_image'] ? $baseUrl . "Faculty Dashboard/uploads/profile/" . $row['sender_image'] : $baseUrl . "assets/img/default-avatar.png";
    } elseif ($row['sender_designation'] === 'student') {
        $imagePath = $row['sender_image'] ? $baseUrl . "student/uploads/profile/" . $row['sender_image'] : $baseUrl . "assets/img/default-avatar.png";
    } else {
        $imagePath = $baseUrl . "assets/img/default-avatar.png";
    }

    // File and voice paths
    $attachmentPath = !empty($row['file_attachment']) ? $baseUrl . "chat_files/" . $row['file_attachment'] : null;
    $voicePath = !empty($row['voice_note']) ? $baseUrl . "voice_notes/" . $row['voice_note'] : null;

    // Build message array
    $messages[] = [
        "id" => (int)$row['id'],
        "sender_id" => (int)$row['sender_id'],
        "sender_designation" => $row['sender_designation'],
        "receiver_id" => (int)$row['receiver_id'],
        "receiver_designation" => $row['receiver_designation'],
        "message" => $row['message'],
        "attachment" => $attachmentPath,
        "voice_note" => $voicePath,
        "sender_image" => $imagePath,
        "sent_at" => date('d M, h:i A', strtotime($row['sent_at'])),
        "status" => $row['status']
    ];
}

// ============================
// 4. Return JSON Response
// ============================
echo json_encode([
    "status" => "success",
    "count" => count($messages),
    "data" => $messages
]);
?>