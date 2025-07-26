<?php
header("Content-Type: application/json");
require '../sass/db_config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

// Helper function
function respond($success, $message, $data = null) {
    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}

if ($method === 'POST') {
    $action = $input['action'] ?? '';
    
    if ($action === 'create') {
        $school_id   = $input['school_id'] ?? '';
        $title       = $input['title'] ?? '';
        $content     = $input['content'] ?? '';
        $author_id   = $input['author_id'] ?? '';
        $author_role = 'teacher';
        $status      = $input['status'] ?? 'published';

        if (!$school_id || !$title || !$content || !$author_id) {
            respond(false, "Missing required fields");
        }

        $stmt = $conn->prepare("INSERT INTO notes_board (school_id, title, content, author_id, author_role, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ississ", $school_id, $title, $content, $author_id, $author_role, $status);
        $stmt->execute();

        respond(true, "Note created successfully");

    } elseif ($action === 'update') {
        $note_id     = $input['note_id'] ?? '';
        $title       = $input['title'] ?? '';
        $content     = $input['content'] ?? '';
        $author_id   = $input['author_id'] ?? '';

        if (!$note_id || !$title || !$content || !$author_id) {
            respond(false, "Missing required fields");
        }

        // Check if note belongs to the teacher
        $check = $conn->prepare("SELECT * FROM notes_board WHERE id = ? AND author_id = ? AND author_role = 'teacher'");
        $check->bind_param("ii", $note_id, $author_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows === 0) {
            respond(false, "Unauthorized or note not found");
        }

        $stmt = $conn->prepare("UPDATE notes_board SET title = ?, content = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $note_id);
        $stmt->execute();

        respond(true, "Note updated successfully");

    } elseif ($action === 'delete') {
        $note_id   = $input['note_id'] ?? '';
        $author_id = $input['author_id'] ?? '';

        if (!$note_id || !$author_id) {
            respond(false, "Missing note_id or author_id");
        }

        // Check if note belongs to teacher
        $check = $conn->prepare("SELECT * FROM notes_board WHERE id = ? AND author_id = ? AND author_role = 'teacher'");
        $check->bind_param("ii", $note_id, $author_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows === 0) {
            respond(false, "Unauthorized or note not found");
        }

        $stmt = $conn->prepare("DELETE FROM notes_board WHERE id = ?");
        $stmt->bind_param("i", $note_id);
        $stmt->execute();

        respond(true, "Note deleted successfully");
    }

    else {
        respond(false, "Invalid action");
    }

} elseif ($method === 'GET') {
    $author_id = $_GET['author_id'] ?? '';
    $school_id = $_GET['school_id'] ?? '';

    if (!$author_id || !$school_id) {
        respond(false, "Missing author_id or school_id");
    }

    $stmt = $conn->prepare("SELECT id, title, content, status, created_at, updated_at FROM notes_board WHERE author_id = ? AND author_role = 'teacher' AND school_id = ?");
    $stmt->bind_param("ii", $author_id, $school_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $notes = [];
    while ($row = $res->fetch_assoc()) {
        $notes[] = $row;
    }

    respond(true, "Notes fetched", $notes);
} else {
    respond(false, "Invalid request method");
}