<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = [];

require_once '../sass/db_config.php'; // Your DB connection

// Sanitize input
function clean_input($data) {
  return htmlspecialchars(strip_tags(trim($data)));
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Use POST.']);
  exit;
}

// Validate required fields
$required_fields = ['school_name', 'school_type', 'registration_number', 'affiliation_board', 'school_email', 'school_phone', 'country', 'state', 'city', 'address', 'admin_contact_person', 'admin_email', 'admin_phone', 'password'];

foreach ($required_fields as $field) {
  if (!isset($_POST[$field]) || empty($_POST[$field])) {
    echo json_encode(['status' => 'error', 'message' => "Field '$field' is required."]);
    exit;
  }
}

// Extract and sanitize form data
$school_name = clean_input($_POST['school_name']);
$school_type = clean_input($_POST['school_type']);
$registration_number = clean_input($_POST['registration_number']);
$affiliation_board = clean_input($_POST['affiliation_board']);
$school_email = clean_input($_POST['school_email']);
$school_phone = clean_input($_POST['school_phone']);
$school_website = isset($_POST['school_website']) ? clean_input($_POST['school_website']) : '';
$country = clean_input($_POST['country']);
$state = clean_input($_POST['state']);
$city = clean_input($_POST['city']);
$address = clean_input($_POST['address']);
$admin_contact_person = clean_input($_POST['admin_contact_person']);
$admin_email = clean_input($_POST['admin_email']);
$admin_phone = clean_input($_POST['admin_phone']);
$password = password_hash(clean_input($_POST['password']), PASSWORD_DEFAULT); // Hashed password

// Handle file upload
$logo_name = '';
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
  $target_dir = __DIR__ . '/../uploads/logos/';
  if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
  }
  $file_ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
  $logo_name = uniqid('logo_', true) . '.' . $file_ext;
  move_uploaded_file($_FILES['logo']['tmp_name'], $target_dir . $logo_name);
}
// Check email in students
$check1 = $conn->query("SELECT id FROM students WHERE email = '$email'");
$check2 = $conn->query("SELECT id FROM teachers WHERE email = '$email'");
$check3 = $conn->query("SELECT id FROM schools WHERE admin_email = '$email'");

if ($check1->num_rows > 0 || $check2->num_rows > 0 || $check3->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists"]);
    exit;
}

// Check for duplicate email or registration number
$check = $conn->prepare("SELECT id FROM schools WHERE school_email = ? OR registration_number = ?");
$check->bind_param("ss", $school_email, $registration_number);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
  echo json_encode(['status' => 'error', 'message' => 'School with this email or registration number already exists.']);
  exit;
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO schools (school_name, school_type, registration_number, affiliation_board, school_email, school_phone, school_website, country, state, city, address, logo, admin_contact_person, admin_email, admin_phone, password)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssssssssssssss", $school_name, $school_type, $registration_number, $affiliation_board, $school_email, $school_phone, $school_website, $country, $state, $city, $address, $logo_name, $admin_contact_person, $admin_email, $admin_phone, $password);

if ($stmt->execute()) {
  echo json_encode(['status' => 'success', 'message' => 'School registered successfully.']);
} else {
  http_response_code(500);
  echo json_encode(['status' => 'error', 'message' => 'Failed to register school. Please try again.', 'error' => $stmt->error]);
}
?>