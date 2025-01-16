<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get total donors
$total_donors_query = "SELECT COUNT(*) AS total FROM users";
$total_donors_result = $conn->query($total_donors_query)->fetch_assoc();

// Get available donors
$available_donors_query = "SELECT COUNT(*) AS available FROM users WHERE availability = 1";
$available_donors_result = $conn->query($available_donors_query)->fetch_assoc();

// Get blood group-wise statistics
$blood_group_stats_query = "SELECT blood_group, COUNT(*) AS total, SUM(availability) AS available FROM users GROUP BY blood_group";
$blood_group_stats_result = $conn->query($blood_group_stats_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Site</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        header {
            background-color: #dc3545;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .card {
            margin: 20px 0;
        }
        table {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<header>
    <h1>Donate Blood, Save Life</h1>
    <p>A drop of blood can save a life!</p>
</header>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Blood Donation</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register as a Donor</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Sign In</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="request_status.php">Request Status</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Admin Panel</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container my-5">
    <div class="row">
        <!-- Total Registered Donors -->
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Registered Donors</h5>
                    <p class="card-text display-4"><?php echo $total_donors_result['total']; ?></p>
                </div>
            </div>
        </div>
        <!-- Available Donors -->
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Available Donors</h5>
                    <p class="card-text display-4"><?php echo $available_donors_result['available']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Blood Group-wise Statistics -->
    <h3 class="mt-5">Blood Group-wise Statistics</h3>
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Blood Group</th>
            <th>Total Donors</th>
            <th>Available Donors</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $query = "SELECT blood_group, COUNT(*) as total, SUM(availability) as available FROM users GROUP BY blood_group";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bloodGroup = urlencode($row['blood_group']); // Encode for URL safety
                echo "<tr>
                        <td>{$row['blood_group']}</td>
                        <td>{$row['total']}</td>
                        <td>{$row['available']}</td>
                        <td><a href='donor_details.php?blood_group={$bloodGroup}' target='_blank' class='btn btn-primary'>View Details</a></td>
                    </tr>";
            }
        }
    ?>

    </tbody>
</table>

</div>

<!-- Footer -->
<footer class="text-center text-white bg-dark py-3">
    <p>&copy; 2025 Blood Donation. All rights reserved.</p>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
$conn->close();
?>

<script>
    function showDetails(bloodGroup) {
        fetch('get_donor_details.php?blood_group=' + bloodGroup)
            .then(response => response.json())
            .then(data => {
                let details = 'Donor Details for ' + bloodGroup + ':\n\n';
                data.forEach(donor => {
                    details += `Name: ${donor.name}\nAge: ${donor.age}\nAddress: ${donor.address}\nBlood Group: ${donor.blood_group}\nLast Donated: ${donor.last_donation}\n\n`;
                });
                alert(details);
            })
            .catch(error => {
                console.error('Error fetching donor details:', error);
            });
    }
</script>


</body>
</html>