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
// $admin_id = 1; // Replace with session

$current_password = trim($_POST['current_password']);
$new_password     = trim($_POST['new_password']);

if(empty($current_password) || empty($new_password)){
    echo json_encode(["status"=>"error","message"=>"All password fields are required"]);
    exit;
}

// Get current password hash
$stmt = $conn->prepare("SELECT password FROM app_admin WHERE id=?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($hash);
$stmt->fetch();
$stmt->close();

if(!password_verify($current_password, $hash)){
    echo json_encode(["status"=>"error","message"=>"Current password is incorrect"]);
    exit;
}

// Hash new password
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE app_admin SET password=?, updated_at=NOW() WHERE id=?");
$stmt->bind_param("si", $new_hash, $admin_id);

if($stmt->execute()){
    echo json_encode(["status"=>"success","message"=>"Password updated successfully"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Failed to update password"]);
}

$stmt->close();
$conn->close();
?>