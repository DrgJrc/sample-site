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
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #d9534f;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        nav {
            background-color: #333;
            padding: 10px 0;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .statistics, .blood-group-stats {
            margin: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .stat-box {
            display: inline-block;
            width: 45%;
            margin: 10px 2.5%;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .stat-box h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .stat-box p {
            font-size: 36px;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Donate Blood, Save Life</h1>
        <p>A drop of blood can save a life!</p>
    </header>

    <!-- Navigation Bar -->
    <nav>
        <a href="register.php">Register as a Donor</a>
        <a href="login.php">Sign In</a>
    </nav>

    <!-- Statistics Section -->
    <section class="statistics">
        <div class="stat-box">
            <h3>Total Registered Donors</h3>
            <p><?php echo $total_donors_result['total']; ?></p>
        </div>
        <div class="stat-box">
            <h3>Available Donors</h3>
            <p><?php echo $available_donors_result['available']; ?></p>
        </div>
    </section>

    <!-- Blood Group-wise Statistics Section -->
    <section class="blood-group-stats">
        <h3>Blood Group-wise Statistics</h3>
        <table>
            <thead>
                <tr>
                    <th>Blood Group</th>
                    <th>Total Donors</th>
                    <th>Available Donors</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $blood_group_stats_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['blood_group']; ?></td>
                        <td><?php echo $row['total']; ?></td>
                        <td><?php echo $row['available']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2025 Blood Donation. All rights reserved.</p>
    </footer>

<?php
$conn->close();
?>
</body>
</html>