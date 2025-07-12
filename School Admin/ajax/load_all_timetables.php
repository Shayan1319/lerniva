<?php
session_start();
include '../sass/db_config.php';

if (!isset($_SESSION['school_id'])) {
    echo "School ID required.";
    exit;
}
$school_id = $_SESSION['school_id'];

// Get all meta (classes)
$meta_q = "SELECT * FROM class_timetable_meta WHERE school_id = '$school_id'";
$meta_r = mysqli_query($conn, $meta_q);

$html = '';

while ($meta = mysqli_fetch_assoc($meta_r)) {
    $meta_id = $meta['id'];
    $class = $meta['class_name'];
    $section = $meta['section'];

    // Get periods
    $periods_q = "SELECT * FROM class_timetable_details WHERE timing_meta_id = '$meta_id' ORDER BY period_number ASC";
    $periods_r = mysqli_query($conn, $periods_q);
    $periods = [];
    while ($p = mysqli_fetch_assoc($periods_r)) {
        // Get teacher name
        $teacher_name = '';
        if ($p['teacher_id']) {
            $t_q = mysqli_query($conn, "SELECT full_name FROM faculty WHERE id = '" . $p['teacher_id'] . "'");
            if (mysqli_num_rows($t_q)) {
                $teacher_row = mysqli_fetch_assoc($t_q);
                $teacher_name = $teacher_row['full_name'];
            }
        }
        $periods[] = [
            'name' => $p['period_name'],
            'start' => $p['start_time'],
            'end' => $p['end_time'],
            'type' => $p['period_type'],
            'break' => $p['is_break'],
            'teacher' => $teacher_name
        ];
    }

    // Weekdays logic
    $week_q = mysqli_query($conn, "SELECT * FROM class_timetable_weekdays WHERE timetable_id = '$meta_id'");
    $weekday_data = [];
    while ($w = mysqli_fetch_assoc($week_q)) {
        $weekday_data[$w['weekday']] = [
            'is_half_day' => $w['is_half_day'],
            'total_periods' => $w['total_periods']
        ];
    }

    // Start HTML table
    $html .= "<h5 class='mt-4'>Class: $class - Section: $section</h5>";
    $html .= "<table class='table table-bordered text-center'><thead><tr><th>Day</th>";

    foreach ($periods as $i => $p) {
        $html .= "<th>Period " . ($i + 1) . "</th>";
    }

    $html .= "</tr></thead><tbody>";

    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    foreach ($days as $day) {
        $is_half = isset($weekday_data[$day]) && $weekday_data[$day]['is_half_day'] == 1;
        $max = $is_half ? $weekday_data[$day]['total_periods'] : count($periods);
        $html .= "<tr><td><strong>$day</strong></td>";

        for ($i = 0; $i < count($periods); $i++) {
            if ($i >= $max) {
                $html .= "<td class='text-muted'>-</td>";
                continue;
            }

            $pd = $periods[$i];
            $bg = $pd['break'] ? "table-warning" : "";
            $html .= "<td class='$bg'>
                <div><strong>{$pd['name']}</strong></div>
                <div>{$pd['type']}</div>
                <div>{$pd['start']} - {$pd['end']}</div>
                <div>{$pd['teacher']}</div>
            </td>";
        }

        $html .= "</tr>";
    }

    $html .= "</tbody></table><hr/>";
}

echo $html;
?>