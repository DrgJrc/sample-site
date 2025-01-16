<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump($_POST['donor_ids']);
    exit; // Stop execution to verify output
}


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_request'])) {
    $donorIds = $_POST['donor_ids'] ?? []; // Array of selected donor IDs
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

    // Insert request for each selected donor
    foreach ($donorIds as $donorId) {
        $query = "INSERT INTO blood_requests (donor_id, requester_name, requester_mobile, requester_email, recipient_name, 
                  recipient_mobile, recipient_email, hospital, doctor_name, relation, message)
                  VALUES ('$donorId', '$requesterName', '$requesterMobile', '$requesterEmail', '$recipientName', 
                          '$recipientMobile', '$recipientEmail', '$hospital', '$doctorName', '$relation', '$message')";
        if (!$conn->query($query)) {
            echo '<div class="alert alert-danger text-center" role="alert">
                    Error: ' . $conn->error . '
                  </div>';
        }
    }

    echo '<div class="alert alert-success text-center" role="alert">
            Blood request(s) sent successfully!
          </div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Blood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .required {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center text-danger">Request Blood</h2>
            
            <?php if (!empty($donorIds)) { ?>
                <div class="alert alert-info">
                    <strong>Requesting Blood from Donors:</strong><br>
                    <?php
                    // Fetch and display donor names and blood groups
                    $ids = implode(',', array_map('intval', $donorIds));
                    $donorQuery = "SELECT name, blood_group FROM users WHERE id IN ($ids)";
                    $donorResult = $conn->query($donorQuery);
                    if ($donorResult && $donorResult->num_rows > 0) {
                        while ($donor = $donorResult->fetch_assoc()) {
                            echo "Donor Name: <b>{$donor['name']}</b>, Blood Group: <b>{$donor['blood_group']}</b><br>";
                        }
                    }
                    ?>
                </div>
            <?php } ?>

            <!-- Request Form -->
            <form action="" method="POST">
                <!-- Hidden Donor IDs -->
                <?php foreach ($donorIds as $donorId) { ?>
                    <input type="hidden" name="donor_ids[]" value="<?php echo htmlspecialchars($donorId); ?>">
                <?php } ?>

                <!-- Requester Details -->
                <h4>Requester Details</h4>
                <div class="mb-3">
                    <label for="requester_name" class="form-label">Requester Name <span class="required">*</span></label>
                    <input type="text" name="requester_name" id="requester_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="requester_mobile" class="form-label">Requester Mobile No. <span class="required">*</span></label>
                    <input type="tel" name="requester_mobile" id="requester_mobile" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="requester_email" class="form-label">Requester Email</label>
                    <input type="email" name="requester_email" id="requester_email" class="form-control">
                </div>

                <!-- Recipient Details -->
                <h4>Recipient Details</h4>
                <div class="mb-3">
                    <label for="recipient_name" class="form-label">Recipient Name <span class="required">*</span></label>
                    <input type="text" name="recipient_name" id="recipient_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="recipient_mobile" class="form-label">Recipient Mobile No.</label>
                    <input type="tel" name="recipient_mobile" id="recipient_mobile" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="recipient_email" class="form-label">Recipient Email</label>
                    <input type="email" name="recipient_email" id="recipient_email" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="hospital" class="form-label">Recipient Admitted Hospital <span class="required">*</span></label>
                    <input type="text" name="hospital" id="hospital" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="doctor_name" class="form-label">Doctor's Name <span class="required">*</span></label>
                    <input type="text" name="doctor_name" id="doctor_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="relation" class="form-label">Requester Relation with Recipient <span class="required">*</span></label>
                    <input type="text" name="relation" id="relation" class="form-control" required>
                </div>

                <!-- Message -->
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea name="message" id="message" rows="4" class="form-control" placeholder="Enter additional details (optional)"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" name="submit_request" class="btn btn-danger">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>