<?php
// Start session and connect DB
session_start();
require_once 'admin/sass/db_config.php'; // Your DB connection

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $school_name         = $_POST['school_name'] ?? '';
    $school_type         = $_POST['school_type'] ?? '';
    $registration_number = $_POST['registration_number'] ?? '';
    $affiliation_board   = $_POST['affiliation_board'] ?? '';
    $school_email        = $_POST['school_email'] ?? '';
    $school_phone        = $_POST['school_phone'] ?? '';
    $school_website      = $_POST['school_website'] ?? '';
    $country             = $_POST['country'] ?? '';
    $state               = $_POST['state'] ?? '';
    $city                = $_POST['city'] ?? '';
    $address             = $_POST['address'] ?? '';
    $admin_phone         = $_POST['admin_phone'] ?? '';
    $admin_contact       = $_POST['admin_contact_person'] ?? '';
    $admin_email         = $_POST['admin_email'] ?? '';
    $password            = $_POST['password'] ?? '';

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Handle logo upload
    $logoFileName = null;
    if (!empty($_FILES['logo']['name'])) {
        $targetDir = "admin/uploads/logos/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $logoFileName = time() . "_" . basename($_FILES["logo"]["name"]);
        $targetFilePath = $targetDir . $logoFileName;
        move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFilePath);
    }

    // Insert into DB
    $stmt = $conn->prepare("
        INSERT INTO schools (
            school_name, school_type, registration_number, affiliation_board, 
            school_email, school_phone, school_website, country, state, city, address, 
            logo, admin_contact_person, admin_email, admin_phone, password, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param(
        "ssssssssssssssss",
        $school_name, $school_type, $registration_number, $affiliation_board,
        $school_email, $school_phone, $school_website, $country, $state, $city, $address,
        $logoFileName, $admin_contact, $admin_email, $admin_phone, $hashedPassword
    );

   if ($stmt->execute()) {
    header("Location: login.php");
    exit; // Always exit after header redirect
}
else {
        $errorMsg = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>School Registration - Lurniva</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <section class="section my-5">
        <div class="container">
            <div class="section-header text-center mb-4">
                <h1>Register Your School</h1>
                <p class="text-muted">Fill in the details below to register with Lurniva</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <!-- Alert Message -->
                            <?php if (!empty($successMsg)) : ?>
                            <div class="alert alert-success text-center"><?= $successMsg ?></div>
                            <?php elseif (!empty($errorMsg)) : ?>
                            <div class="alert alert-danger text-center"><?= $errorMsg ?></div>
                            <?php endif; ?>

                            <!-- Form -->
                            <form method="POST" enctype="multipart/form-data">
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
                                        <input type="text" class="form-control" name="address" required />
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
                                <small class="text-muted">Already registered? <a href="login.php">Login here</a></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>