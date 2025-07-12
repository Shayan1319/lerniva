<?php
session_start();
include '../sass/db_config.php'; // Make sure this sets up $conn (mysqli connection)

header('Content-Type: application/json');

if (!isset($_SESSION['school_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

$school_id = $_SESSION['school_id'];
$assembly_time = $_POST['assembly_time'];
echo $assembly_time;
$leave_time = $_POST['leave_time'];
$is_finalized = isset($_POST['is_finalized']) ? 1 : 0;
$half_day_config = isset($_POST['half_day_config']) ? $_POST['half_day_config'] : null;
$created_by = $school_id;

// Insert into school_timings
$sql = "INSERT INTO school_timings (school_id, assembly_time, leave_time, half_day_config, is_finalized, is_preview, created_by, created_at)
        VALUES ('$school_id', '$assembly_time', '$leave_time', '$half_day_config', '$is_finalized', 1, '$created_by', NOW())";

if (mysqli_query($conn, $sql)) {
    $timetable_id = mysqli_insert_id($conn);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save school timings."]);
    exit;
}
// Save half-day config to class_timetable_weekdays
if (!empty($half_day_config)) {
    $halfDayArray = json_decode($half_day_config, true);
    foreach ($halfDayArray as $weekday => $config) {
        $assembly = $config['assembly_time'];
        $leave = $config['leave_time'];
        $periods = (int)$config['total_periods'];
        $is_half_day = isset($config['is_half_day']) ? (int)$config['is_half_day'] : 0;

        $sqlWeekday = "INSERT INTO class_timetable_weekdays (timetable_id, weekday, assembly_time, leave_time, total_periods, is_half_day, created_at)
                       VALUES ('$timetable_id', '$weekday', '$assembly', '$leave', '$periods', '$is_half_day', NOW())";

        mysqli_query($conn, $sqlWeekday);
    }
}


// Process class/section timetables
$class_names = $_POST['class_name'];
$sections = $_POST['section'];
$total_periods_list = $_POST['total_periods'];

foreach ($class_names as $index => $class_name) {
    $section = mysqli_real_escape_string($conn, $sections[$index]);
    $total_periods = (int)$total_periods_list[$index];

    // Insert into class_timetable_meta
    $sqlMeta = "INSERT INTO class_timetable_meta (school_id, timing_table_id, class_name, section, total_periods, is_finalized, created_by, created_at)
                VALUES ('$school_id', '$timetable_id', '$class_name', '$section', '$total_periods', '$is_finalized', '$created_by', NOW())";

    if (mysqli_query($conn, $sqlMeta)) {
        $meta_id = mysqli_insert_id($conn);

        $period_names = $_POST['period_name'][$index];
        $start_times = $_POST['start_time'][$index];
        $end_times = $_POST['end_time'][$index];
        $types = $_POST['period_type'][$index];
        $teachers = $_POST['teacher_id'][$index];
        $is_breaks = isset($_POST['is_break'][$index]) ? $_POST['is_break'][$index] : [];

        foreach ($period_names as $p => $pname) {
            $start = $start_times[$p];
            $end = $end_times[$p];
            $type = $types[$p];
            $teacher = $teachers[$p];
            $is_break = isset($is_breaks[$p]) ? 1 : 0;

            $sqlDetail = "INSERT INTO class_timetable_details (timing_meta_id, period_number, period_name, start_time, end_time, period_type, teacher_id, is_break, created_by, created_at)
                          VALUES ('$meta_id', '".($p+1)."', '$pname', '$start', '$end', '$type', '$teacher', '$is_break', '$created_by', NOW())";

            mysqli_query($conn, $sqlDetail);
        }
    }
}

echo json_encode(["status" => "success", "message" => "Timetable saved successfully."]);
exit;