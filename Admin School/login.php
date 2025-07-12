<?php
session_start();
require_once 'sass/db_config.php';

$message = '';
$message_type = '';

// Handle login form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $message = "Email and password are required.";
        $message_type = 'danger';
    } else {
        $stmt = $conn->prepare("SELECT id, school_name, admin_contact_person, password FROM schools WHERE school_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Login success
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['admin_contact_person'];
                $_SESSION['school_name'] = $user['school_name'];

                header("Location: dashboard.php");
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
    body {
        background-color: #f1f3f5;
    }

    .login-box {
        max-width: 400px;
        margin: 100px auto;
        background: #fff;
        padding: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    </style>
</head>

<body>
    <div class="login-box">
        <h4 class="text-center mb-4">Admin Login</h4>

        <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message_type ?> text-center"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Admin Email</label>
                <input type="email" class="form-control" name="email" required />
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required />
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>

</html>