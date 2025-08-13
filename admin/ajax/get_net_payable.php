<?php
session_start();
require_once '../sass/db_config.php';

$school_id = $_SESSION['admin_id'];
$student_id = $_POST['student_id'] ?? 0;

// Get student class
$studentRes = $conn->query("SELECT class_grade FROM students WHERE id = $student_id AND school_id = $school_id");
if ($studentRes->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Student not found']);
    exit;
}
$student_class = $studentRes->fetch_assoc()['class_grade'];

// 1. Sum of class_fee_types (x)
$sql1 = "SELECT SUM(rate) AS total_class_fee 
         FROM class_fee_types 
         WHERE class_grade = '$student_class' AND school_id = $school_id";
$x = ($conn->query($sql1)->fetch_assoc()['total_class_fee']) ?? 0;

// 2. Sum of student_fee_plans (y)
$sql2 = "SELECT SUM(base_amount) AS total_student_fee 
         FROM student_fee_plans 
         WHERE student_id = $student_id AND school_id = $school_id AND frequency = 'monthly'";
$y = ($conn->query($sql2)->fetch_assoc()['total_student_fee']) ?? 0;

// 3. Total
$total_fee = $x + $y;

// 4. Scholarship deduction
$scholarship = 0;
$sql3 = "SELECT type, amount FROM scholarships WHERE student_id = $student_id AND school_id = $school_id AND status = 'approved'";
$res3 = $conn->query($sql3);
while ($row = $res3->fetch_assoc()) {
    if ($row['type'] === 'percentage') {
        $scholarship += ($row['amount'] / 100) * $total_fee;
    } else {
        $scholarship += $row['amount'];
    }
}

// Final Net Payable
$net = max(0, $total_fee - $scholarship);

echo json_encode([
    'status' => 'success',
    'net_amount' => round($net, 2),
    'total' => $total_fee,
    'scholarship' => $scholarship
]);
?>