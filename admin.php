<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
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

// Build the query with search filters
$searchConditions = [];
if (!empty($_GET['req_id'])) {
    $searchConditions[] = "br.id LIKE '%" . $conn->real_escape_string($_GET['req_id']) . "%'";
}
if (!empty($_GET['requester_name'])) {
    $searchConditions[] = "br.requester_name LIKE '%" . $conn->real_escape_string($_GET['requester_name']) . "%'";
}
if (!empty($_GET['requester_mobile'])) {
    $searchConditions[] = "br.requester_mobile LIKE '%" . $conn->real_escape_string($_GET['requester_mobile']) . "%'";
}
if (!empty($_GET['requester_email'])) {
    $searchConditions[] = "br.requester_email LIKE '%" . $conn->real_escape_string($_GET['requester_email']) . "%'";
}
if (!empty($_GET['recipient_name'])) {
    $searchConditions[] = "br.recipient_name LIKE '%" . $conn->real_escape_string($_GET['recipient_name']) . "%'";
}
if (!empty($_GET['donor_name'])) {
    $searchConditions[] = "u.name LIKE '%" . $conn->real_escape_string($_GET['donor_name']) . "%'";
}
if (!empty($_GET['donor_mobile'])) {
    $searchConditions[] = "u.mobile LIKE '%" . $conn->real_escape_string($_GET['donor_mobile']) . "%'";
}
if (!empty($_GET['donor_email'])) {
    $searchConditions[] = "u.email LIKE '%" . $conn->real_escape_string($_GET['donor_email']) . "%'";
}
if (!empty($_GET['blood_group'])) {
    $searchConditions[] = "u.blood_group LIKE '%" . $conn->real_escape_string($_GET['blood_group']) . "%'";
}
if (!empty($_GET['hospital'])) {
    $searchConditions[] = "br.hospital LIKE '%" . $conn->real_escape_string($_GET['hospital']) . "%'";
}
if (!empty($_GET['message'])) {
    $searchConditions[] = "br.message LIKE '%" . $conn->real_escape_string($_GET['message']) . "%'";
}
if (!empty($_GET['status'])) {
    $searchConditions[] = "br.status LIKE '%" . $conn->real_escape_string($_GET['status']) . "%'";
}
if (!empty($_GET['rejection_reason'])) {
    $searchConditions[] = "br.rejection_reason LIKE '%" . $conn->real_escape_string($_GET['rejection_reason']) . "%'";
}

// Create the final query
$query = "SELECT br.id, br.requester_name, br.requester_mobile, br.requester_email, 
          br.recipient_name, u.name AS donor_name, u.mobile AS donor_mobile, u.email AS donor_email, 
          u.blood_group, br.hospital, br.message, br.status, br.rejection_reason 
          FROM blood_requests br 
          LEFT JOIN users u ON br.donor_id = u.id";

if (!empty($searchConditions)) {
    $query .= " WHERE " . implode(" AND ", $searchConditions);
}
$query .= " ORDER BY br.id DESC";

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
            font-size: 0.9rem;
        }
        .container {
            max-width: 90%;
            margin: 0 auto;
        }
        .table-responsive {
            overflow-x: auto;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .search-input {
            width: 100%;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Admin Panel</span>
            <a href="admin_logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container mt-3">
        <h1 class="text-center text-primary mb-4">Admin Panel</h1>

        <?php if (isset($message)) { ?>
            <div class="alert alert-success text-center">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <div class="table-responsive">
            <form method="GET" action="admin.php">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Req. ID</th>
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
                        <tr>
                            <!-- Search Inputs -->
                            <th><input type="text" name="req_id" class="form-control search-input" value="<?php echo $_GET['req_id'] ?? ''; ?>"></th>
                            <th><input type="text" name="requester_name" class="form-control search-input" value="<?php echo $_GET['requester_name'] ?? ''; ?>"></th>
                            <th><input type="text" name="requester_mobile" class="form-control search-input" value="<?php echo $_GET['requester_mobile'] ?? ''; ?>"></th>
                            <th><input type="text" name="requester_email" class="form-control search-input" value="<?php echo $_GET['requester_email'] ?? ''; ?>"></th>
                            <th><input type="text" name="recipient_name" class="form-control search-input" value="<?php echo $_GET['recipient_name'] ?? ''; ?>"></th>
                            <th><input type="text" name="donor_name" class="form-control search-input" value="<?php echo $_GET['donor_name'] ?? ''; ?>"></th>
                            <th><input type="text" name="donor_mobile" class="form-control search-input" value="<?php echo $_GET['donor_mobile'] ?? ''; ?>"></th>
                            <th><input type="text" name="donor_email" class="form-control search-input" value="<?php echo $_GET['donor_email'] ?? ''; ?>"></th>
                            <th><input type="text" name="blood_group" class="form-control search-input" value="<?php echo $_GET['blood_group'] ?? ''; ?>"></th>
                            <th><input type="text" name="hospital" class="form-control search-input" value="<?php echo $_GET['hospital'] ?? ''; ?>"></th>
                            <th><input type="text" name="message" class="form-control search-input" value="<?php echo $_GET['message'] ?? ''; ?>"></th>
                            <th><input type="text" name="status" class="form-control search-input" value="<?php echo $_GET['status'] ?? ''; ?>"></th>
                            <th><input type="text" name="rejection_reason" class="form-control search-input" value="<?php echo $_GET['rejection_reason'] ?? ''; ?>"></th>
                            <th><button type="submit" class="btn btn-primary w-100">Search</button></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
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
                                            <select name="status" class="form-select">
                                                <option value="Pending" <?php echo ($row['status'] == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                                <option value="Approved" <?php echo ($row['status'] == 'Approved' ? 'selected' : ''); ?>>Approved</option>
                                                <option value="Rejected" <?php echo ($row['status'] == 'Rejected' ? 'selected' : ''); ?>>Rejected</option>
                                                <option value="Donated" <?php echo ($row['status'] == 'Donated' ? 'selected' : ''); ?>>Donated</option>
                                            </select>
                                            <textarea name="rejection_reason" class="form-control" rows="2"><?php echo htmlspecialchars($row['rejection_reason']); ?></textarea>
                                            <button type="submit" name="update_status" class="btn btn-primary w-100">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr><td colspan="14" class="text-center">No data available</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
