<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>School Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
    body {
        background-color: #f8f9fa;
    }

    .form-container {
        max-width: 800px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }
    </style>
</head>

<body>
    <div class="form-container">
        <h4 class="text-center mb-4">School Registration Form</h4>
        <form id="schoolRegistrationForm" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">School Name</label>
                    <input type="text" class="form-control" name="school_name" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">School Type</label>
                    <select class="form-select" name="school_type" required>
                        <option value="">Select Type</option>
                        <option value="Public">Public</option>
                        <option value="Private">Private</option>
                        <option value="Charter">Charter</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Registration Number</label>
                    <input type="text" class="form-control" name="registration_number" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Affiliation Board</label>
                    <input type="text" class="form-control" name="affiliation_board" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">School Email</label>
                    <input type="email" class="form-control" name="school_email" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">School Phone</label>
                    <input type="tel" class="form-control" name="school_phone" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">School Website</label>
                    <input type="url" class="form-control" name="school_website" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Country</label>
                    <input type="text" class="form-control" name="country" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Province/State</label>
                    <input type="text" class="form-control" name="state" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">City</label>
                    <input type="text" class="form-control" name="city" required />
                </div>
                <div class="col-md-12">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" rows="2" required></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">School Logo</label>
                    <input type="file" class="form-control" name="logo" accept="image/*" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Admin Contact Person</label>
                    <input type="text" class="form-control" name="admin_contact_person" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Admin Email</label>
                    <input type="email" class="form-control" name="admin_email" required />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Admin Phone</label>
                    <input type="tel" class="form-control" name="admin_phone" required />
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary w-50">
                    Register School
                </button>
            </div>
        </form>
        <div id="response" class="mt-3 text-center"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $("#schoolRegistrationForm").on("submit", function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: "ajax/register_school.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                alert(response);
                if (response.status === "success") {
                    $("#response").html(
                        '<div class="alert alert-success">' +
                        response.message +
                        "</div>"
                    );
                    $("#schoolRegistrationForm")[0].reset();
                } else {
                    $("#response").html(
                        '<div class="alert alert-danger">' + response.message + "</div>"
                    );
                }
            },
            error: function(xhr, status, error) {
                $("#response").html(
                    '<div class="alert alert-danger">An error occurred: ' +
                    error +
                    "</div>"
                );
            },
        });
    });
    </script>
</body>

</html>