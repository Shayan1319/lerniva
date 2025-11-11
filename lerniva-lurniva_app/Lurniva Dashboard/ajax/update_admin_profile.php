<?php
require_once '../sass/db_config.php';
header('Content-Type: application/json; charset=UTF-8');
session_start();
// Use session admin ID (or hardcoded for testing)
if (!isset($_SESSION['app_admin_id'])) {
echo json_encode(["status"=>"error","message"=>"Unauthorized"]);
exit;
}

$admin_id = intval($_SESSION['app_admin_id']); // change for testing if needed

$full_name = trim($_POST['full_name']);
$username  = trim($_POST['username']);
$email     = trim($_POST['email']);
$phone     = trim($_POST['phone']);

// Basic validation
if(empty($full_name) || empty($username) || empty($email)){
    echo json_encode(["status"=>"error","message"=>"Full Name, Username and Email are required"]);
    exit;
}

$stmt = $conn->prepare("UPDATE app_admin SET full_name=?, username=?, email=?, phone=?, updated_at=NOW() WHERE id=?");
$stmt->bind_param("ssssi", $full_name, $username, $email, $phone, $admin_id);

if($stmt->execute()){
    echo json_encode(["status"=>"success","message"=>"Profile updated successfully"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Failed to update profile"]);
}

$stmt->close();
$conn->close();
?>