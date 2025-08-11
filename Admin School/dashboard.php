<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: logout.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5">
        <h3>Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></h3>
        <p>School: <?= htmlspecialchars($_SESSION['school_name']) ?></p>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <a href="students_list.php" class="btn btn-danger">Students</a>
        <a href="timetable.php" class="btn btn-danger">time_table</a>
        <a href="view_all_time.php" class="btn btn-danger">view time_table</a>
        <a href="faculty_registration.php" class="btn btn-danger">Faculty</a>
        <a href="faculty_attendance.php" class="btn btn-danger">faculty_attendance</a>
        <a href="fee_type.php" class="btn btn-danger">Fee Type</a>
        <a href="fee_strutter.php" class="btn btn-danger">Fee</a>
        <a href="enroll_student_fee_plan.php" class="btn btn-danger"> stu Fee</a>
        <a href="show_fee_structures.php" class="btn btn-danger"> Show class Fee</a>
        <a href="enroll_scholarship.php" class="btn btn-danger"> enroll_scholarship</a>
        <a href="load_scholarships.php" class="btn btn-danger">load_scholarships</a>
        <a href="fee_structure_view.php" class="btn btn-danger">fee_structure</a>
        <a href="fee_slip.php" class="btn btn-danger">fee_slip</a>
        <a href="fee_period_form.php" class="btn btn-danger">fee_period_form</a>
        <a href="submit_student_fee.php" class="btn btn-danger">submit_student_fee</a>
        <a href="meeting_form.php" class="btn btn-danger">meeting_crud</a>
        <a href="fetch_requests.php" class="btn btn-danger">fetch_requests</a>
        <a href="admin_notes.php" class="btn btn-danger">admin_notes</a>
        <a href="pending_leaves.php" class="btn btn-danger">pending_leaves</a>
        <a href="assign_task.php" class="btn btn-danger">assign_task</a>
    </div>
</body>

</html>