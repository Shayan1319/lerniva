<?php
// Enable all error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'sass/db_config.php';

if (!isset($_SESSION['school_id'])) {
    header("Location: school_login.php");
    exit;
}

$school_id = $_SESSION['school_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Create School Timetable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5">
        <h3>Create School Timetable</h3>
        <form id="timetableForm">
            <!-- Assembly & Leave Time -->
            <div class="row mb-3">
                <div class="col">
                    <label>Assembly Time</label>
                    <input type="time" name="assembly_time" class="form-control" required />
                </div>
                <div class="col">
                    <label>Leave Time</label>
                    <input type="time" name="leave_time" class="form-control" required />
                </div>
            </div>

            <!-- Finalize Switch -->
            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" id="finalizeSwitch" name="is_finalized" value="1">
                <label class="form-check-label" for="finalizeSwitch">Finalize Timetable (Lock from editing)</label>
            </div>

            <!-- Half-Day Settings -->
            <div class="border p-3 mb-4 rounded bg-light">
                <h5>Optional Half-Day Settings</h5>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label>Weekday</label>
                        <select id="halfDayWeekday" class="form-control">
                            <option value="">-- Select --</option>
                            <option>Monday</option>
                            <option>Tuesday</option>
                            <option>Wednesday</option>
                            <option>Thursday</option>
                            <option>Friday</option>
                            <option>Saturday</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Assembly</label>
                        <input type="time" id="halfDayAssembly" class="form-control" />
                    </div>
                    <div class="col-md-2">
                        <label>Leave</label>
                        <input type="time" id="halfDayLeave" class="form-control" />
                    </div>
                    <div class="col-md-2">
                        <label>Periods</label>
                        <input type="number" id="halfDayPeriods" class="form-control" min="1" />
                    </div>
                    <div class="col-md-3 d-grid mt-4">
                        <button type="button" id="addHalfDay" class="btn btn-warning">Add Half-Day</button>
                    </div>
                </div>
                <div id="halfDayPreview" class="mt-2"></div>
                <input type="hidden" name="half_day_config" id="half_day_config" />
            </div>

            <!-- Class Sections -->
            <div id="class-sections"></div>
            <button type="button" id="addClassSection" class="btn btn-secondary mb-3">Add Class/Section</button>

            <!-- Preview & Save -->
            <button type="button" id="previewBtn" class="btn btn-info mb-3">Preview Timetable</button>
            <div id="previewContainer" class="mb-4"></div>
            <div id="response" class="mb-3"></div>
            <button type="submit" class="btn btn-primary">Save Timetable</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    let classIndex = 0;
    let teacherOptionsHtml = '<option value="">Loading...</option>';
    let halfDayMap = {};
    const weekdays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    $(document).ready(function() {
        // Load teachers once
        $.get("ajax/get_teachers.php", function(data) {
            const teachers = typeof data === "object" ? data : JSON.parse(data);
            teacherOptionsHtml = `<option value="">Select Teacher</option>`;
            teachers.forEach(t => {
                teacherOptionsHtml += `<option value="${t.id}">${t.full_name}</option>`;
            });
            $('select.teacher-dropdown').each(function() {
                $(this).html(teacherOptionsHtml);
            });
        });

        // Add new class section
        $('#addClassSection').click(function() {
            $('#class-sections').append(getClassSectionHtml(classIndex));
            classIndex++;
        });

        // Handle half-day config
        $("#addHalfDay").click(function() {
            const day = $("#halfDayWeekday").val();
            const assembly = $("#halfDayAssembly").val();
            const leave = $("#halfDayLeave").val();
            const periods = $("#halfDayPeriods").val();

            if (!day || !assembly || !leave || !periods) {
                alert("Please fill all half-day fields.");
                return;
            }

            halfDayMap[day] = {
                is_half_day: true,
                assembly_time: assembly,
                leave_time: leave,
                total_periods: parseInt(periods)
            };

            $("#half_day_config").val(JSON.stringify(halfDayMap));

            let html = "<ul class='mb-0'>";
            for (let d in halfDayMap) {
                html +=
                    `<li><strong>${d}</strong>: ${halfDayMap[d].assembly_time} - ${halfDayMap[d].leave_time}, Periods: ${halfDayMap[d].total_periods}</li>`;
            }
            html += "</ul>";
            $("#halfDayPreview").html(html);
        });

        // Generate periods on period count change
        $(document).on('input', '.period-count', function() {
            const index = $(this).data('index');
            const count = parseInt($(this).val());
            const container = $(`#periods-${index}`);
            container.html('');
            for (let i = 1; i <= count; i++) {
                container.append(getPeriodHtml(index, i));
            }
        });

        // Handle preview
        $("#previewBtn").on("click", function() {
            generatePreview();
        });

        // Form submission
        $("#timetableForm").on("submit", function(e) {
            e.preventDefault();
            if (!validateTimetable()) return;

            const formData = new FormData(this);

        });
    });

    // ---- Helper Functions ----

    function getClassSectionHtml(index) {
        return `
    <div class="border p-3 mb-4 class-block bg-light rounded" data-index="${index}">
        <div class="row">
            <div class="col">
                <label>Class</label>
                <input type="text" name="class_name[${index}]" class="form-control" required />
            </div>
            <div class="col">
                <label>Section</label>
                <input type="text" name="section[${index}]" class="form-control" required />
            </div>
            <div class="col">
                <label>Number of Periods</label>
                <input type="number" name="total_periods[${index}]" class="form-control period-count" data-index="${index}" required min="1" />
            </div>
        </div>
        <div class="periods mt-3" id="periods-${index}"></div>
    </div>`;
    }

    function getPeriodHtml(classIndex, periodNumber) {
        return `
    <div class="row mb-2">
        <div class="col"><label>Period ${periodNumber} Name</label><input type="text" name="period_name[${classIndex}][]" class="form-control" required /></div>
        <div class="col"><label>Start</label><input type="time" name="start_time[${classIndex}][]" class="form-control" required /></div>
        <div class="col"><label>End</label><input type="time" name="end_time[${classIndex}][]" class="form-control" required /></div>
        <div class="col">
            <label>Type</label>
            <select name="period_type[${classIndex}][]" class="form-control">
                <option value="Normal">Normal</option>
                <option value="Lab">Lab</option>
                <option value="Break">Break</option>
                <option value="Sports">Sports</option>
                <option value="Library">Library</option>
            </select>
        </div>
        <div class="col">
            <label>Teacher</label>
            <select name="teacher_id[${classIndex}][]" class="form-control teacher-dropdown" required>${teacherOptionsHtml}</select>
        </div>
        <div class="col d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_break[${classIndex}][]" value="1">
                <label class="form-check-label">Break</label>
            </div>
        </div>
    </div>`;
    }

    function validateTimetable() {
        let isValid = true;
        let errorMsg = "";

        $(".class-block").each(function() {
            const index = $(this).data("index");
            const periods = [];

            $(this).find(".row.mb-2").each(function(pIndex) {
                const start = $(this).find(`input[name='start_time[${index}][]']`).val();
                const end = $(this).find(`input[name='end_time[${index}][]']`).val();

                if (start >= end) {
                    isValid = false;
                    errorMsg =
                        `Class ${index + 1}, Period ${pIndex + 1}: Start time must be before end time.`;
                    return false;
                }
                periods.push({
                    start,
                    end
                });
            });

            if (isValid) {
                periods.sort((a, b) => a.start.localeCompare(b.start));
                for (let i = 0; i < periods.length - 1; i++) {
                    if (periods[i + 1].start < periods[i].end) {
                        isValid = false;
                        errorMsg = `Class ${index + 1}: Periods ${i + 1} and ${i + 2} are overlapping.`;
                        break;
                    }
                }
            }

            if (!isValid) return false;
        });

        if (!isValid) {
            $("#response").html(`<div class="alert alert-danger">${errorMsg}</div>`);
        }

        return isValid;
    }

    function generatePreview() {
        let previewHTML = "<h5>Timetable Preview</h5><div class='table-responsive'>";

        $(".class-block").each(function() {
            const index = $(this).data("index");
            const className = $(this).find(`input[name='class_name[${index}]']`).val();
            const section = $(this).find(`input[name='section[${index}]']`).val();
            const periodData = [];

            $(this).find(".row.mb-2").each(function() {
                periodData.push({
                    pname: $(this).find(`input[name='period_name[${index}][]']`).val(),
                    start: $(this).find(`input[name='start_time[${index}][]']`).val(),
                    end: $(this).find(`input[name='end_time[${index}][]']`).val(),
                    type: $(this).find(`select[name='period_type[${index}][]'] option:selected`)
                        .val(),
                    teacher: $(this).find(
                        `select[name='teacher_id[${index}][]'] option:selected`).text(),
                    isBreak: $(this).find(`input[name='is_break[${index}][]']`).is(":checked")
                });
            });

            previewHTML += `<h6 class="mt-4">${className} - Section ${section}</h6>`;
            previewHTML += `<table class="table table-bordered text-center"><thead><tr><th>Day</th>`;
            for (let i = 0; i < periodData.length; i++) {
                previewHTML += `<th>Period ${i + 1}</th>`;
            }
            previewHTML += `</tr></thead><tbody>`;

            weekdays.forEach(day => {
                const isHalf = halfDayMap[day];
                const maxPeriods = isHalf ? isHalf.total_periods : periodData.length;

                previewHTML += `<tr><td><strong>${day}</strong></td>`;
                for (let i = 0; i < periodData.length; i++) {
                    if (i >= maxPeriods) {
                        previewHTML += `<td class="text-muted">-</td>`;
                    } else {
                        const pd = periodData[i];
                        const bg = pd.isBreak ? 'table-warning' : '';
                        previewHTML += `<td class="${bg}">
                        <div><strong>${pd.pname || '-'}</strong></div>
                        <div>${pd.type || '-'}</div>
                        <div>${pd.start || '-'} - ${pd.end || '-'}</div>
                        <div>${pd.teacher || '-'}</div>
                    </td>`;
                    }
                }
                previewHTML += `</tr>`;
            });

            previewHTML += `</tbody></table>`;
        });

        if (Object.keys(halfDayMap).length > 0) {
            previewHTML += `<h5 class="mt-4">Half-Day Configuration</h5><table class="table table-sm table-bordered">
            <thead><tr><th>Weekday</th><th>Assembly</th><th>Leave</th><th>Periods</th></tr></thead><tbody>`;
            for (let d in halfDayMap) {
                previewHTML +=
                    `<tr><td>${d}</td><td>${halfDayMap[d].assembly_time}</td><td>${halfDayMap[d].leave_time}</td><td>${halfDayMap[d].total_periods}</td></tr>`;
            }
            previewHTML += `</tbody></table>`;
        }

        previewHTML += "</div>";
        $("#previewContainer").html(previewHTML);
    }
    </script>
</body>

</html>