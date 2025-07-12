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
    <meta charset="UTF-8" />
    <title>Faculty Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5">
        <h4 class="text-center mb-4">Faculty Registration</h4>
        <form id="facultyForm" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Campus</label>
                <input type="text" name="campus_id" id="campusSelect" class="form-control"
                    value="<?php echo $_SESSION['admin_id'];?>" disabled />
            </div>
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">CNIC/ID Number</label>
                <input type="text" name="cnic" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">Qualification</label>
                <input type="text" name="qualification" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">Subjects</label>
                <input type="text" name="subjects" class="form-control" placeholder="e.g., Math, Physics" />
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" />
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">Address (Optional)</label>
                <textarea name="address" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Date of Joining</label>
                <input type="date" name="joining_date" class="form-control" />
            </div>
            <div class="mb-3">
                <label class="form-label">Employment Type</label>
                <select name="employment_type" class="form-select">
                    <option value="">-- Select Type --</option>
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Contractual">Contractual</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Schedule Preference</label>
                <select name="schedule_preference" class="form-select">
                    <option value="">-- Select Schedule --</option>
                    <option value="Morning">Morning</option>
                    <option value="Evening">Evening</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Photo (Optional)</label>
                <input type="file" name="photo" class="form-control" accept="image/*" />
            </div>
            <button type="submit" class="btn btn-primary w-100">
                Register Faculty
            </button>
            <div id="facultyResponse" class="mt-3 text-center"></div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#facultyForm').on('submit', function(e) {
            e.preventDefault(); // Prevent page reload

            const formData = new FormData(this);

            $.ajax({
                url: 'ajax/register_faculty.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response); // now response is just plain text
                },
                error: function(xhr) {
                    alert('Error occurred: ' + xhr.responseText);
                }
            });

        });
    });
    </script>

</body>

</html>