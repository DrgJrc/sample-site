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
        th, td {
            vertical-align: middle;
            text-align: center;
        }
        th {
            white-space: nowrap;
        }
        .action-column {
            width: 200px;
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
    <h1 class="text-center text-primary mt-3">Admin Panel</h1>

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
                    <th class="action-column">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamic rows go here -->
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
                                
                                <select name="status" class="form-select">
                                    <option value="Pending" <?php echo ($row['status'] == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="Approved" <?php echo ($row['status'] == 'Approved' ? 'selected' : ''); ?>>Approved</option>
                                    <option value="Rejected" <?php echo ($row['status'] == 'Rejected' ? 'selected' : ''); ?>>Rejected</option>
                                    <option value="Donated" <?php echo ($row['status'] == 'Donated' ? 'selected' : ''); ?>>Donated</option>
                                </select>

                                <textarea name="rejection_reason" class="form-control mt-2" rows="2" placeholder="Enter reason for rejection"><?php echo htmlspecialchars($row['rejection_reason']); ?></textarea>

                                <button type="submit" name="update_status" class="btn btn-primary mt-2 w-100">Update</button>
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