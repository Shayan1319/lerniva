<?php
require_once '../sass/db_config.php';
header('Content-Type: application/json; charset=UTF-8');
session_start();

// Use admin session or fallback

if (!isset($_SESSION['app_admin_id'])) {
    echo json_encode(["status"=>"error","message"=>"Unauthorized"]);
    exit;
}

$admin_id = intval($_SESSION['app_admin_id']);

$stmt = $conn->prepare("SELECT id, username, email, full_name, phone, profile_image, role FROM app_admin WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
if($row = $result->fetch_assoc()){
    echo json_encode(["status"=>"success", "data"=>$row]);
} else {
    echo json_encode(["status"=>"error","message"=>"Admin not found"]);
}
$stmt->close();
$conn->close();
?>