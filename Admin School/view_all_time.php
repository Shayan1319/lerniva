<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View All Timetable</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>All Timetables</h2>
        <div id="timetable-container"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $.ajax({
            url: 'ajax/view_all_time_data.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.length === 0) {
                    $('#timetable-container').html(
                        '<div class="alert alert-warning">No timetables found.</div>');
                    return;
                }

                var html = '';

                data.forEach(function(classBlock) {
                    html += '<h4>' + classBlock.class_name + ' - ' + classBlock.section +
                        '</h4>';
                    html += '<table class="table table-bordered">';
                    html += '<thead><tr><th>Day</th>';

                    // Table header for period numbers
                    for (var p = 1; p <= classBlock.max_periods; p++) {
                        html += '<th>Period ' + p + '</th>';
                    }

                    html += '</tr></thead><tbody>';

                    // Rows per day
                    classBlock.days.forEach(function(day) {
                        html += '<tr>';
                        html += '<td>' + day.name + '</td>';

                        for (var p = 1; p <= classBlock.max_periods; p++) {
                            if (day.periods.hasOwnProperty(p)) {
                                var per = day.periods[p];
                                html += '<td>';
                                html += per.period_name + '<br>';
                                html += per.start_time + ' - ' + per.end_time +
                                    '<br>';
                                html += per.teacher_name + '<br>';
                                html += '(' + per.period_type + ')';
                                html += '</td>';
                            } else {
                                html += '<td>--</td>';
                            }
                        }

                        html += '</tr>';
                    });

                    html += '</tbody></table>';
                });

                $('#timetable-container').html(html);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('#timetable-container').html(
                    '<div class="alert alert-danger">Error loading timetable.</div>');
            }
        });
    });
    </script>
</body>

</html>