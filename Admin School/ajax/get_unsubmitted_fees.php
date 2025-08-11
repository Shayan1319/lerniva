<?php
session_start();
require_once '../sass/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo "Session expired.";
    exit;
}

$school_id = $_SESSION['admin_id'];

$period_id = $_POST['fee_period_id'] ?? '';
$class_grade = $_POST['class_grade'] ?? '';
$student_id = $_POST['student_id'] ?? '';

// Get students matching filters
$student_sql = "SELECT * FROM students WHERE school_id = ?";
$params = [$school_id];
$types = "i";

if (!empty($class_grade)) {
    $student_sql .= " AND class_grade = ?";
    $params[] = $class_grade;
    $types .= "s";
}

if (!empty($student_id)) {
    $student_sql .= " AND id = ?";
    $params[] = $student_id;
    $types .= "i";
}

$stmt = $conn->prepare($student_sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-info'>No students found.</div>";
    exit;
}

while ($student = $result->fetch_assoc()) {
    $sid = $student['id'];

    // If fee_period is selected, check if fee slip already exists
    if (!empty($period_id)) {
        $check = $conn->query("SELECT id FROM fee_slip_details WHERE student_id = $sid AND fee_period_id = $period_id");
        if ($check->num_rows > 0) continue; // Skip already submitted
    }

    // Skip student if fee slip already submitted in any period (when no period is selected)
    if (empty($period_id)) {
        $check = $conn->query("SELECT id FROM fee_slip_details WHERE student_id = $sid");
        if ($check->num_rows > 0) continue;
    }

    // Mock slip data for now — replace with real values if needed
    $total = 10000;
    $scholarship = 0;
    $net = $total - $scholarship;

    echo "
    <div class='fee-slip'>
        <div class='fee-slip-header'>
            <img src='../uploads/school_logo.png' alt='Logo'>
            <h2>Iqra School Jehangira</h2>
            <p>Jehangira nowshara KPK - Jehangira</p>
            <h5>Fee Slip for: <strong>{$student['full_name']}</strong> (Roll #: {$student['roll_number']})</h5>
        </div>

        <div class='fee-slip-section'>
            <div class='fee-line'><span class='fee-slip-label'>Class</span><span>{$student['class_grade']}</span></div>
            <div class='fee-line'><span class='fee-slip-label'>Parent</span><span>{$student['parent_name']}</span></div>
            <div class='fee-line'><span class='fee-slip-label'>Period</span><span>" . (!empty($period_id) ? "Selected Period" : "—") . "</span></div>
        </div>

        <div class='fee-slip-section'>
            <div class='fee-line'><span>Tuition Fee</span><span>Rs. 6000</span></div>
            <div class='fee-line'><span>Stationery</span><span>Rs. 2000</span></div>
            <div class='fee-line'><span>Transport</span><span>Rs. 1000</span></div>
            <div class='fee-line'><span>Others</span><span>Rs. 1000</span></div>
            <div class='fee-line fee-slip-total'><span>Total</span><span>Rs. $total</span></div>
            <div class='fee-line'><span>Scholarship</span><span>- Rs. $scholarship</span></div>
            <div class='fee-line fee-slip-total'><span>Net Payable</span><span>Rs. $net</span></div>
        </div>
    </div>";
}
?>