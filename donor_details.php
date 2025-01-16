<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['blood_group'])) {
    $bloodGroup = $conn->real_escape_string($_GET['blood_group']);

    echo "<div class='container my-5'>";
    echo "<h1 class='text-center'>Donor Details for Blood Group: $bloodGroup</h1>";

    $query = "SELECT name, YEAR(CURDATE()) - YEAR(dob) AS age, address, blood_group, last_donation, availability, reason FROM users WHERE blood_group = '$bloodGroup'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Blood Group</th>
                        <th>Last Donation Date</th>
                        <th>Availability</th>
                        <th>Reason (if Unavailable)</th>
                    </tr>
                </thead>
                <tbody>";

        while ($row = $result->fetch_assoc()) {
            $availability = $row['availability'] == 1 ? "Available" : "Unavailable";
            $reason = $row['availability'] == 0 ? $row['reason'] : "N/A";

            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['age']}</td>
                    <td>{$row['address']}</td>
                    <td>{$row['blood_group']}</td>
                    <td>{$row['last_donation']}</td>
                    <td>{$availability}</td>
                    <td>{$reason}</td>
                  </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p class='text-center text-danger'>No donors found for this blood group.</p>";
    }

    echo "</div>";
} else {
    echo "<p class='text-center text-danger'>Blood group not specified.</p>";
}

$conn->close();
?>