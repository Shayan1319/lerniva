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
    <title>Meeting Announcement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="p-4">
    <div class="container">
        <h2>Meeting Announcement</h2>
        <form id="meetingForm">
            <input type="hidden" name="school_id" value="<?php echo $_SESSION['admin_id']; ?>">

            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Agenda</label>
                <textarea name="meeting_agenda" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="meeting_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Time</label>
                <input type="time" name="meeting_time" class="form-control" required>
            </div>

            <!-- Person One -->
            <div class="mb-3">
                <label>Person One</label>
                <select name="meeting_person" id="meeting_person" class="form-select" required>
                    <option value="">Select</option>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="parent">Parent</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Person One ID</label>
                <select name="person_id_one" id="person_id_one" class="form-select" required>
                    <option value="">Select Person</option>
                </select>
            </div>

            <!-- Person Two -->
            <div class="mb-3">
                <label>Person Two</label>
                <select name="meeting_person2" id="meeting_person2" class="form-select" required>
                    <option value="">Select</option>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="parent">Parent</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Person Two ID</label>
                <select name="person_id_two" id="person_id_two" class="form-select" required>
                    <option value="">Select Person</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="scheduled">Scheduled</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Meeting</button>
        </form>

        <div id="result" class="mt-3"></div>

        <hr>
        <h3>All Meetings</h3>
        <div id="allMeetings"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {

        loadMeetings();

        $("#meeting_person").change(function() {
            $.post("ajax/get_persons.php", {
                person_type: $(this).val()
            }, function(data) {
                $("#person_id_one").html(data);
            });
        });

        $("#meeting_person2").change(function() {
            $.post("ajax/get_persons.php", {
                person_type: $(this).val()
            }, function(data) {
                $("#person_id_two").html(data);
            });
        });


        $("#meetingForm").off("submit").on("submit", function(e) {
            e.preventDefault();
            var updateId = $("button[type=submit]").data("update-id");
            var action = updateId ? "update" : "insert";

            $.ajax({
                url: "ajax/meeting_crud.php",
                method: "POST",
                data: $(this).serialize() + "&action=" + action + (updateId ? "&id=" +
                    updateId : ""),
                success: function(data) {
                    $("#result").html(data);
                    $("#meetingForm")[0].reset();
                    $("button[type=submit]").text("Save Meeting").removeData("update-id");
                    loadMeetings();
                }
            });
        });

        $(document).on("click", ".deleteMeeting", function() {
            var id = $(this).data("id");
            if (confirm("Are you sure?")) {
                $.post("ajax/meeting_crud.php", {
                    id: id,
                    action: "delete"
                }, function(data) {
                    $("#result").html(data);
                    loadMeetings();
                });
            }
        });

        $(document).on("click", ".editMeeting", function() {
            var id = $(this).data("id");
            $.post("ajax/meeting_crud.php", {
                id: id,
                action: "getOne"
            }, function(data) {
                var m = JSON.parse(data);
                $("input[name=title]").val(m.title);
                $("textarea[name=meeting_agenda]").val(m.meeting_agenda);
                $("input[name=meeting_date]").val(m.meeting_date);
                $("input[name=meeting_time]").val(m.meeting_time);
                $("select[name=meeting_person]").val(m.meeting_person).change();
                setTimeout(function() {
                    $("#person_id_one").val(m.person_id_one);
                }, 300);
                $("select[name=meeting_person2]").val(m.meeting_person2).change();
                setTimeout(function() {
                    $("#person_id_two").val(m.person_id_two);
                }, 300);
                $("select[name=status]").val(m.status);
                $("button[type=submit]").text("Update Meeting").data("update-id", id);
            });
        });

        function loadMeetings() {
            $.post("ajax/meeting_crud.php", {
                action: "getAll"
            }, function(data) {
                $("#allMeetings").html(data);
            });
        }

    });
    </script>

</body>

</html>