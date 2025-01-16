<?php
session_start();

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Hardcoded admin credentials (replace with database verification if required)
    $admin_username = "admin";
    $admin_password = "admin123";

    if ($username === $admin_username && $password === $admin_password) {
        // Store admin session
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php'); // Redirect to admin page
        exit;
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            margin-top: 100px;
            max-width: 400px;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container mx-auto">
            <h2 class="text-center text-danger">Admin Login</h2>
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php } ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-danger w-100">Login</button>
            </form>
        </div>
    </div>
</body>
</html>