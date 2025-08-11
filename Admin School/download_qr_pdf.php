<?php
session_start();
require_once 'sass/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    die("Access denied");
}

$admin_id = $_SESSION['admin_id'];

$sql = "SELECT full_name, class_grade, section, roll_number FROM students WHERE school_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Print Student QR Codes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    .qr-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        padding: 20px;
    }

    .qr-box {
        text-align: center;
        border: 1px solid #ccc;
        padding: 10px;
        width: 180px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .qr-box img {
        width: 150px;
        height: 150px;
    }

    .print-btn {
        margin: 20px;
        display: block;
        text-align: center;
    }

    @media print {
        .print-btn {
            display: none;
        }
    }
    </style>
</head>

<body>

    <div class="print-btn">
        <button id="print" class="btn btn-primary">Download / Print PDF</button>
    </div>

    <div class="qr-container">
        <?php
while ($row = $result->fetch_assoc()) {
    $jsonData = json_encode([
        'full_name' => $row['full_name'],
        'class' => $row['class_grade'],
        'section' => $row['section'],
        'roll_number' => $row['roll_number']
    ], JSON_UNESCAPED_UNICODE);

    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . rawurlencode($jsonData) . "&size=150x150";

    echo "<div class='qr-box'>
            <img src='{$qrUrl}' alt='QR Code'>
            <div><strong>{$row['full_name']}</strong></div>
            <div>Class: {$row['class_grade']} - {$row['section']}</div>
            <div>Roll #: {$row['roll_number']}</div>
          </div>";
}
?>
    </div>

    <script>
    document.getElementById('print').addEventListener('click', function() {
        window.print();
    });
    </script>

</body>

</html>