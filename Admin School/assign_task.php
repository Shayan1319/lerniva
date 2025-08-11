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
    <title>Assign School Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="p-4">

    <div class="container">
        <h4>Assign Task</h4>
        <form id="taskForm">
            <input type="hidden" name="action" value="insert">
            <input type="hidden" name="school_id" value="1">

            <div class="mb-3">
                <label>Task Title</label>
                <input type="text" name="task_title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="task_description" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label>Assign To</label>
                <select name="assigned_to_type" id="assigned_to_type" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Person</label>
                <select name="assigned_to_id" id="personList" class="form-select" required>
                    <option value="">Select Person</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Due Date</label>
                <input type="date" name="due_date" class="form-control" required>
            </div>

            <button class="btn btn-primary" type="submit">Save Task</button>
        </form>

        <hr>
        <div id="taskList"></div>
    </div>

    <script>
    $(document).ready(function() {
        loadTasks();

        $('#assigned_to_type').on('change', function() {
            const type = $(this).val();
            $.post('ajax/get_people.php', {
                type
            }, function(data) {
                $('#personList').html(data);
            });
        });

        $('#taskForm').on('submit', function(e) {
            e.preventDefault();
            $.post('ajax/task_crud.php', $(this).serialize(), function(res) {
                alert(res.message);
                $('#taskForm')[0].reset();
                loadTasks();
            }, 'json');
        });

        $(document).on('click', '.delete-task', function() {
            if (confirm("Delete this task?")) {
                const id = $(this).data('id');
                $.post('ajax/task_crud.php', {
                    action: 'delete',
                    id
                }, function(res) {
                    alert(res.message);
                    loadTasks();
                }, 'json');
            }
        });

        $(document).on('click', '.edit-task', function() {
            const id = $(this).data('id');
            $.post('ajax/task_crud.php', {
                action: 'get',
                id
            }, function(data) {
                $('[name="action"]').val('update');
                $.each(data, function(key, value) {
                    $('[name="' + key + '"]').val(value);
                });
            }, 'json');
        });

        function loadTasks() {
            $.get('ajax/task_crud.php?action=list', function(data) {
                $('#taskList').html(data);
            });
        }
    });
    </script>
</body>

</html>