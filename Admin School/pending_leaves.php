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
    <title>Pending Faculty Leaves</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h3>Pending Faculty Leave Requests</h3>
        <div id="leaveTable">Loading...</div>
    </div>

    <script>
    function loadPendingLeaves() {
        $.get('ajax/fetch_pending_leaves.php', function(data) {
            $('#leaveTable').html(data);
        });
    }

    function updateLeaveStatus(id, status) {
        $.post('ajax/update_leave_status.php', {
            id: id,
            status: status
        }, function(response) {
            alert(response.message);
            loadPendingLeaves();
        }, 'json');
    }

    $(document).ready(function() {
        loadPendingLeaves();

        $(document).on('click', '.action-btn', function() {
            const id = $(this).data('id');
            const status = $(this).data('status');
            updateLeaveStatus(id, status);
        });
    });
    </script>
</body>

</html>