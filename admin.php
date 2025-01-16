<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page if not logged in
    header('Location: admin_login.php');
    exit;
}

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
            max-width: 90%;
            margin: 0 auto;
        }
        .table-responsive {
            overflow-x: auto;
        }
        table {
            table-layout: fixed;
            word-wrap: break-word;
        }
        th, td {
            text-align: left;
            vertical-align: middle;
        }
        .logout-btn {
            float: right;
            margin-right: 20px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<!-- Logout Button -->
<a href="admin_logout.php" class="btn btn-secondary logout-btn">Logout</a>

<div class="container mt-5">
    <h1 class="text-center text-primary mb-4">Admin Panel</h1>

    <!-- Success Message -->
    <?php if (isset($message)) { ?>
        <div class="alert alert-success text-center">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <!-- Table Wrapper for Scrollable Content -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
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
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td class="text-center"><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['requester_name']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($row['requester_mobile']); ?></td>
                        <td><?php echo htmlspecialchars($row['requester_email'] ?: 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($row['recipient_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($row['donor_mobile']); ?></td>
                        <td><?php echo htmlspecialchars($row['donor_email'] ?: 'N/A'); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($row['blood_group']); ?></td>
                        <td><?php echo htmlspecialchars($row['hospital']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['rejection_reason'] ?: 'N/A'); ?></td>
                        <td>
                            <form method="POST" class="d-flex flex-column gap-2">
                                <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">

                                <!-- Status Dropdown -->
                                <select name="status" class="form-select">
                                    <option value="Pending" <?php echo ($row['status'] == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="Approved" <?php echo ($row['status'] == 'Approved' ? 'selected' : ''); ?>>Approved</option>
                                    <option value="Rejected" <?php echo ($row['status'] == 'Rejected' ? 'selected' : ''); ?>>Rejected</option>
                                    <option value="Donated" <?php echo ($row['status'] == 'Donated' ? 'selected' : ''); ?>>Donated</option>
                                </select>

                                <!-- Remarks Input -->
                                <textarea name="rejection_reason" class="form-control" placeholder="Enter remarks" rows="2"><?php echo htmlspecialchars($row['rejection_reason']); ?></textarea>

                                <!-- Update Button -->
                                <button type="submit" name="update_status" class="btn btn-primary">Update</button>
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
