<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch donor details from POST
$donorId = htmlspecialchars($_POST['donor_id'] ?? '');

// Fetch donor details dynamically from the `users` table
$donorQuery = "SELECT name, blood_group FROM users WHERE id = '$donorId'";
$donorResult = $conn->query($donorQuery);
if ($donorResult && $donorResult->num_rows > 0) {
    $donor = $donorResult->fetch_assoc();
    $donorName = $donor['name'];
    $bloodGroup = $donor['blood_group'];
} else {
    die("Invalid donor ID.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_request'])) {
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

    // Insert into database
    $query = "INSERT INTO blood_requests (donor_id, requester_name, requester_mobile, requester_email, recipient_name, 
              recipient_mobile, recipient_email, hospital, doctor_name, relation, message)
              VALUES ('$donorId', '$requesterName', '$requesterMobile', '$requesterEmail', '$recipientName', 
                      '$recipientMobile', '$recipientEmail', '$hospital', '$doctorName', '$relation', '$message')";
    if ($conn->query($query) === TRUE) {
        $successMessage = "Blood request sent successfully!";
    } else {
        $errorMessage = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Blood</title>
    <!-- Bootstrap CSS -->
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
            <?php if (isset($successMessage)) { ?>
                <div class="alert alert-success text-center" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php } elseif (isset($errorMessage)) { ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php } ?>
            
            <!-- Display Donor Details -->
            <div class="alert alert-info">
                <strong>Requesting Blood from Donor:</strong><br>
                Donor Name: <b><?php echo $donorName; ?></b><br>
                Blood Group: <b><?php echo $bloodGroup; ?></b><br>
            </div>

            <!-- Request Form -->
            <form action="" method="POST">
                <!-- Hidden Donor Details -->
                <input type="hidden" name="donor_id" value="<?php echo $donorId; ?>">

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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>   
