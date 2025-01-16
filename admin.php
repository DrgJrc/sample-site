<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $requestId = intval($_POST['request_id']);
    $newStatus = $conn->real_escape_string($_POST['status']);
    $updateQuery = "UPDATE blood_requests SET status = '$newStatus' WHERE id = $requestId";

    if ($conn->query($updateQuery)) {
        $successMessage = "Request status updated successfully!";
    } else {
        $errorMessage = "Error updating status: " . $conn->error;
    }
}

// Fetch all blood requests
$query = "SELECT br.id, br.requester_name, br.recipient_name, br.hospital, br.message, br.status, 
                 u.name AS donor_name, u.blood_group 
          FROM blood_requests br 
          JOIN users u ON br.donor_id = u.id
          ORDER BY br.request_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-rejected {
            color: red;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center text-primary">Admin Panel</h1>
        <?php if (isset($successMessage)) { ?>
            <div class="alert alert-success text-center">
                <?php echo $successMessage; ?>
            </div>
        <?php } elseif (isset($errorMessage)) { ?>
            <div class="alert alert-danger text-center">
                <?php echo $errorMessage; ?>
            </div>
        <?php } ?>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Request ID</th>
                    <th>Requester Name</th>
                    <th>Recipient Name</th>
                    <th>Donor Name</th>
                    <th>Blood Group</th>
                    <th>Hospital</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $statusClass = strtolower($row['status']);
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['requester_name']}</td>
                                <td>{$row['recipient_name']}</td>
                                <td>{$row['donor_name']}</td>
                                <td>{$row['blood_group']}</td>
                                <td>{$row['hospital']}</td>
                                <td>{$row['message']}</td>
                                <td class='status-$statusClass'>{$row['status']}</td>
                                <td>
                                    <form method='POST'>
                                        <input type='hidden' name='request_id' value='{$row['id']}'>
                                        <select name='status' class='form-select'>
                                            <option value='Pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                            <option value='Approved' " . ($row['status'] == 'Approved' ? 'selected' : '') . ">Approved</option>
                                            <option value='Rejected' " . ($row['status'] == 'Rejected' ? 'selected' : '') . ">Rejected</option>
                                        </select>
                                        <button type='submit' name='update_status' class='btn btn-primary mt-2'>Update</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='text-center'>No requests found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
    </div>
</body>
</html>

<?php $conn->close(); ?>