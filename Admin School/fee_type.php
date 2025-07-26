<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: logout.php");
  exit;
}
$school_id = $_SESSION['admin_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Fee Types</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="p-4">
    <div class="container">
        <h3>Add New Fee Type</h3>

        <form id="feeTypeForm">
            <input type="hidden" id="school_id" name="school_id" value="<?php echo $school_id; ?>">
            <input type="hidden" id="edit_id" name="edit_id">

            <div class="mb-3">
                <label for="fee_name" class="form-label">Fee Name</label>
                <input type="text" class="form-control" id="fee_name" name="fee_name" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Fee Type</button>
        </form>

        <div id="response" class="mt-3"></div>

        <hr>

        <h4>Fee Types</h4>
        <table class="table table-bordered" id="feeTypeTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fee Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
    function loadFeeTypes() {
        $.get("ajax/get_fee_types.php", function(data) {
            var rows = "";
            data.forEach(function(fee) {
                rows += "<tr>" +
                    "<td>" + fee.id + "</td>" +
                    "<td>" + fee.fee_name + "</td>" +
                    "<td>" + fee.status + "</td>" +
                    "<td>" +
                    "<button class='btn btn-sm btn-info editBtn' data-id='" + fee.id + "' data-name='" +
                    fee.fee_name + "' data-status='" + fee.status + "'>Edit</button> " +
                    "<button class='btn btn-sm btn-danger deleteBtn' data-id='" + fee.id +
                    "'>Delete</button>" +
                    "</td>" +
                    "</tr>";
            });
            $("#feeTypeTable tbody").html(rows);
        }, "json");
    }

    loadFeeTypes();

    $("#feeTypeForm").on("submit", function(e) {
        e.preventDefault();
        var formData = {
            school_id: $("#school_id").val(),
            fee_name: $("#fee_name").val(),
            status: $("#status").val(),
            id: $("#edit_id").val()
        };

        $.ajax({
            url: "ajax/save_fee_type.php",
            type: "POST",
            data: JSON.stringify(formData),
            contentType: "application/json",
            success: function(response) {
                $("#response").html(
                    '<div class="alert alert-' + (response.status === 'success' ? 'success' :
                        'danger') + '">' +
                    response.message +
                    '</div>'
                );
                if (response.status === 'success') {
                    $("#feeTypeForm")[0].reset();
                    $("#edit_id").val('');
                    loadFeeTypes();
                }
            }
        });
    });

    $(document).on("click", ".editBtn", function() {
        $("#edit_id").val($(this).data("id"));
        $("#fee_name").val($(this).data("name"));
        $("#status").val($(this).data("status"));
    });

    $(document).on("click", ".deleteBtn", function() {
        if (confirm("Are you sure to delete this fee type?")) {
            var id = $(this).data("id");
            $.post("ajax/delete_fee_type.php", {
                id: id
            }, function(response) {
                $("#response").html(
                    '<div class="alert alert-' + (response.status === 'success' ? 'success' :
                        'danger') + '">' +
                    response.message +
                    '</div>'
                );
                if (response.status === 'success') {
                    loadFeeTypes();
                }
            }, "json");
        }
    });
    </script>
</body>

</html>