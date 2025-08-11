<?php
session_start();
require_once 'sass/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo "Unauthorized";
    exit;
}

$school_id = $_SESSION['admin_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Faculty Attendance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>

    <div class="container mt-4">
        <h3>Faculty Attendance (Today)</h3>
        <form id="attendanceForm">
            <input type="hidden" name="attendance_date" value="<?= date('Y-m-d') ?>">

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
        $stmt = $conn->prepare("SELECT id, full_name, phone, photo FROM faculty WHERE campus_id = ?");
        $stmt->bind_param("i", $school_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
            <td><img src='uploads/{$row['photo']}' width='50' height='50' style='object-fit: cover; border-radius: 50%'></td>
            <td>{$row['full_name']}</td>
            <td>{$row['phone']}</td>
            <td>
              <input type='hidden' name='faculty_ids[]' value='{$row['id']}'>
              <label><input type='radio' name='status_{$row['id']}' value='Present' checked> Present</label>
              <label class='ms-2'><input type='radio' name='status_{$row['id']}' value='Absent'> Absent</label>
              <label class='ms-2'><input type='radio' name='status_{$row['id']}' value='Leave'> Leave</label>
            </td>
          </tr>";
        }
        ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Submit Attendance</button>
        </form>

        <div id="message" class="mt-3"></div>
    </div>

    <script>
    $('#attendanceForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax/save_faculty_attendance.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#message').html(`<div class="alert alert-success">${response}</div>`);
            },
            error: function() {
                $('#message').html(`<div class="alert alert-danger">Something went wrong.</div>`);
            }
        });
    });
    </script>

</body>

</html>