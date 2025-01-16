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
        .search-input {
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <a href="admin_logout.php" class="btn btn-secondary">Logout</a>

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
                        <th>Action</th>
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
                                    <select name="status" class="form-select">
                                        <option value="Pending" <?php echo ($row['status'] == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="Approved" <?php echo ($row['status'] == 'Approved' ? 'selected' : ''); ?>>Approved</option>
                                        <option value="Rejected" <?php echo ($row['status'] == 'Rejected' ? 'selected' : ''); ?>>Rejected</option>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterTable(columnIndex) {
            const input = document.querySelectorAll('.search-input')[columnIndex];
            const filter = input.value.toLowerCase();
            const table = document.getElementById('adminTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 2; i < rows.length; i++) { // Skip first two rows (search and headers)
                const cells = rows[i].getElementsByTagName('td');
                if (cells[columnIndex]) {
                    const text = cells[columnIndex].innerText.toLowerCase();
                    rows[i].style.display = text.includes(filter) ? '' : 'none';
                }
            }
        }
    </script>
</body>
</html>