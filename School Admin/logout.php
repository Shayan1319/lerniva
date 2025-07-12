<?php
session_start();
session_destroy();
header("Location: school_login.php");
exit;
?>