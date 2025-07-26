<?php
session_start();
header('Content-Type: application/json');

require_once '../sass/db_config.php';

try {
    // Decode JSON body
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("Invalid input data");
    }

    // Get values
    $school_id = $_SESSION['admin_id'];
    $class_grade = $data['class_grade'];
    $frequency = $data['frequency'];
    $status = $data['status'];
    $total_amount = $data['total_amount'];
    $fee_items = $data['fee_items']; // array of fee_type_id + rate

    if (empty($fee_items)) {
        throw new Exception("No fee items provided");
    }

    // Insert into fee_structures
    $stmt = $conn->prepare("
        INSERT INTO fee_structures 
        (school_id, class_grade, amount, frequency, status, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("isdss", $school_id, $class_grade, $total_amount, $frequency, $status);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("Failed to insert fee structure");
    }

    // ✅ Get inserted fee_structure ID
    $fee_structure_id = $stmt->insert_id;

    // Insert fee items into class_fee_types
    $stmt2 = $conn->prepare("
        INSERT INTO class_fee_types 
        (school_id, class_grade, fee_type_id, rate, fee_structure_id) 
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($fee_items as $fee) {
        $fee_type_id = $fee['fee_type_id'];
        $rate = $fee['rate'];

        $stmt2->bind_param("isidi", $school_id, $class_grade, $fee_type_id, $rate, $fee_structure_id);
        $stmt2->execute();
    }

    echo json_encode([
        "status" => "success",
        "message" => "Class fee plan saved successfully."
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
} finally {
    $conn->close();
}