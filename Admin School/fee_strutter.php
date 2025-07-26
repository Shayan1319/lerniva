<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: logout.php");
  exit;
}

// Fetch Fee Types for dropdown
require 'sass/db_config.php'; // your DB connection
$feeTypes = $conn->query("SELECT id, fee_name FROM fee_types WHERE school_id = " . $_SESSION['admin_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Add Class Fee Plan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <h3>Add Class Fee Plan</h3>
        <form id="classFeePlanForm">
            <input type="hidden" id="school_id" name="school_id" value="<?php echo $_SESSION['admin_id']; ?>" disabled>

            <div class="mb-3">
                <label class="form-label">Class</label>
                <select class="form-select" id="class_grade" name="class_grade" required>
                    <option value="">Loading classes...</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Frequency</label>
                <select class="form-select" id="frequency" name="frequency" required>
                    <option value="Monthly">Monthly</option>
                    <option value="Yearly">Yearly</option>
                    <option value="One Time">One Time</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Add Fee Type</label>
                <div class="input-group mb-2">
                    <select class="form-select" id="fee_type_id">
                        <option value="">Select Fee Type</option>
                        <?php while ($row = $feeTypes->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['fee_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <input type="number" class="form-control" id="rate" placeholder="Enter Rate">
                    <button type="button" class="btn btn-secondary" id="addFeeType">Add</button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Total Amount</label>
                <input type="number" class="form-control" id="total_amount" name="total_amount" readonly>
            </div>

            <table class="table table-bordered" id="feeTypeTable">
                <thead>
                    <tr>
                        <th>Fee Type</th>
                        <th>Rate</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <button type="submit" class="btn btn-primary">Save Class Fee Plan</button>
            <div id="response" class="mt-3"></div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
    // ✅ Load classes on page load via AJAX
    $(document).ready(function() {
        $.ajax({
            url: 'ajax/get_classes.php',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                const $classSelect = $("#class_grade");
                $classSelect.empty().append(`<option value="">Select Class</option>`);
                if (res.status === 'success') {
                    res.data.forEach(cls => {
                        $classSelect.append(`<option value="${cls}">${cls}</option>`);
                    });
                } else {
                    $classSelect.append(`<option value="">No classes found</option>`);
                }
            },
            error: function() {
                alert("Failed to load classes");
            }
        });
    });

    let feeItems = [];

    $("#addFeeType").click(function() {
        const feeTypeId = $("#fee_type_id").val();
        const feeTypeText = $("#fee_type_id option:selected").text();
        const rate = parseFloat($("#rate").val());

        if (!feeTypeId || !rate || rate <= 0) {
            alert("Please select fee type and enter valid rate");
            return;
        }

        feeItems.push({
            fee_type_id: feeTypeId,
            fee_name: feeTypeText,
            rate: rate
        });

        renderTable();
        updateTotal();
        $("#fee_type_id").val("");
        $("#rate").val("");
    });

    function renderTable() {
        const tbody = $("#feeTypeTable tbody");
        tbody.empty();
        feeItems.forEach((item, index) => {
            tbody.append(`<tr>
          <td>${item.fee_name}</td>
          <td>${item.rate}</td>
          <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">Delete</button>
          </td>
        </tr>`);
        });
    }

    function updateTotal() {
        const total = feeItems.reduce((sum, item) => sum + item.rate, 0);
        $("#total_amount").val(total);
    }

    function removeItem(index) {
        feeItems.splice(index, 1);
        renderTable();
        updateTotal();
    }

    $("#classFeePlanForm").on("submit", function(e) {
        e.preventDefault();

        if (feeItems.length === 0) {
            alert("Please add at least one fee type.");
            return;
        }

        const formData = {
            school_id: <?php echo $_SESSION['admin_id']; ?>,
            class_grade: $("#class_grade").val(),
            frequency: $("#frequency").val(),
            status: $("#status").val(),
            total_amount: $("#total_amount").val(),
            fee_items: feeItems
        };

        $.ajax({
            url: "ajax/insert_fee_structure.php",
            type: "POST",
            data: JSON.stringify(formData),
            contentType: "application/json",
            success: function(response) {
                $("#response").html(
                    `<div class="alert alert-${response.status === 'success' ? 'success' : 'danger'}">${response.message}</div>`
                );
                if (response.status === 'success') {
                    $("#classFeePlanForm")[0].reset();
                    feeItems = [];
                    renderTable();
                    updateTotal();
                }
            },
            error: function() {
                $("#response").html('<div class="alert alert-danger">Error saving data</div>');
            }
        });
    });
    </script>
</body>

</html>