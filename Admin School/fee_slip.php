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
    <title>Unsubmitted Fee Slips</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .fee-slip {
        max-width: 800px;
        margin: 30px auto;
        padding: 25px;
        border: 2px solid #343a40;
        background-color: #f8f9fa;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
        page-break-after: always;
    }

    .fee-slip-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .fee-slip-header img {
        max-height: 80px;
        margin-bottom: 10px;
    }

    .fee-slip-header h2 {
        margin: 0;
        font-weight: 700;
    }

    .fee-slip-section {
        margin-bottom: 15px;
    }

    .fee-line {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px dotted #ccc;
    }

    .fee-slip-total {
        font-size: 18px;
        font-weight: bold;
        text-align: right;
        color: #1d3557;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        #printArea,
        #printArea * {
            visibility: visible;
        }

        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        #print {
            display: none !important;
        }

        .fee-slip {
            page-break-after: always;
        }
    }
    </style>
</head>

<body>

    <div class="container mt-4">
        <h4>Filter Fee Slips</h4>
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Fee Period</label>
                <select id="feePeriodSelect" class="form-select">
                    <option value="">-- Select Period --</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Class</label>
                <select id="classSelect" class="form-select">
                    <option value="">-- Select Class --</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Student</label>
                <select id="studentSelect" class="form-select">
                    <option value="">-- Select Student --</option>
                </select>
            </div>
        </div>

        <button id="print" class="btn btn-success mb-3">🖨️ Print All Fee Slips</button>

        <div id="printArea">
            <div id="unsubmittedFeeSlips"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
        // Load periods
        $.get("ajax/get_fee_periods.php", function(data) {
            $('#feePeriodSelect').append(data);
        });

        // Load classes
        $.getJSON("ajax/get_classes.php", function(res) {
            if (res.status === 'success') {
                res.data.forEach(cls => {
                    $('#classSelect').append(`<option value="${cls}">${cls}</option>`);
                });
            }
        });

        // Load students
        $.get("ajax/get_students.php", function(response) {
            if (response.status === 'success') {
                let select = $('#studentSelect');
                select.html('<option value="">-- Select Student --</option>');
                response.data.forEach(s => {
                    select.append(
                        `<option value="${s.id}">${s.name} (${s.class} - Roll #${s.roll})</option>`
                        );
                });
            }
        });


        // Load slips on filter change
        $('#feePeriodSelect, #classSelect, #studentSelect').on('change', function() {
            let period = $('#feePeriodSelect').val();
            let cls = $('#classSelect').val();
            let student = $('#studentSelect').val();

            $.post("ajax/get_unsubmitted_fees.php", {
                period_id: period,
                class_name: cls,
                student_id: student
            }, function(response) {
                $('#unsubmittedFeeSlips').html(response);
            });
        });

        // Print
        document.getElementById('print').addEventListener('click', function() {
            window.print();
        });
    });
    </script>
</body>

</html>