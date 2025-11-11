<?php require_once 'assets/php/header.php';
include_once('sass/db_config.php');

?>
<style>
#timetable {
    padding-left: 20px;
    color: #6777ef !important;
    background-color: #f0f3ff;
}

#timetable ul {
    display: block !important;
}

#seeTT {
    color: #000;
}
</style>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>All Timetables</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Timetable Overview</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="timetable-container">
                        <!-- AJAX content will load here -->
                    </div>
                </div>
                <div class="card-footer text-right">
                    <!-- Optional Pagination -->
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Update Timetable Modal -->
<div class="modal fade" id="updateTimetableModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="updateModalLabel">Update Class Timetable</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="updateTimetableBody" class="p-3">
                    <p class="text-center text-muted">Loading timetable details...</p>
                </div>
                <button class="btn btn-outline-primary mt-3" id="addPeriodBtn">➕ Add New Period</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="saveTimetableChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
let fullTimetableData = [];

$(document).ready(function() {
    loadTimetables();
});

function loadTimetables() {
    $.ajax({
        url: 'ajax/view_all_time_data.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            fullTimetableData = data;

            if (!data.length) {
                $('#timetable-container').html(
                    '<div class="alert alert-warning">No timetables found.</div>');
                return;
            }

            let html = '<table class="table table-bordered table-md">';
            html +=
                '<thead><tr><th>#</th><th>Class</th><th>Section</th><th>Max Periods</th><th>Actions</th></tr></thead><tbody>';

            data.forEach((classBlock, index) => {
                html += `<tr>
                    <td>${index + 1}</td>
                    <td>${classBlock.class_name}</td>
                    <td>${classBlock.section}</td>
                    <td>${classBlock.max_periods}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="showTimetableDetails(${index})">Detail</button>
                        <button class="btn btn-warning btn-sm ms-1" onclick="openUpdateModal(${classBlock.id})">Update</button>
                        <button class="btn btn-danger btn-sm ms-1" onclick="deleteTimetable(${classBlock.id})">Delete</button>
                    </td>
                </tr>`;
            });

            html += '</tbody></table>';
            $('#timetable-container').html(html);
        },
        error: function() {
            $('#timetable-container').html(
                '<div class="alert alert-danger">Failed to load timetables.</div>');
        }
    });
}

function showTimetableDetails(index) {
    const classBlock = fullTimetableData[index];

    let html = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>${classBlock.class_name} - ${classBlock.section}</h5>
            <button class="btn btn-secondary btn-sm" onclick="loadTimetables()">← Back to All Timetables</button>
        </div>`;

    html += '<table class="table table-bordered"><thead><tr><th>Day</th>';

    for (let p = 1; p <= classBlock.max_periods; p++) {
        html += `<th>Period ${p}</th>`;
    }

    html += '</tr></thead><tbody>';

    classBlock.days.forEach(day => {
        html += `<tr><td><strong>${day.name}</strong></td>`;
        for (let p = 1; p <= classBlock.max_periods; p++) {
            if (day.periods.hasOwnProperty(p)) {
                const per = day.periods[p];
                html += `<td>
                    <div><strong>${per.period_name}</strong></div>
                    <div>${per.start_time} - ${per.end_time}</div>
                    <div><small>${per.teacher_name}</small></div>
                    <div><em class="text-muted">(${per.period_type})</em></div>
                </td>`;
            } else {
                html += '<td class="text-muted text-center">--</td>';
            }
        }
        html += '</tr>';
    });

    html += '</tbody></table>';
    $('#timetable-container').html(html);
}

function deleteTimetable(timetableId) {
    if (!confirm("Are you sure you want to delete this timetable?")) return;

    $.ajax({
        url: 'ajax/delete_timetable.php',
        method: 'POST',
        data: {
            timing_table_id: timetableId
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === "success") loadTimetables();
            else alert(response.message);
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            alert("Error deleting timetable: " + error);
        }
    });
}

let currentClassId = null; // global variable

function openUpdateModal(classId) {
    currentClassId = classId; // store globally
    $('#updateTimetableModal').modal('show');
    $('#updateTimetableBody').html('<p class="text-center text-muted">Loading timetable details...</p>');

    $.ajax({
        url: 'ajax/fetch_timetable_details.php',
        method: 'GET',
        data: {
            class_id: classId
        },
        dataType: 'json',
        success: function(periods) {
            if (!periods.length) {
                $('#updateTimetableBody').html(
                    '<div class="alert alert-info">No periods found. Add new below.</div>');
                return;
            }

            let html = '<table class="table table-bordered">';
            html +=
                '<thead><tr><th>#</th><th>Period Name</th><th>Start Time</th><th>End Time</th><th>Teacher</th><th>Type</th><th>Break?</th></tr></thead><tbody>';

            periods.forEach((p, i) => {
                html += `
                    <tr data-id="${p.id}">
                        <td>${i + 1}</td>
                        <td><input type="text" class="form-control" value="${p.period_name}"></td>
                        <td><input type="time" class="form-control" value="${p.start_time}"></td>
                        <td><input type="time" class="form-control" value="${p.end_time}"></td>
                        <td>
                            <select class="form-control teacher-dropdown" data-selected="${p.teacher_id || ''}">
                                <option>Loading...</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control period-type">
                                <option value="Normal" ${p.period_type === 'Normal' ? 'selected' : ''}>Normal</option>
                                <option value="Lab" ${p.period_type === 'Lab' ? 'selected' : ''}>Lab</option>
                                <option value="Break" ${p.period_type === 'Break' ? 'selected' : ''}>Break</option>
                                <option value="Sports" ${p.period_type === 'Sports' ? 'selected' : ''}>Sports</option>
                                <option value="Library" ${p.period_type === 'Library' ? 'selected' : ''}>Library</option>
                            </select>
                        </td>
                        <td><input type="checkbox" ${p.is_break == 1 ? 'checked' : ''}></td>
                    </tr>`;
            });

            html += '</tbody></table>';
            $('#updateTimetableBody').html(html);

            // Load teacher options dynamically
            loadTeacherOptions();
        },
        error: function(xhr) {
            $('#updateTimetableBody').html('<div class="alert alert-danger">Error loading timetable: ' + xhr
                .statusText + '</div>');
        }
    });

    // Add new row dynamically
    $('#addPeriodBtn').off('click').on('click', function() {
        const rowCount = $('#updateTimetableBody table tbody tr').length + 1;
        $('#updateTimetableBody table tbody').append(`
            <tr data-id="new">
                <td>${rowCount}</td>
                <td><input type="text" class="form-control" placeholder="New Period Name"></td>
                <td><input type="time" class="form-control"></td>
                <td><input type="time" class="form-control"></td>
                <td><select class="form-control teacher-dropdown"><option>Loading...</option></select></td>
                <td>
                    <select class="form-control period-type">
                        <option value="Normal">Normal</option>
                        <option value="Lab">Lab</option>
                        <option value="Break">Break</option>
                        <option value="Sports">Sports</option>
                        <option value="Library">Library</option>
                    </select>
                </td>
                <td><input type="checkbox"></td>
            </tr>`);
        loadTeacherOptions();
    });
}

// Load teachers into all dropdowns
function loadTeacherOptions() {
    $.ajax({
        url: 'ajax/get_teachers.php',
        method: 'GET',
        success: function(html) {
            $('.teacher-dropdown').each(function() {
                const selected = $(this).data('selected');
                $(this).html(html);
                if (selected) $(this).val(selected);
            });
        },
        error: function() {
            $('.teacher-dropdown').html('<option>Error loading teachers</option>');
        }
    });
}
$('#saveTimetableChanges').off('click').on('click', function() {
    if (!currentClassId) {
        alert('Invalid class ID.');
        return;
    }

    const periodsData = [];
    $('#updateTimetableBody table tbody tr').each(function() {
        const row = $(this);
        const id = row.data('id');
        const period_name = row.find('td:eq(1) input').val().trim();
        const start_time = row.find('td:eq(2) input').val();
        const end_time = row.find('td:eq(3) input').val();
        const teacher_id = parseInt(row.find('td:eq(4) select').val()) || 0;
        const period_type = row.find('td:eq(5) select').val();
        const is_break = row.find('td:eq(6) input').is(':checked') ? 1 : 0;

        // Basic validation
        if (!period_name) {
            alert('Period name cannot be empty');
            return false; // stop iteration
        }

        periodsData.push({
            id,
            period_name,
            start_time,
            end_time,
            teacher_id,
            period_type,
            is_break
        });
    });

    console.log('Sending periodsData:', periodsData);

    $.ajax({
        url: 'ajax/update_timetable.php',
        method: 'POST',
        data: {
            periods: JSON.stringify(periodsData),
            class_id: currentClassId
        },
        dataType: 'json',
        success: function(response) {
            console.log('AJAX response:', response);
            if (response.status === 'success') {
                alert('Timetable updated successfully!');
                $('#updateTimetableModal').modal('hide');
                loadTimetables();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            alert('AJAX error: ' + xhr.statusText);
        }
    });
});
</script>

<?php require_once 'assets/php/footer.php'; ?>