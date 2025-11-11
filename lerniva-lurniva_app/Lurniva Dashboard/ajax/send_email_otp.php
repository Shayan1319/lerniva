<?php
require_once '../sass/db_config.php';
require_once '../../mail_library.php'; // include PHPMailer library

session_start();

if(!isset($_POST['email']) || empty($_POST['email'])){
    echo json_encode(['status'=>'error','message'=>'Email is required']);
    exit;
}

$newEmail = trim($_POST['email']);
$adminId = $_SESSION['app_admin_id']; // assuming admin is logged in and ID stored in session

// Get admin details
$res = $conn->query("SELECT * FROM app_admin WHERE id=$adminId");
if($res->num_rows != 1){
    echo json_encode(['status'=>'error','message'=>'Admin not found']);
    exit;
}
$admin = $res->fetch_assoc();

// Generate OTP
$otp = rand(100000, 999999);
$expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

// Save OTP and expiry to DB
$conn->query("UPDATE app_admin SET verification_code='$otp', code_expires_at='$expiry', message_email='$newEmail' WHERE id=" . $admin['id']);

// Send email
$subject = "App Admin OTP Verification";
$msg = "Hello {$admin['full_name']},<br><br>Your OTP is: <b>$otp</b><br><br>This code expires in 5 minutes.";

if(sendMail($newEmail, $subject, $msg, $admin['full_name'])){
    echo json_encode(['status'=>'success','message'=>'OTP sent successfully']);
} else {
    echo json_encode(['status'=>'error','message'=>'Failed to send OTP email']);
}