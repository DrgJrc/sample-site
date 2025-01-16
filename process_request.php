<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donor_ids'])) {
    $donorIds = $_POST['donor_ids'];
    $requesterName = htmlspecialchars($_POST['requester_name']);
    $requesterMobile = htmlspecialchars($_POST['requester_mobile']);
    $requesterEmail = htmlspecialchars($_POST['requester_email'] ?? null);
    $recipientName = htmlspecialchars($_POST['recipient_name']);
    $recipientMobile = htmlspecialchars($_POST['recipient_mobile'] ?? null);
    $recipientEmail = htmlspecialchars($_POST['recipient_email'] ?? null);
    $hospital = htmlspecialchars($_POST['hospital']);
    $doctorName = htmlspecialchars($_POST['doctor_name']);
    $relation = htmlspecialchars($_POST['relation']);
    $message = htmlspecialchars($_POST['message'] ?? null);

    $insertedRequests = [];
    foreach ($donorIds as $donorId) {
        $query = "INSERT INTO blood_requests 
                  (donor_id, requester_name, requester_mobile, requester_email, recipient_name, recipient_mobile, 
                  recipient_email, hospital, doctor_name, relation, message)
                  VALUES ('$donorId', '$requesterName', '$requesterMobile', '$requesterEmail', '$recipientName', 
                          '$recipientMobile', '$recipientEmail', '$hospital', '$doctorName', '$relation', '$message')";
        if ($conn->query($query)) {
            $insertedRequests[] = $conn->insert_id;
        }
    }

    if (!empty($insertedRequests)) {
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .summary-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .table {
            margin-top: 20px;
        }
        .btn-home {
            margin-top: 20px;
            display: block;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="summary-card">
            <h2 class="text-center text-success">Blood Request Summary</h2>
            <p class="text-center">Your blood request(s) have been sent successfully!</p>
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Requester Name</th>
                        <th>Recipient Name</th>
                        <th>Hospital</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($insertedRequests as $id) {
            $result = $conn->query("SELECT requester_name, recipient_name, hospital, message FROM blood_requests WHERE id = $id");
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<tr>
                        <td>' . htmlspecialchars($row['requester_name']) . '</td>
                        <td>' . htmlspecialchars($row['recipient_name']) . '</td>
                        <td>' . htmlspecialchars($row['hospital']) . '</td>
                        <td>' . htmlspecialchars($row['message']) . '</td>
                      </tr>';
            }
        }
        echo '</tbody>
            </table>
            <a href="index.php" class="btn btn-primary btn-home">Back to Home</a>
        </div>
    </div>
</body>
</html>';
    } else {
        echo '<div class="alert alert-danger text-center">Error processing requests.</div>';
    }
}
$conn->close();
?>