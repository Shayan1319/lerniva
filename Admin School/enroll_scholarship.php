<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: logout.php");
  exit;
}
require_once 'sass/db_config.php';
$school_id = $_SESSION['admin_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Scholarship</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="p-4">
    <div class="container">
        <h2>Enroll Scholarship</h2>
        <form id="scholarship_form">

            <!-- Hidden school ID -->
            <input type="hidden" id="school_id" name="school_id" value="<?php echo $school_id; ?>">

            <!-- Student Select -->
            <div class="mb-3">
                <label class="form-label">Student</label>
                <select class="form-select" id="student_id" name="student_id" required>
                    <option value="">Select Student</option>
                    <?php
          $students = $conn->query("SELECT id, full_name, class_grade, section, roll_number FROM students WHERE school_id = $school_id");
          while ($row = $students->fetch_assoc()) {
            echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['full_name']).' - '.$row['class_grade'].' '.$row['section'].' (Roll: '.$row['roll_number'].')</option>';
          }
          ?>
                </select>
            </div>

            <!-- Type -->
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="percentage">Percentage</option>
                    <option value="fixed">Fixed</option>
                </select>
            </div>

            <!-- Amount -->
            <div class="mb-3">
                <label class="form-label">Amount (<span id="amount_type_label">%</span>)</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount"
                    placeholder="Enter amount" required>
            </div>

            <!-- Reason -->
            <div class="mb-3">
                <label class="form-label">Reason</label>
                <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
            </div>



            <button type="submit" class="btn btn-primary">Save Scholarship</button>
        </form>

        <div id="result" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    // Change label if type is percentage or fixed
    $('#type').on('change', function() {
        if ($(this).val() === 'percentage') {
            $('#amount_type_label').text('%');
        } else {
            $('#amount_type_label').text('Amount');
        }
    });

    // Submit form via AJAX
    $('#scholarship_form').on('submit', function(e) {
        e.preventDefault();

        var data = {
            school_id: $('#school_id').val(),
            student_id: $('#student_id').val(),
            type: $('#type').val(),
            amount: $('#amount').val(),
            reason: $('#reason').val(),
        };

        $.ajax({
            url: 'ajax/enroll_scholarship.php',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#result').html(
                        '<div class="alert alert-success">Scholarship saved successfully.</div>'
                    );
                    $('#scholarship_form')[0].reset();
                } else {
                    $('#result').html('<div class="alert alert-danger">Error: ' + response.message +
                        '</div>');
                }
            },
            error: function() {
                $('#result').html('<div class="alert alert-danger">AJAX request failed.</div>');
            }
        });
    });
    </script>
</body>

</html>