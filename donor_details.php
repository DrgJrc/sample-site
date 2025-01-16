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
    <!-- Bootstrap CSS -->
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
        .search-input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="header text-danger">Donor Details for Blood Group: <?php echo htmlspecialchars($bloodGroup); ?></h1>

        <?php
        if ($bloodGroup) {
            $query = "SELECT name, YEAR(CURDATE()) - YEAR(dob) AS age, address, blood_group, last_donation, 
                      availability, reason FROM users WHERE blood_group = '$bloodGroup'";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                echo '<table class="table table-bordered table-striped" id="donorTable">';
                echo '<thead class="table-dark">';
                echo '<tr>
                        <th>
                            <input type="text" class="search-input" placeholder="Search Name" onkeyup="searchColumn(0)">
                        </th>
                        <th>
                            <input type="text" class="search-input" placeholder="Search Age" onkeyup="searchColumn(1)">
                        </th>
                        <th>
                            <input type="text" class="search-input" placeholder="Search Address" onkeyup="searchColumn(2)">
                        </th>
                        <th>
                            <input type="text" class="search-input" placeholder="Search Blood Group" onkeyup="searchColumn(3)">
                        </th>
                        <th>
                            <input type="text" class="search-input" placeholder="Search Last Donation" onkeyup="searchColumn(4)">
                        </th>
                        <th>
                            <input type="text" class="search-input" placeholder="Search Availability" onkeyup="searchColumn(5)">
                        </th>
                        <th>
                            <input type="text" class="search-input" placeholder="Search Reason" onkeyup="searchColumn(6)">
                        </th>
                      </tr>
                      <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Blood Group</th>
                        <th>Last Donation Date</th>
                        <th>Availability</th>
                        <th>Reason (if Unavailable)</th>
                      </tr>';
                echo '</thead><tbody>';
                while ($row = $result->fetch_assoc()) {
                    $availability = $row['availability'] == 1 ? 
                        '<span class="badge bg-success">Available</span>' : 
                        '<span class="badge bg-danger">Unavailable</span>';
                    $reason = $row['availability'] == 0 ? htmlspecialchars($row['reason']) : 'N/A';
                    echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['address']}</td>
                            <td>{$row['blood_group']}</td>
                            <td>{$row['last_donation']}</td>
                            <td>$availability</td>
                            <td>$reason</td>
                          </tr>";
                }
                echo '</tbody></table>';
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function searchColumn(index) {
            const input = document.getElementsByClassName('search-input')[index];
            const filter = input.value.toLowerCase();
            const table = document.getElementById('donorTable');
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 2; i < rows.length; i++) { // Skip first two rows (search inputs and headers)
                const cells = rows[i].getElementsByTagName('td');
                if (cells[index]) {
                    const text = cells[index].innerText.toLowerCase();
                    rows[i].style.display = text.includes(filter) ? '' : 'none';
                }
            }
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>