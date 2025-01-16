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
            margin-top: 20px;
        }
        .search-input {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .table-wrapper {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white px-3" href="admin_logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1 class="text-center text-primary">Admin Panel</h1>
        
        <?php if (isset($message)) { ?>
            <div class="alert alert-success text-center">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <div class="table-wrapper">
            <table class="table table-bordered table-striped" id="adminTable">
                <thead class="table-dark">
                    <tr>
                        <th><input type="text" class="search-input" placeholder="Search Request ID" onkeyup="filterTable(0)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Requester Name" onkeyup="filterTable(1)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Requester Mobile" onkeyup="filterTable(2)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Requester Email" onkeyup="filterTable(3)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Recipient Name" onkeyup="filterTable(4)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Donor Name" onkeyup="filterTable(5)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Donor Mobile" onkeyup="filterTable(6)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Donor Email" onkeyup="filterTable(7)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Blood Group" onkeyup="filterTable(8)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Hospital" onkeyup="filterTable(9)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Message" onkeyup="filterTable(10)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Status" onkeyup="filterTable(11)"></th>
                        <th><input type="text" class="search-input" placeholder="Search Reason for Rejection" onkeyup="filterTable(12)"></th>
                        <th style="width: 150px;">Action</th>
                    </tr>
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
                        <th style="width: 150px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
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
                                        <textarea column="100" rows="2" name="rejection_reason" class="form-control" rows="2" placeholder="Enter reason for rejection"><?php echo htmlspecialchars($row['rejection_reason']); ?></textarea>
                                        <button type="submit" name="update_status" class="btn btn-primary w-100">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php }
                    } else {
                        echo "<tr><td colspan='14' class='text-center'>No data available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterTable(columnIndex) {
            const input = document.querySelectorAll(".search-input")[columnIndex];
            const filter = input.value.toLowerCase();
            const table = document.getElementById("adminTable");
            const rows = table.getElementsByTagName("tr");

            for (let i = 2; i < rows.length; i++) { // Skip search row and headers
                const cells = rows[i].getElementsByTagName("td");
                if (cells[columnIndex]) {
                    const cellText = cells[columnIndex].textContent || cells[columnIndex].innerText;
                    rows[i].style.display = cellText.toLowerCase().includes(filter) ? "" : "none";
                }
            }
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>