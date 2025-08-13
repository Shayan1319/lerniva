<?php
session_start();
header('Content-Type: application/json');
require '../sass/db_config.php';

if (!isset($_SESSION['admin_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
  exit;
}

$school_id     = $_SESSION['admin_id'];
$student_id    = intval($_POST['student_id']);
$fee_period_id = intval($_POST['fee_period_id']);
$amount_paid   = floatval($_POST['amount_paid']);
$payment_method = $_POST['payment_method'];
$payment_status = 'Paid';
$payment_date = date('Y-m-d');

// ✅ Check if fee slip already exists
$check = $conn->prepare("SELECT id FROM fee_slip_details WHERE school_id = ? AND student_id = ? AND fee_period_id = ?");
$check->bind_param("iii", $school_id, $student_id, $fee_period_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
  echo json_encode(['status' => 'error', 'message' => 'Fee slip already submitted.']);
  exit;
}

// ✅ Get fee types
$feeTypes = [];
$ftResult = $conn->query("SELECT id FROM fee_types WHERE school_id = $school_id");
while ($row = $ftResult->fetch_assoc()) {
  $feeTypes[] = $row['id'];
}

// ✅ Get student class
$studentQuery = $conn->query("SELECT class_grade FROM students WHERE id = $student_id AND school_id = $school_id");
if (!$studentQuery || $studentQuery->num_rows == 0) {
  echo json_encode(['status' => 'error', 'message' => 'Student not found']);
  exit;
}
$class = $studentQuery->fetch_assoc()['class_grade'];

// ✅ Get total class fee
$total_amount = 0;
$classFeeQuery = $conn->query("SELECT * FROM class_fee_types WHERE school_id = $school_id AND class_grade = '$class'");
$feeMap = [];
while ($cf = $classFeeQuery->fetch_assoc()) {
  $feeMap[$cf['fee_type_id']] = $cf['rate'];
  $total_amount += $cf['rate'];
}

// ✅ Override with student-specific fees
$studentFeeQuery = $conn->query("SELECT * FROM student_fee_plans WHERE school_id = $school_id AND student_id = $student_id");
while ($sf = $studentFeeQuery->fetch_assoc()) {
  $fid = $sf['fee_component'];
  $rate = $sf['base_amount'];
  $total_amount -= $feeMap[$fid] ?? 0; // remove class fee
  $total_amount += $rate;              // add student-specific fee
}

// ✅ Check scholarships
$scholarshipQuery = $conn->query("SELECT * FROM scholarships WHERE school_id = $school_id AND student_id = $student_id AND status = 'approved'");
$scholarship_amount = 0;

while ($sch = $scholarshipQuery->fetch_assoc()) {
  if ($sch['type'] === 'percentage') {
    $scholarship_amount += ($total_amount * $sch['amount']) / 100;
  } else {
    $scholarship_amount += $sch['amount'];
  }
}

$net_payable = $total_amount - $scholarship_amount;

// ✅ Insert into fee_slip_details
$stmt = $conn->prepare("INSERT INTO fee_slip_details 
(school_id, student_id, fee_period_id, total_amount, scholarship_amount, net_payable, amount_paid, payment_status, payment_date, created_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

$stmt->bind_param("iiiddddss", $school_id, $student_id, $fee_period_id, $total_amount, $scholarship_amount, $net_payable, $amount_paid, $payment_status, $payment_date);

if ($stmt->execute()) {
  echo json_encode(['status' => 'success', 'message' => 'Fee submitted successfully.']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Failed to submit fee.']);
}