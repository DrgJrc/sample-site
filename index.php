<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "blood_donation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get total donors
$total_donors_query = "SELECT COUNT(*) AS total FROM users";
$total_donors_result = $conn->query($total_donors_query);
$total_donors = $total_donors_result->fetch_assoc()['total'];

// Get available donors (those who haven't donated blood in the past 3 months)
$available_donors_query = "SELECT COUNT(*) AS available FROM users WHERE last_donation <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
$available_donors_result = $conn->query($available_donors_query);
$available_donors = $available_donors_result->fetch_assoc()['available'];

// Get blood group-wise statistics
$blood_group_query = "SELECT blood_group, COUNT(*) AS total_donors, SUM(availability) AS available_donors FROM users GROUP BY blood_group";
$blood_group_result = $conn->query($blood_group_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Site</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Blood Donation</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register as a Donor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Sign In</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1>Donate Blood, Save Life</h1>
            <p class="lead">A drop of blood can save a life!</p>
        </div>

        <!-- Blood Donation Statistics -->
        <h2>Blood Donation Statistics</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Registered Donors</h5>
                        <p class="card-text"><?php echo $total_donors; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Available Donors</h5>
                        <p class="card-text"><?php echo $available_donors; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blood Group-wise Statistics -->
        <h2>Blood Group-wise Statistics</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Blood Group</th>
                    <th>Total Donors</th>
                    <th>Available Donors</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $blood_group_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['blood_group']; ?></td>
                        <td><?php echo $row['total_donors']; ?></td>
                        <td><?php echo $row['available_donors']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-4">
        <p>© 2025 Blood Donation. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>