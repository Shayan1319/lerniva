<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    die("Access denied!");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Fee Structure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h4 class="mb-3">All Students Fee Structure</h4>
        <div id="feeTable">Loading...</div>
    </div>

    <script>
    $(document).ready(function() {
        $.get("ajax/fetch_student_fee_structure.php", function(data) {
            $("#feeTable").html(data);
        });
    });
    </script>
</body>

</html>