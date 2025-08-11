<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>School Registration - Lurniva</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Optional: Custom styling -->
    <style>
    body {
        background-color: #f6f9fc;
    }

    .card {
        border: none;
    }

    .section-header h1 {
        font-size: 1.8rem;
        font-weight: 600;
    }

    .rounded-pill {
        border-radius: 50rem !important;
    }
    </style>
</head>

<body>
    <section class="section my-5">
        <div class="container">
            <div class="section-header text-center mb-4">
                <h1>Register Your School</h1>
                <p class="text-muted">
                    Fill in the details below to register with Lurniva
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <!-- Alert Message -->
                            <div id="responseMsg"></div>

                            <form id="schoolForm" enctype="multipart/form-data">
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
                                        <label class="form-label">Province / State</label>
                                        <input type="text" class="form-control" name="state" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" name="city" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="Addess" required />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Admin Phone</label>
                                        <input type="tel" class="form-control" name="admin_phone" required />
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
                                        <label class="form-label">School Logo</label>
                                        <input type="file" class="form-control" name="logo" accept="image/*" />
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary w-50 rounded-pill">
                                        Register School
                                    </button>
                                </div>
                            </form>

                            <div class="text-center mt-3">
                                <small class="text-muted">Already registered?
                                    <a href="login.php">Login here</a></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- jQuery + Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $("#schoolForm").on("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: "ajax/register_school.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                $("#responseMsg").php(
                    `<div class="alert alert-${response.type} text-center">${response.message}</div>`
                );
                if (response.type === "success") {
                    $("#schoolForm")[0].reset();
                }
            },
            error: function() {
                $("#responseMsg").php(
                    `<div class="alert alert-danger text-center">Server error. Please try again.</div>`
                );
            },
        });
    });
    </script>
</body>

</html>