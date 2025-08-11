<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: logout.php");
  exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Fee Periods</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h4>Manage Fee Periods</h4>
        <form id="feePeriodForm">
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="school_id" value="<?= $_SESSION['admin_id'] ?? 1 ?>">

            <div class="mb-3">
                <label>Period Name</label>
                <input type="text" name="period_name" id="period_name" class="form-control" required
                    placeholder="e.g. August 2025">
            </div>

            <div class="mb-3">
                <label>Period Type</label>
                <select name="period_type" id="period_type" class="form-select" required>
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="term">Term</option>
                    <option value="custom">Custom</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Save Fee Period</button>
            <div id="response" class="mt-3"></div>
        </form>

        <hr>
        <h5>All Fee Periods</h5>
        <div id="feePeriodTable"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
    loadTable();

    $('#feePeriodForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'ajax/save_fee_period.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                $('#response').html(`<div class="alert alert-${res.status}">${res.message}</div>`);
                if (res.status === 'success') {
                    $('#feePeriodForm')[0].reset();
                    $('#id').val('');
                    loadTable();
                }
            }
        });
    });

    function loadTable() {
        $.get('ajax/fetch_fee_periods.php', function(data) {
            $('#feePeriodTable').html(data);
        });
    }

    function editPeriod(id) {
        $.get('ajax/fetch_fee_periods.php?id=' + id, function(data) {
            let p = JSON.parse(data);
            $('#id').val(p.id);
            $('#period_name').val(p.period_name);
            $('#period_type').val(p.period_type);
            $('#start_date').val(p.start_date);
            $('#end_date').val(p.end_date);
        });
    }

    function deletePeriod(id) {
        if (confirm('Delete this period?')) {
            $.post('ajax/delete_fee_period.php', {
                id
            }, function(res) {
                alert(res.message);
                loadTable();
            }, 'json');
        }
    }
    </script>
</body>

</html>