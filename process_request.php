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
        echo '<div class="container mt-5">';
        echo '<div class="alert alert-success text-center">Blood request(s) sent successfully!</div>';
        echo '<h4>Inserted Records</h4>';
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>Requester Name</th><th>Recipient Name</th><th>Hospital</th><th>Message</th></tr></thead>';
        echo '<tbody>';
        foreach ($insertedRequests as $id) {
            $result = $conn->query("SELECT requester_name, recipient_name, hospital, message FROM blood_requests WHERE id = $id");
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['requester_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['recipient_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['hospital']) . '</td>';
                echo '<td>' . htmlspecialchars($row['message']) . '</td>';
                echo '</tr>';
            }
        }
        echo '</tbody></table>';
        echo '<a href="index.php" class="btn btn-primary">Back to Home</a>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-danger text-center">Error processing requests.</div>';
    }
}
$conn->close();
?>