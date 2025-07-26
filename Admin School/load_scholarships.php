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
    <meta charset="UTF-8" />
    <title>Scholarships</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="p-4">
    <div class="container">
        <h2>Scholarships</h2>

        <div id="scholarship_list"></div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editScholarshipModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="update_scholarship_form" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Scholarship</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="edit_id" name="id">
                    <input type="hidden" id="edit_school_id" name="school_id" value="<?php echo $school_id; ?>">

                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-select" id="edit_type" name="type" required>
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount (<span id="amount_type_label">%</span>)</label>

                        <input type="number" class="form-control" id="edit_amount" name="amount" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea class="form-control" id="edit_reason" name="reason" required></textarea>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $('#edit_type').on('change', function() {
        if ($(this).val() === 'percentage') {
            $('#amount_type_label').text('%');
        } else {
            $('#amount_type_label').text('Amount');
        }
    });

    function loadScholarships() {
        $.ajax({
            url: 'ajax/load_scholarships.php',
            method: 'GET',
            success: function(data) {
                $('#scholarship_list').html(data);
            }
        });
    }

    loadScholarships();

    $(document).on('click', '.delete-btn', function() {
        if (!confirm('Are you sure?')) return;
        var id = $(this).data('id');
        $.post('ajax/delete_scholarship.php', {
            id: id
        }, function(response) {
            if (response.status === 'success') {
                loadScholarships();
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json');
    });

    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.getJSON('ajax/get_scholarship.php', {
            id: id
        }, function(data) {
            $('#edit_id').val(data.id);
            $('#edit_type').val(data.type);
            $('#edit_amount').val(data.amount);
            $('#edit_reason').val(data.reason);
            var editModal = new bootstrap.Modal(document.getElementById('editScholarshipModal'));
            editModal.show();
        });
    });

    $('#update_scholarship_form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.post('ajax/update_scholarship.php', formData, function(response) {
            if (response.status === 'success') {
                loadScholarships();
                bootstrap.Modal.getInstance(document.getElementById('editScholarshipModal')).hide();
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json');
    });
    </script>
</body>

</html>