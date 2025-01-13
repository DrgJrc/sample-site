<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Site</title>
    <!-- Link to the CSS file -->
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
            <p><?php echo $total_donors; ?></p>
        </div>
        <div class="stat-box">
            <h3>Available Donors</h3>
            <p><?php echo $available_donors; ?></p>
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