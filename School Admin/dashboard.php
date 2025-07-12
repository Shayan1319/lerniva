<?php
session_start();
if (!isset($_SESSION['school_id'])) {
  header("Location: school_login.php");
  exit;
}
?>
<h1>Welcome, <?= $_SESSION['school_name'] ?>!</h1>
<a href="logout.php">Logout</a>
<a href="faculty_registration.php">faculty registration</a>
<a href="time_table.php">time table</a>
<a href="school_tables.php">time table</a>