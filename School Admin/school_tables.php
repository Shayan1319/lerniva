<!-- show_timetables.php -->
<?php
session_start();
if (!isset($_SESSION['school_id'])) {
    echo "School ID missing.";
    exit;
}
$school_id = $_SESSION['school_id'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>School Timetables</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-4">
        <h3>All Timetables</h3>
        <div id="timetableContainer"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $.ajax({
            url: 'ajax/load_all_timetables.php',
            type: 'POST',
            data: {},
            success: function(data) {
                $('#timetableContainer').html(data);
            }
        });
    });
    </script>
</body>

</html>