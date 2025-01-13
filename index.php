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