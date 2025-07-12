<?php
session_start();
header('Content-Type: application/json');
require_once '../sass/db_config.php'; // adjust path as needed

function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Validate session
if (!isset($_SESSION['school_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

try {
    // Validate required fields
    $required = ['full_name', 'cnic', 'qualification', 'subjects', 'joining_date', 'employment_type', 'schedule_preference'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['status' => 'error', 'message' => ucfirst($field) . ' is required']);
            exit;
        }
    }

    // Assign and clean values
    $campus_id = $_SESSION['school_id'];
    $full_name = clean($_POST['full_name']);
    $cnic = clean($_POST['cnic']);
    $qualification = clean($_POST['qualification']);
    $subjects = clean($_POST['subjects']);
    $email = isset($_POST['email']) ? clean($_POST['email']) : null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $phone = isset($_POST['phone']) ? clean($_POST['phone']) : null;
    $address = isset($_POST['address']) ? clean($_POST['address']) : null;
    $joining_date = $_POST['joining_date'];
    $employment_type = $_POST['employment_type'];
    $schedule_preference = $_POST['schedule_preference'];

    // Handle photo upload
    $photo_name = null;
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "../uploads/faculty/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $photo_name = time() . '_' . basename($_FILES['photo']['name']);
        $targetPath = $targetDir . $photo_name;
        move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath);
    }

    // Check email in students
$check1 = $conn->query("SELECT id FROM students WHERE email = '$email'");
$check2 = $conn->query("SELECT id FROM teachers WHERE email = '$email'");
$check3 = $conn->query("SELECT id FROM schools WHERE admin_email = '$email'");

if ($check1->num_rows > 0 || $check2->num_rows > 0 || $check3->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists"]);
    exit;
}
    // Insert query
    $stmt = $conn->prepare("INSERT INTO faculty (
        campus_id, full_name, cnic, qualification, subjects, email, password,
        phone, address, joining_date, employment_type, schedule_preference, photo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "issssssssssss",
        $campus_id, $full_name, $cnic, $qualification, $subjects, $email, $password,
        $phone, $address, $joining_date, $employment_type, $schedule_preference, $photo_name
    );

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Faculty registered successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database insertion failed']);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>