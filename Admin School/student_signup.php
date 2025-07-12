<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Student Signup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Student Signup</h2>
        <div id="response" class="alert d-none"></div>
        <form id="signupForm">
            <div class="mb-3">
                <label for="school_id" class="form-label">Select School</label>
                <select class="form-select" id="school_id" name="school_id" required></select>
            </div>

            <div class="mb-3">
                <label for="parent_name" class="form-label">Parent Name</label>
                <input type="text" class="form-control" id="parent_name" name="parent_name" required />
            </div>

            <div class="mb-3">
                <label for="full_name" class="form-label">Student Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required />
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option value="">-- Select --</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" required />
            </div>

            <div class="mb-3">
                <label for="cnic_formb" class="form-label">CNIC/Form-B</label>
                <input type="text" class="form-control" id="cnic_formb" name="cnic_formb" required />
            </div>

            <div class="mb-3">
                <label for="class_grade" class="form-label">Class Grade</label>
                <input type="text" class="form-control" id="class_grade" name="class_grade" required />
            </div>

            <div class="mb-3">
                <label for="section" class="form-label">Section</label>
                <input type="text" class="form-control" id="section" name="section" required />
            </div>

            <div class="mb-3">
                <label for="roll_number" class="form-label">Roll Number (optional)</label>
                <input type="text" class="form-control" id="roll_number" name="roll_number" />
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address (optional)</label>
                <textarea class="form-control" id="address" name="address"></textarea>
            </div>

            <div class="mb-3">
                <label for="profile_photo" class="form-label">Profile Photo (optional)</label>
                <input type="file" class="form-control" id="profile_photo" accept="image/*" />
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required />
            </div>
            <div class="form-group">
                <label for="parent_email">Parent Email Address</label>
                <input type="email" name="parent_email" id="parent_email" class="form-control"
                    placeholder="Enter Parent's Email" required>
            </div>


            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required />
            </div>

            <button type="submit" id="save" class="btn btn-primary">Register</button>

        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $.ajax({
            url: 'ajax/get_schools.php', // <-- update this path
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var options = '<option value="">-- Select School --</option>';
                    response.data.forEach(function(school) {
                        options += '<option value="' + school.id + '">' +
                            school.school_name + ' | ' + school.registration_number +
                            '</option>';
                    });
                    $('#school_id').html(options);
                } else {
                    alert('Failed to load schools.');
                }
            },
            error: function() {
                alert('Error connecting to server.');
            }
        });
        $("#signupForm").submit(function(e) {
            e.preventDefault();

            var school_id = $('#school_id').val();
            var parent_name = $('#parent_name').val();
            var full_name = $('#full_name').val();
            var gender = $('#gender').val();
            var dob = $('#dob').val();
            var cnic_formb = $('#cnic_formb').val();
            var class_grade = $('#class_grade').val();
            var section = $('#section').val();
            var roll_number = $('#roll_number').val();
            var address = $('#address').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var parent_email = $('#parent_email').val();
            var password = $('#password').val();

            // If you want to send file, handle that separately. Here we'll skip the photo for simplicity.
            $.ajax({
                url: 'ajax/student_signup.php',
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json', // <-- ADD THIS
                data: JSON.stringify({
                    school_id: school_id,
                    parent_name: parent_name,
                    full_name: full_name,
                    gender: gender,
                    dob: dob,
                    cnic_formb: cnic_formb,
                    class_grade: class_grade,
                    section: section,
                    roll_number: roll_number,
                    address: address,
                    email: email,
                    parent_email: parent_email,
                    phone: phone,
                    password: password,
                }),
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {
                        alert('Student registered successfully.');
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Something went wrong.');
                    console.log(xhr
                        .responseText); // <-- Add this to see PHP errors in console!
                }
            });

        });


    });
    </script>


</body>

</html>