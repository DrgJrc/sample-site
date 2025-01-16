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
            font-size: 0.9rem;
        }
        .container {
            max-width: 90%;
            margin: 0 auto;
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

        <div class="table-responsive">
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
                        <th><input type="text" class="form-control search-input" data-column="0"></th>
                        <th><input type="text" class="form-control search-input" data-column="1"></th>
                        <th><input type="text" class="form-control search-input" data-column="2"></th>
                        <th><input type="text" class="form-control search-input" data-column="3"></th>
                        <th><input type="text" class="form-control search-input" data-column="4"></th>
                        <th><input type="text" class="form-control search-input" data-column="5"></th>
                        <th><input type="text" class="form-control search-input" data-column="6"></th>
                        <th><input type="text" class="form-control search-input" data-column="7"></th>
                        <th><input type="text" class="form-control search-input" data-column="8"></th>
                        <th><input type="text" class="form-control search-input" data-column="9"></th>
                        <th><input type="text" class="form-control search-input" data-column="10"></th>
                        <th><input type="text" class="form-control search-input" data-column="11"></th>
                        <th><input type="text" class="form-control search-input" data-column="12"></th>
                        <th></th>
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
                            <td><?php echo $row['rejection_reason'] ?: 'N/A'; ?></td>
                            <td>
                                <form method="POST" class="status-update-form">
                                    <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                                    <select name="status" class="form-select">
                                        <option value="Pending" <?php echo ($row['status'] == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="Approved" <?php echo ($row['status'] == 'Approved' ? 'selected' : ''); ?>>Approved</option>
                                        <option value="Rejected" <?php echo ($row['status'] == 'Rejected' ? 'selected' : ''); ?>>Rejected</option>
                                        <option value="Donated" <?php echo ($row['status'] == 'Donated' ? 'selected' : ''); ?>>Donated</option>
                                    </select>
                                    <textarea name="rejection_reason" class="form-control" rows="2" placeholder="Enter reason for rejection"><?php echo htmlspecialchars($row['rejection_reason']); ?></textarea>
                                    <button type="submit" name="update_status" class="btn btn-primary w-100">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInputs = document.querySelectorAll(".search-input");

            searchInputs.forEach(input => {
                input.addEventListener("keyup", function() {
                    const column = this.getAttribute("data-column");
                    const value = this.value.toLowerCase();
                    const rows = document.querySelectorAll("tbody tr");

                    rows.forEach(row => {
                        const cell = row.querySelectorAll("td")[column];
                        if (cell && cell.textContent.toLowerCase().includes(value)) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>