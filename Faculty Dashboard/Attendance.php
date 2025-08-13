<?php
require_once 'assets/php/header.php';
require_once 'sass/db_config.php';

if (!isset($_SESSION['admin_id'])) {
    echo "Unauthorized";
    exit;
}

$school_id = $_SESSION['admin_id'];
?>
<style>
#attendance {
    padding-left: 20px;
    position: relative;
    color: #6777ef !important;
    background-color: #f0f3ff;
}

#attendance i {
    color: #6777ef !important;
}
</style>

<div class="main-content">
    <section class="section">
        <div class="section-body container">

            <form id="attendanceForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="attendanceDate">Date</label>
                        <input type="date" class="form-control" id="attendanceDate" name="attendanceDate"
                            value="<?= date('Y-m-d') ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="classSelect">Select Class</label>
                        <select id="classSelect" name="classSelect" class="form-control">
                            <option value="">-- Select Class --</option>
                        </select>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4><i data-feather="check-square" class="me-2"></i>Mark Student Attendance</h4>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="bg-primary text-white text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Students will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-success">
                            <i data-feather="send"></i> Submit Attendance
                        </button>
                    </div>
                </div>
            </form>

            <div id="message"></div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function loadAvailableClasses() {
    $.get("ajax/get_teacher_classes.php", function(response) {
        $("#classSelect").html(response);
    });
}

$(document).ready(function() {
    // Initial load
    loadAvailableClasses();

    // Load students when class changes
    $("#classSelect").change(function() {
        let classId = $(this).val();
        let date = $("#attendanceDate").val();

        if (classId) {
            $.get("ajax/get_class_students.php", {
                class_id: classId,
                date: date
            }, function(response) {
                $("table tbody").html(response);
            });
        } else {
            $("table tbody").html("");
        }
    });

    // Handle attendance save
    $('#attendanceForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: 'ajax/save_attendance.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'text',
            beforeSend: function() {
                $('#message').html(
                    `<div class="alert alert-info mt-3">Saving attendance...</div>`);
            },
            success: function(response) {
                $('#message').html(
                    `<div class="alert alert-success mt-3">${response}</div>`);
                // Reload class list after save
                loadAvailableClasses();
                // Clear students table
                $('tbody').empty();
            },
            error: function(xhr, status, error) {
                $('#message').html(
                    `<div class="alert alert-danger mt-3">Something went wrong: ${error}</div>`
                );
            }
        });
    });
});
</script>

<?php require_once 'assets/php/footer.php'; ?>