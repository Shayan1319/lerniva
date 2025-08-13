<?php
session_start();
require_once 'admin/sass/db_config.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $message = "Email and password are required.";
        $message_type = 'danger';
    } else {
        // First: check in schools table
        $stmt = $conn->prepare("
            SELECT id, school_name, admin_contact_person, password 
            FROM schools 
            WHERE school_email = ?
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Store admin session
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['admin_contact_person'];
                $_SESSION['school_name'] = $user['school_name'];

                header("Location: admin/index.php");
                exit;
            } else {
                $message = "Invalid password.";
                $message_type = 'danger';
            }
        } else {
            // If not found in schools, check faculty
            $stmt = $conn->prepare("
                SELECT id, campus_id, full_name, email, password, photo 
                FROM faculty 
                WHERE email = ?
            ");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $faculty = $result->fetch_assoc();
                if (password_verify($password, $faculty['password'])) {
                    // Store faculty session
                    $_SESSION['admin_id'] = $faculty['id'];
                    $_SESSION['admin_name'] = $faculty['full_name'];
                    $_SESSION['campus_id'] = $faculty['campus_id'];
                    $_SESSION['faculty_photo'] = $faculty['photo'];

                    header("Location: Faculty Dashboard/index.php");
                    exit;
                } else {
                    $message = "Invalid password.";
                    $message_type = 'danger';
                }
            } else {
                $message = "No account found with that email.";
                $message_type = 'danger';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login - Lurniva</title>
    <link rel="stylesheet" href="admin/assets/css/app.min.css" />
    <link rel="stylesheet" href="admin/assets/css/style.css" />
    <link rel="stylesheet" href="admin/assets/css/components.css" />
    <link rel="stylesheet" href="admin/assets/css/custom.css" />
    <link rel="shortcut icon" type="image/x-icon" href="admin/assets/img/T Logo.png" />
    <style>
    body,
    html {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: "Segoe UI", sans-serif;
    }

    .login-container {
        display: flex;
        height: 100vh;
    }

    .left-section {
        background: linear-gradient(#1da1f2, #794bc4, #17c3b2);
        width: 50%;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding-top: 60px;
    }

    .right-section {
        background-color: #ffffff;
        width: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .right-section img {
        width: 200px;
        height: auto;
    }

    .login-box {
        width: 100%;
        max-width: 400px;
    }

    .card {
        border: none;
    }

    .forgot-password {
        margin-top: 10px;
        font-size: 0.875rem;
    }

    .create-account {
        margin-top: 15px;
        text-align: center;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .login-container {
            flex-direction: column;
        }

        .left-section,
        .right-section {
            width: 100%;
            height: 50%;
        }
    }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Left Section (Form) -->
        <div class="left-section">
            <div class="login-box">
                <div class="card card-primary">
                    <div class="card-header text-white">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body bg-white">
                        <?php if (!empty($message)): ?>
                        <div class="alert alert-<?= $message_type ?>">
                            <?= $message ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                            class="needs-validation" novalidate>
                            <div class="form-group">
                                <label for="email" class="text-dark">Email</label>
                                <input id="email" type="email" class="form-control" name="email" required autofocus />
                                <div class="invalid-feedback">Please fill in your email</div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="text-dark">Password</label>
                                <input id="password" type="password" class="form-control" name="password" required />
                                <div class="invalid-feedback">
                                    Please fill in your password
                                </div>
                                <div class="forgot-password">
                                    <a href="auth-forgot-password.php" class="text-small">Forgot Password?</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="remember" class="custom-control-input"
                                        id="remember-me" />
                                    <label class="custom-control-label text-dark" for="remember-me">Remember Me</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-block" style="
                      background: linear-gradient(#1da1f2, #794bc4, #17c3b2);
                      border: none;
                      color: white;
                    ">
                                    Login
                                </button>
                            </div>

                            <div class="create-account text-dark">
                                Don't have an account?
                                <a href="auth-register.php">Sign Up!</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section (Logo) -->
        <div class="right-section">
            <img src="admin/assets/img/Final Logo.jpg" alt="Logo" style="width: 400px; max-width: 100%; height: auto" />
        </div>
    </div>

    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>