<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page if not logged in
    header('Location: admin_login.php');
    exit;
}
?>

<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to update status and rejection reason
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $requestId = htmlspecialchars($_POST['request_id']);
    $status = htmlspecialchars($_POST['status']);
    $rejectionReason = htmlspecialchars($_POST['rejection_reason'] ?? 'N/A');

    $query = "UPDATE blood_requests SET status = '$status', rejection_reason = '$rejectionReason' WHERE id = '$requestId'";
    if ($conn->query($query) === TRUE) {
        $message = "Request updated successfully!";
    } else {
        $message = "Error updating request: " . $conn->error;
    }
}

// Fetch all blood requests
$query = "SELECT br.id, br.requester_name, br.requester_mobile, br.requester_email, 
          br.recipient_name, u.name AS donor_name, u.mobile AS donor_mobile, u.email AS donor_email, 
          u.blood_group, br.hospital, br.message, br.status, br.rejection_reason 
          FROM blood_requests br 
          LEFT JOIN users u ON br.donor_id = u.id 
          ORDER BY br.id DESC";
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
            margin-top: 20px;
        }
        .table-wrapper {
            margin-top: 20px;
        }
        .status-update-form select, .status-update-form textarea {
            margin-bottom: 10px;
        }
        .status-update-form button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center text-primary">Admin Panel</h1>
        
        <?php if (isset($message)) { ?>
            <div class="alert alert-success text-center">
                <?php echo $message; ?>
            </div>
        <?php } ?>
        
        <div class="table-wrapper">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Request ID</th>
                        <th>Requester Name</th>
                        <th>Requester Mobile</th>
                        <th>Requester Email</th>
                        <th>Recipient Name</th>
                        <th>Donor Name</th>
                        <th>Donor Mobile</th>
                        <th>Donor Email</th>
                        <th>Blood Group</th>
                        <th>Hospital</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Reason for Rejection</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['requester_name']; ?></td>
                            <td><?php echo $row['requester_mobile']; ?></td>
                            <td><?php echo $row['requester_email'] ?: 'N/A'; ?></td>
                            <td><?php echo $row['recipient_name']; ?></td>
                            <td><?php echo $row['donor_name']; ?></td>
                            <td><?php echo $row['donor_mobile']; ?></td>
                            <td><?php echo $row['donor_email'] ?: 'N/A'; ?></td>
                            <td><?php echo $row['blood_group']; ?></td>
                            <td><?php echo $row['hospital']; ?></td>
                            <td><?php echo $row['message']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['rejection_reason']; ?></td>
                            <td>
                                <form method="POST" class="status-update-form">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                    
                                    <!-- Status Dropdown -->
                                    <select name="status" class="form-select">
                                        <option value="Pending" <?php echo ($row['status'] == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="Approved" <?php echo ($row['status'] == 'Approved' ? 'selected' : ''); ?>>Approved</option>
                                        <option value="Rejected" <?php echo ($row['status'] == 'Rejected' ? 'selected' : ''); ?>>Rejected</option>
                                    </select>

                                    <!-- Rejection Reason -->
                                    <textarea name="rejection_reason" class="form-control" rows="2" placeholder="Enter reason for rejection"><?php echo htmlspecialchars($row['rejection_reason']); ?></textarea>

                                    <!-- Update Button -->
                                    <button type="submit" name="update_status" class="btn btn-primary w-100">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>