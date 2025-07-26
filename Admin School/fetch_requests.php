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
    <meta charset="UTF-8" />
    <title>Meeting Requests</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <h2>Meeting Requests</h2>
    <div id="meetingRequests"></div>

    <script>
    function loadRequests() {
        $.ajax({
            url: 'ajax/fetch_requests.php',
            type: 'GET',
            success: function(data) {
                $('#meetingRequests').html(data);
            }
        });
    }

    function updateRequest(id, action) {
        if (action === 'accept') {
            let meeting_date = prompt("Enter meeting date (YYYY-MM-DD):");
            let meeting_time = prompt("Enter meeting time (HH:MM):");
            if (!meeting_date || !meeting_time) {
                alert("Meeting date and time required!");
                return;
            }
            $.ajax({
                url: 'ajax/update_request.php',
                type: 'POST',
                data: {
                    id: id,
                    action: action,
                    meeting_date: meeting_date,
                    meeting_time: meeting_time
                },
                success: function(response) {
                    alert(response);
                    loadRequests();
                }
            });
        } else {
            $.ajax({
                url: 'ajax/update_request.php',
                type: 'POST',
                data: {
                    id: id,
                    action: action
                },
                success: function(response) {
                    alert(response);
                    loadRequests();
                }
            });
        }
    }

    $(document).ready(function() {
        loadRequests();
        $(document).on('click', '.accept-btn', function() {
            let id = $(this).data('id');
            updateRequest(id, 'accept');
        });
        $(document).on('click', '.reject-btn', function() {
            let id = $(this).data('id');
            updateRequest(id, 'reject');
        });
    });
    </script>

</body>

</html>