<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Site</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center text-danger my-5">Donate Blood, Save Life</h1>
        <p class="text-center">A drop of blood can save a life!</p>

        <h2 class="text-center my-4">Blood Donation Statistics</h2>
        <?php
        // Database connection
        $conn = new mysqli('localhost', 'root', 'root', 'blood_donation');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get total registered donors
        $total_donors_query = "SELECT COUNT(*) AS total FROM users";
        $total_donors_result = $conn->query($total_donors_query);
        $total_donors = $total_donors_result->fetch_assoc()['total'];

        // Get available donors (those who haven't donated in the last 3 months)
        $available_donors_query = "SELECT COUNT(*) AS available FROM users WHERE last_donation <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
        $available_donors_result = $conn->query($available_donors_query);
        $available_donors = $available_donors_result->fetch_assoc()['available'];

        echo "
        <div class='row'>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-body text-center'>
                        <h3>Total Registered Donors</h3>
                        <p class='display-4'>$total_donors</p>
                    </div>
                </div>
            </div>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-body text-center'>
                        <h3>Available Donors</h3>
                        <p class='display-4'>$available_donors</p>
                    </div>
                </div>
            </div>
        </div>
        ";

        // Close the database connection
        $conn->close();
        ?>

        <div class="text-center mt-5">
            <a href="login.php" class="btn btn-primary">Sign In</a>
            <a href="register.php" class="btn btn-secondary">Register as a Donor</a>
        </div>
    </div>

    <footer class="text-center mt-5">
        <p>&copy; 2025 Blood Donation. All rights reserved.</p>
    </footer>

    <script src="javascript/bootstrap.bundle.min.js"></script>
</body>
</html>
