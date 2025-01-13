<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Site</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 10px;
            text-align: center;
            margin-top: 20px;
        }
        .stats-card {
            margin: 20px 0;
        }
        .table-section {
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <h1>Donate Blood, Save Life</h1>
        <p>A drop of blood can save a life!</p>
    </div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Blood Donation</a>
        <div class="navbar-nav">
            <a class="nav-link" href="login.php">Sign In</a>
            <a class="nav-link" href="register.php">Register as a Donor</a>
        </div>
    </nav>

    <!-- PHP Code to Fetch Statistics -->
    <?php
        // Database connection
        $conn = new mysqli('localhost', 'root', 'root', 'blood_donation');
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Total Registered Donors
        $total_donors_query = "SELECT COUNT(*) AS total FROM users";
        $total_donors_result = $conn->query($total_donors_query);
        $total_donors = $total_donors_result->fetch_assoc()['total'];

        // Available Donors (who haven't donated blood in the past 3 months)
        $available_donors_query = "SELECT COUNT(*) AS available FROM users WHERE last_donation <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
        $available_donors_result = $conn->query($available_donors_query);
        $available_donors = $available_donors_result->fetch_assoc()['available'];

        // Blood Group-wise Statistics
        $blood_group_query = "SELECT blood_group, 
                                     COUNT(*) AS total, 
                                     SUM(CASE WHEN last_donation <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) THEN 1 ELSE 0 END) AS available 
                              FROM users 
                              GROUP BY blood_group";
        $blood_group_result = $conn->query($blood_group_query);
    ?>

    <!-- Main Content -->
    <div class="container">
        <h2 class="my-4">Blood Donation Statistics</h2>
        <div class="row">
            <div class="col-md-6 stats-card">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h4 class="card-title">Total Registered Donors</h4>
                        <p class="card-text"><?php echo $total_donors; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 stats-card">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h4 class="card-title">Available Donors</h4>
                        <p class="card-text"><?php echo $available_donors; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blood Group-wise Statistics Table -->
        <div class="table-section">
            <h3>Blood Group-wise Statistics</h3>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Blood Group</th>
                        <th>Total Donors</th>
                        <th>Available Donors</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if ($blood_group_result->num_rows > 0) {
                            while ($row = $blood_group_result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row['blood_group'] . "</td>
                                        <td>" . $row['total'] . "</td>
                                        <td>" . $row['available'] . "</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No data available</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>&copy; 2025 Blood Donation. All rights reserved.</p>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
