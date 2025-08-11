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
    <title>Submit Student Fee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="p-4">

    <div class="container">
        <h4>Submit Student Fee</h4>
        <form id="feeSubmitForm">
            <div class="mb-3">
                <label for="fee_period_id" class="form-label">Fee Period</label>
                <select class="form-select" name="fee_period_id" id="fee_period_id" required>
                    <option value="">Select Period</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="student_id" class="form-label">Student</label>
                <select class="form-select" name="student_id" id="student_id" required>
                    <option value="">Select Student</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="amount_paid" class="form-label">Amount Paid</label>
                <input type="number" class="form-control" name="amount_paid" required min="1">
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select class="form-select" name="payment_method" required>
                    <option value="Cash">Cash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Online">Online</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit Fee</button>
            <div id="response" class="mt-3 text-success fw-bold"></div>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        // Load Fee Periods
        $.get("ajax/get_fee_periods.php", function(data) {
            $('#fee_period_id').append(data);
        });

        // Load Students
        $.getJSON("ajax/get_students.php", function(res) {
            if (res.status === "success") {
                res.data.forEach(s => {
                    $('#student_id').append(
                        `<option value="${s.id}">${s.name} (${s.class} - ${s.roll})</option>`
                    );
                });
            }
        });

        // Submit Form
        $('#feeSubmitForm').on('submit', function(e) {
            e.preventDefault();
            $.post('ajax/submit_fee.php', $(this).serialize(), function(res) {
                $('#response').text(res.message);
                if (res.status === 'success') $('#feeSubmitForm')[0].reset();
            }, 'json');
        });
    });
    </script>

</body>

</html>