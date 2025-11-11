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
// $admin_id =  1; // Replace with session if available

if(!isset($_FILES['admin_image']) || $_FILES['admin_image']['error'] !== UPLOAD_ERR_OK){
    echo json_encode(["status"=>"error","message"=>"No file uploaded or error"]);
    exit;
}

$file = $_FILES['admin_image'];
$allowed_ext = ['jpg','jpeg','png','gif','webp'];
$max_size = 2*1024*1024; // 2MB
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if(!in_array($ext, $allowed_ext)){
    echo json_encode(["status"=>"error","message"=>"Invalid file type"]);
    exit;
}
if($file['size'] > $max_size){
    echo json_encode(["status"=>"error","message"=>"File too large"]);
    exit;
}

$upload_dir = '../uploads/admins/';
if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

$new_name = 'admin_'.$admin_id.'_'.time().'.'.$ext;
$upload_path = $upload_dir.$new_name;

if(!move_uploaded_file($file['tmp_name'], $upload_path)){
    echo json_encode(["status"=>"error","message"=>"Upload failed"]);
    exit;
}

// Delete old image
$stmt = $conn->prepare("SELECT profile_image FROM app_admin WHERE id=?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($old_image);
$stmt->fetch();
$stmt->close();

if($old_image && file_exists($upload_dir.$old_image)){
    unlink($upload_dir.$old_image);
}

// Update DB
$stmt = $conn->prepare("UPDATE app_admin SET profile_image=?, updated_at=NOW() WHERE id=?");
$stmt->bind_param("si", $new_name, $admin_id);

if($stmt->execute()){
    $baseUrl = rtrim((isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'], '/').'/';
    echo json_encode(["status"=>"success","message"=>"Profile image updated","image_path"=>$baseUrl.'uploads/admins/'.$new_name]);
} else {
    echo json_encode(["status"=>"error","message"=>"DB update failed"]);
}

$stmt->close();
$conn->close();
?>