<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$requesterMobile = '';
$requesterName = '';
$statusResult = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requesterMobile = htmlspecialchars($_POST['requester_mobile']);
    $requesterName = htmlspecialchars($_POST['requester_name']);

    // Fetch requests for the requester
    $query = "SELECT id, recipient_name, hospital, status, rejection_reason 
              FROM blood_requests 
              WHERE requester_mobile = '$requesterMobile' OR requester_name = '$requesterName'
              ORDER BY id DESC";
    $statusResult = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 800px;
        }
        .table-wrapper {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center text-primary">Check Request Status</h1>
        
        <!-- Search Form -->
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="requester_name" class="form-label">Requester Name</label>
                <input type="text" name="requester_name" id="requester_name" class="form-control" placeholder="Enter your name">
            </div>
            <div class="mb-3">
                <label for="requester_mobile" class="form-label">Requester Mobile No.</label>
                <input type="tel" name="requester_mobile" id="requester_mobile" class="form-control" placeholder="Enter your mobile number">
            </div>
            <button type="submit" class="btn btn-primary w-100">Check Status</button>
        </form>

        <!-- Results Table -->
        <?php if ($statusResult && $statusResult->num_rows > 0) { ?>
            <div class="table-wrapper">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Request ID</th>
                            <th>Recipient Name</th>
                            <th>Hospital</th>
                            <th>Status</th>
                            <th>Rejection Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $statusResult->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['recipient_name']; ?></td>
                                <td><?php echo $row['hospital']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td><?php echo $row['rejection_reason'] ?: 'N/A'; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
            <div class="alert alert-warning text-center">
                No requests found for the given details.
            </div>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>