<?php
header('Content-Type: application/json');

// DB connection
$conn = new mysqli("localhost", "root", "", "your_database");
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
$required = ['school_id', 'parent_name', 'full_name', 'gender', 'dob', 'cnic_formb', 'class_grade', 'section', 'email', 'phone', 'password'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        echo json_encode(["status" => "error", "message" => "$field is required"]);
        exit;
    }
}

// Sanitize inputs
$school_id = $conn->real_escape_string($data['school_id']);
$parent_name = $conn->real_escape_string($data['parent_name']);
$full_name = $conn->real_escape_string($data['full_name']);
$gender = $conn->real_escape_string($data['gender']);
$dob = $conn->real_escape_string($data['dob']);
$cnic_formb = $conn->real_escape_string($data['cnic_formb']);
$class_grade = $conn->real_escape_string($data['class_grade']);
$section = $conn->real_escape_string($data['section']);
$roll_number = isset($data['roll_number']) ? $conn->real_escape_string($data['roll_number']) : '';
$address = isset($data['address']) ? $conn->real_escape_string($data['address']) : '';
$email = $conn->real_escape_string($data['email']);
$phone = $conn->real_escape_string($data['phone']);
$password = password_hash($data['password'], PASSWORD_DEFAULT); // Encrypt password

// Check if school exists
$school_check = $conn->query("SELECT id FROM schools WHERE id = '$school_id'");
if ($school_check->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Invalid school ID"]);
    exit;
}
// Check email in students
$check1 = $conn->query("SELECT id FROM students WHERE email = '$email'");
$check2 = $conn->query("SELECT id FROM teachers WHERE email = '$email'");
$check3 = $conn->query("SELECT id FROM schools WHERE admin_email = '$email'");

if ($check1->num_rows > 0 || $check2->num_rows > 0 || $check3->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists"]);
    exit;
}

// Upload profile photo (optional)
$profile_photo = '';
if (!empty($data['profile_photo_base64'])) {
    $imgData = base64_decode($data['profile_photo_base64']);
    $fileName = 'uploads/students/' . uniqid() . '.jpg';
    file_put_contents($fileName, $imgData);
    $profile_photo = $conn->real_escape_string($fileName);
}

// Insert student
$query = "INSERT INTO students (school_id, parent_name, full_name, gender, dob, cnic_formb, class_grade, section, roll_number, address, email, phone, profile_photo, password) 
          VALUES ('$school_id', '$parent_name', '$full_name', '$gender', '$dob', '$cnic_formb', '$class_grade', '$section', '$roll_number', '$address', '$email', '$phone', '$profile_photo', '$password')";

if ($conn->query($query)) {
    echo json_encode(["status" => "success", "message" => "Student registered successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Registration failed"]);
}

$conn->close();
?>