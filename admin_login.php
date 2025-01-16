<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Fetch admin details
    $query = "SELECT * FROM admin_login WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Check if account is active
        if ($admin['status'] == 1) {
            // Verify password (assuming MD5 is used)
            if (md5($password) === $admin['password']) {
                // Set session variables for logged-in admin
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin['username'];

                // Redirect to admin panel
                header('Location: admin.php');
                exit;
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            $error_message = "Your account is inactive. Please contact the system administrator.";
        }
    } else {
        $error_message = "Invalid username.";
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
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">Admin Login</h2>
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php } ?>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </div>
        </form>
    </div>
</body>
</html>