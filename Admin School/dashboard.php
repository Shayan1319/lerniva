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
        <a href="timetable.php" class="btn btn-danger">time_table</a>
        <a href="view_all_time.php" class="btn btn-danger">view time_table</a>
        <a href="faculty_registration.php" class="btn btn-danger">Faculty</a>
    </div>
</body>

</html>