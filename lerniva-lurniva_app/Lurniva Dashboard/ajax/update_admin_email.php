<?php
require_once '../sass/db_config.php';

session_start();

if(!isset($_POST['otp']) || empty($_POST['otp'])){
    echo json_encode(['status'=>'error','message'=>'OTP is required']);
    exit;
}

$otp = trim($_POST['otp']);
$adminId = $_SESSION['app_admin_id'];

// Get admin details
$res = $conn->query("SELECT * FROM app_admin WHERE id=$adminId");
if($res->num_rows != 1){
    echo json_encode(['status'=>'error','message'=>'Admin not found']);
    exit;
}
$admin = $res->fetch_assoc();

// Check OTP and expiry
$currentTime = date("Y-m-d H:i:s");
if($admin['verification_code'] != $otp){
    echo json_encode(['status'=>'error','message'=>'Invalid OTP']);
    exit;
}
if($currentTime > $admin['code_expires_at']){
    echo json_encode(['status'=>'error','message'=>'OTP expired']);
    exit;
}

// OTP valid – update email and clear OTP
$conn->query("UPDATE app_admin SET email=message_email, verification_code=NULL, code_expires_at=NULL, message_email=NULL WHERE id=$adminId");

echo json_encode(['status'=>'success','message'=>'Email updated successfully']);