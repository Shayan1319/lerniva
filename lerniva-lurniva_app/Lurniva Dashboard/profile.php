<?php require_once 'assets/php/header.php'; ?>

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row mt-sm-4">

                <!-- Left column: Admin info summary -->
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card author-box">
                        <div class="card-body text-center">
                            <img id="adminProfileImage" src="assets/img/users/user-1.png" alt="Profile"
                                class="rounded-circle author-box-picture"
                                style="width:120px; height:120px; object-fit:cover;">
                            <h4 id="adminFullName" class="mt-2"></h4>
                            <p id="adminUsername" class="text-muted"></p>
                            <p id="adminEmailDisplay" class="text-muted"></p>
                            <p><strong>Phone:</strong> <span id="adminPhone"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Right column: Tabs -->
                <div class="col-12 col-md-12 col-lg-8">
                    <div class="card">
                        <div class="padding-20">
                            <ul class="nav nav-tabs" id="adminTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="about-tab" data-toggle="tab" href="#about"
                                        role="tab">About</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings"
                                        role="tab">Edit Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="password-tab" data-toggle="tab" href="#password"
                                        role="tab">Change Password</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="email-tab" data-toggle="tab" href="#Change_email"
                                        role="tab">Change
                                        Email</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="image-tab" data-toggle="tab" href="#image"
                                        role="tab">Profile Image</a>
                                </li>
                            </ul>

                            <div class="tab-content tab-bordered" id="adminTabContent">

                                <!-- About Tab -->
                                <div class="tab-pane fade show active" id="about" role="tabpanel"
                                    aria-labelledby="about-tab">
                                    <h5>Admin Overview</h5>
                                    <p id="adminOverview"></p>
                                </div>

                                <!-- Edit Profile Tab -->
                                <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                    <form id="adminProfileForm" class="needs-validation" novalidate>
                                        <input type="hidden" id="adminId" name="admin_id">
                                        <div class="form-group">
                                            <label for="full_name">Full Name</label>
                                            <input type="text" id="full_name" name="full_name" class="form-control"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" id="username" name="username" class="form-control"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" id="email" name="email" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" id="phone" name="phone" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <input type="text" id="role" name="role" class="form-control" readonly>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Password Tab -->
                                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                    <form id="adminPasswordForm" class="needs-validation" novalidate>
                                        <div class="form-group">
                                            <label for="current_password">Current Password</label>
                                            <input type="password" id="current_password" name="current_password"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_password">New Password</label>
                                            <input type="password" id="new_password" name="new_password"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm New Password</label>
                                            <input type="password" id="confirm_password" name="confirm_password"
                                                class="form-control" required>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit" class="btn btn-primary">Update Password</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Email Update Tab -->
                                <div class="tab-pane fade" id="Change_email" role="tabpanel"
                                    aria-labelledby="email-tab">
                                    <form id="adminEmailForm" class="needs-validation" novalidate>
                                        <div class="form-group">
                                            <label for="new_email">New Email</label>
                                            <input type="email" id="new_email" name="new_email" class="form-control"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" id="sendEmailOtpBtn" class="btn btn-secondary">Send
                                                OTP</button>
                                        </div>

                                        <div class="form-group" id="otpSection" style="display:none;">
                                            <label for="otp_input">Enter OTP</label>
                                            <input type="text" id="otp_input" name="otp_input" class="form-control"
                                                placeholder="Enter OTP">
                                            <button type="button" id="verifyOtpBtn" class="btn btn-primary mt-2">Verify
                                                & Update Email</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Profile Image Tab -->
                                <div class="tab-pane fade" id="image" role="tabpanel" aria-labelledby="image-tab">
                                    <form id="adminImageForm" class="needs-validation" novalidate>
                                        <div class="form-group">
                                            <label for="admin_image">Profile Image</label>
                                            <input type="file" id="admin_image" name="admin_image" accept="image/*"
                                                class="form-control-file">
                                            <img id="adminImagePreview" src="assets/img/users/user-1.png" alt="Preview"
                                                style="margin-top:10px; max-height:100px;">
                                        </div>
                                        <button type="button" id="uploadAdminImageBtn" class="btn btn-secondary">Upload
                                            Image</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {

    function loadAdminProfile() {
        $.ajax({
            url: 'ajax/get_admin_profile.php',
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let d = res.data;
                    $('#adminId').val(d.id);
                    $('#full_name').val(d.full_name);
                    $('#username').val(d.username);
                    $('#email').val(d.email);
                    $('#phone').val(d.phone);
                    $('#role').val(d.role);

                    $('#adminFullName').text(d.full_name);
                    $('#adminUsername').text(d.username);
                    $('#adminEmailDisplay').text(d.email);
                    $('#adminPhone').text(d.phone);
                    $('#adminProfileImage').attr('src', d.profile_image ? 'uploads/admins/' + d
                        .profile_image : 'assets/img/users/user-1.png');
                    $('#adminImagePreview').attr('src', d.profile_image ? 'uploads/admins/' + d
                        .profile_image : 'assets/img/users/user-1.png');
                    $('#adminOverview').text(
                        `Admin "${d.full_name}" has username "${d.username}" and role "${d.role}".`
                    );
                } else {
                    alert(res.message || 'Failed to load profile.');
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    }

    loadAdminProfile();

    // Profile update
    $('#adminProfileForm').submit(function(e) {
        e.preventDefault();
        $.post('ajax/update_admin_profile.php', $(this).serialize(), function(res) {
            if (res.status === 'success') {
                alert('Profile updated successfully');
                loadAdminProfile();
            } else {
                alert(res.message || 'Failed to update profile');
            }
        }, 'json');
    });

    // Password update
    $('#adminPasswordForm').submit(function(e) {
        e.preventDefault();
        const current = $('#current_password').val();
        const newPass = $('#new_password').val();
        const confirmPass = $('#confirm_password').val();
        if (newPass !== confirmPass) {
            alert('Passwords do not match');
            return;
        }

        $.post('ajax/update_admin_password.php', {
            current_password: current,
            new_password: newPass
        }, function(res) {
            if (res.status === 'success') {
                alert('Password updated');
                $('#adminPasswordForm')[0].reset();
            } else {
                alert(res.message || 'Failed to update password');
            }
        }, 'json');
    });

    // Send OTP
    $('#sendEmailOtpBtn').click(function() {
        let email = $('#new_email').val();
        if (!email) {
            alert('Enter email');
            return;
        }
        $.post('ajax/send_email_otp.php', {
            email: email
        }, function(res) {
            if (res.status === 'success') {
                alert('OTP sent to ' + email);
                $('#otpSection').show();
            } else {
                alert(res.message || 'Failed to send OTP');
            }
        }, 'json');
    });

    // Verify OTP and update email
    $('#verifyOtpBtn').click(function() {
        let email = $('#new_email').val();
        let otp = $('#otp_input').val();
        if (!otp) {
            alert('Enter OTP');
            return;
        }
        $.post('ajax/update_admin_email.php', {
            email: email,
            otp: otp
        }, function(res) {
            if (res.status === 'success') {
                alert('Email updated successfully');
                $('#otpSection').hide();
                $('#otp_input').val('');
                loadAdminProfile();
            } else {
                alert(res.message || 'Invalid OTP');
            }
        }, 'json');
    });

    // Image upload
    $('#uploadAdminImageBtn').click(function() {
        let fileInput = $('#admin_image')[0];
        if (fileInput.files.length === 0) {
            alert('Select an image');
            return;
        }
        let formData = new FormData();
        formData.append('admin_image', fileInput.files[0]);
        formData.append('admin_id', $('#adminId').val());

        $.ajax({
            url: 'ajax/upload_admin_image.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    alert('Image uploaded successfully');
                    $('#adminProfileImage').attr('src', res.image_path + '?t=' + new Date()
                        .getTime());
                    $('#adminImagePreview').attr('src', res.image_path + '?t=' + new Date()
                        .getTime());
                } else {
                    alert(res.message || 'Failed to upload image');
                }
            }
        });
    });

});
</script>

<?php require_once 'assets/php/footer.php'; ?>