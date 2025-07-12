<?php
header("Content-Type: application/json");
include 'db_connection.php'; // DB connection file

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email']);
$password = trim($data['password']);

if (empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Email and password are required."]);
    exit;
}

// Check in teacher table
$stmt_teacher = $conn->prepare("SELECT * FROM teachers WHERE email = ?");
$stmt_teacher->bind_param("s", $email);
$stmt_teacher->execute();
$result_teacher = $stmt_teacher->get_result();

if ($result_teacher->num_rows > 0) {
    $teacher = $result_teacher->fetch_assoc();
    if (password_verify($password, $teacher['password'])) {
        echo json_encode([
            "status" => "success",
            "user_type" => "teacher",
            "data" => [
                "id" => $teacher['id'],
                "name" => $teacher['full_name'],
                "email" => $teacher['email']
                // Add more teacher fields as needed
            ]
        ]);
        exit;
    }
}

// Check in student table
$stmt_student = $conn->prepare("SELECT * FROM students WHERE email = ?");
$stmt_student->bind_param("s", $email);
$stmt_student->execute();
$result_student = $stmt_student->get_result();

if ($result_student->num_rows > 0) {
    $student = $result_student->fetch_assoc();
    if (password_verify($password, $student['password'])) {
        echo json_encode([
            "status" => "success",
            "user_type" => "student",
            "data" => [
                "id" => $student['id'],
                "name" => $student['full_name'],
                "email" => $student['email']
                // Add more student fields as needed
            ]
        ]);
        exit;
    }
}

echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
?>