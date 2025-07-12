<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../sass/db_config.php'; // your DB connection

function clean_input($data) {
  return htmlspecialchars(strip_tags(trim($data)));
}

// Check POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
  exit;
}

// Validate inputs
if (empty($_POST['school_email']) || empty($_POST['password'])) {
  echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
  exit;
}

$email = clean_input($_POST['school_email']);
$password = $_POST['password'];

// Find user
$stmt = $conn->prepare("SELECT id, school_name, school_email, password FROM schools WHERE school_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();

  if (password_verify($password, $user['password'])) {
    // Login success
    $_SESSION['school_id'] = $user['id'];
    $_SESSION['school_email'] = $user['school_email'];
    $_SESSION['school_name'] = $user['school_name'];

    echo json_encode(['status' => 'success', 'message' => 'Login successful']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Incorrect password']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'No account found with this email']);
}
?>