<?php
session_start();
header('Content-Type: application/json');
require_once '../sass/db_config.php'; // should define $conn
echo"data";


// function clean($data) {
//     return htmlspecialchars(strip_tags(trim($data)));
// }

// // Use admin_id from session
// if (!isset($_SESSION['admin_id'])) {
//     echo  'Unauthorized access';
//     exit;
// }

// $campus_id = $_SESSION['admin_id'];

// // Validate required fields
// $required_fields = ['full_name', 'cnic', 'qualification', 'subjects', 'joining_date', 'employment_type', 'schedule_preference'];
// foreach ($required_fields as $field) {
//     if (empty($_POST[$field])) {
//         echo  '_', ' ', $field . ' is required';
//         exit;
//     }
// }

// // Sanitize and assign fields
// $full_name = clean($_POST['full_name']);
// $cnic = clean($_POST['cnic']);
// $qualification = clean($_POST['qualification']);
// $subjects = clean($_POST['subjects']);
// $email = isset($_POST['email']) ? clean($_POST['email']) : null;
// $password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
// $phone = isset($_POST['phone']) ? clean($_POST['phone']) : null;
// $address = isset($_POST['address']) ? clean($_POST['address']) : null;
// $joining_date = clean($_POST['joining_date']);
// $employment_type = clean($_POST['employment_type']);
// $schedule_preference = clean($_POST['schedule_preference']);

// // Handle photo
// $photo_name = null;
// if (!empty($_FILES['photo']['name'])) {
//     $targetDir = "../uploads/faculty/";
//     if (!is_dir($targetDir)) {
//         mkdir($targetDir, 0777, true);
//     }

//     $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
//     $allowed = ['jpg', 'jpeg', 'png', 'gif'];
//     if (!in_array($ext, $allowed)) {
//         echo  'Invalid photo format';
//         exit;
//     }

//     $photo_name = time() . '_' . basename($_FILES['photo']['name']);
//     $targetPath = $targetDir . $photo_name;

//     if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
//         echo  'Photo upload failed';
//         exit;
//     }
// }

// // Check if email exists in other tables
// if ($email) {
//     $email = $conn->real_escape_string($email);
//     $check_sql = "
//         SELECT id FROM students WHERE email = '$email'
//         UNION
//         SELECT id FROM teachers WHERE email = '$email'
//         UNION
//         SELECT id FROM schools WHERE admin_email = '$email'
//     ";
//     $check_result = mysqli_query($conn, $check_sql);
//     if ($check_result && mysqli_num_rows($check_result) > 0) {
//         echo  'Email already exists';
//         exit;
//     }
// }

// // Insert into faculty table
// $stmt = $conn->prepare("INSERT INTO faculty (
//     campus_id, full_name, cnic, qualification, subjects, email, password,
//     phone, address, joining_date, employment_type, schedule_preference, photo
// ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// $stmt->bind_param(
//     "issssssssssss",
//     $campus_id, $full_name, $cnic, $qualification, $subjects,
//     $email, $password, $phone, $address,
//     $joining_date, $employment_type, $schedule_preference, $photo_name
// );

// if ($stmt->execute()) {
//     echo 'Faculty registered successfully';
// } else {
//     echo 'Database insertion failed';
// }

// $stmt->close();
// $conn->close();
?>