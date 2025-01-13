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
</head>
<body>
    <header>
        <h1>Donate Blood, Save Life</h1>
        <p>A drop of blood can save a life!</p>
    </header>

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

    <footer>
        <p>&copy; 2025 Blood Donation. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>