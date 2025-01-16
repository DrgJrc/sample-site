<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get blood group from the URL
$bloodGroup = isset($_GET['blood_group']) ? $conn->real_escape_string($_GET['blood_group']) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .back-button {
            display: block;
            margin: 20px auto;
        }
        .availability-yes {
            background-color: green;
            color: white;
            padding: 5px;
            border-radius: 5px;
            text-align: center;
        }
        .availability-no {
            background-color: red;
            color: white;
            padding: 5px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="header text-danger">Donor Details for Blood Group: <?php echo htmlspecialchars($bloodGroup); ?></h1>

        <?php
        if ($bloodGroup) {
            $query = "SELECT id, name, YEAR(CURDATE()) - YEAR(dob) AS age, address, blood_group, last_donation, 
                      availability, reason FROM users WHERE blood_group = '$bloodGroup'";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                echo '<form action="request_blood.php" method="POST">';
                echo '<table class="table table-bordered table-striped">';
                echo '<thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Address</th>
                            <th>Blood Group</th>
                            <th>Last Donation Date</th>
                            <th>Availability</th>
                            <th>Reason (if Unavailable)</th>
                            <th>Select</th>
                        </tr>
                      </thead><tbody>';
                while ($row = $result->fetch_assoc()) {
                    $availability = $row['availability'] == 1 
                        ? '<span class="availability-yes">Yes</span>' 
                        : '<span class="availability-no">No</span>';
                    $reason = $row['availability'] == 0 ? htmlspecialchars($row['reason']) : 'N/A';

                    // Only allow checkbox selection if the donor is available
                    $checkbox = $row['availability'] == 1 
                        ? "<input type='checkbox' name='donor_ids[]' value='{$row['id']}'>" 
                        : ''; // No checkbox if availability is No

                    echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['address']}</td>
                            <td>{$row['blood_group']}</td>
                            <td>{$row['last_donation']}</td>
                            <td>$availability</td>
                            <td>$reason</td>
                            <td>$checkbox</td>
                          </tr>";
                }
                echo '</tbody></table>';
                echo '<div class="text-center">';
                echo '<button type="submit" class="btn btn-danger">Request Blood for Selected Donors</button>';
                echo '</div>';
                echo '</form>';
            } else {
                echo '<div class="alert alert-warning text-center" role="alert">
                        No donors found for this blood group.
                      </div>';
            }
        } else {
            echo '<div class="alert alert-danger text-center" role="alert">
                    Invalid blood group!
                  </div>';
        }
        ?>

        <a href="index.php" class="btn btn-primary back-button">Back to Home</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>