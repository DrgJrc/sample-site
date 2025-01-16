<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if donor IDs are passed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donor_ids'])) {
    $donorIds = $_POST['donor_ids'];
} else {
    die('<div class="alert alert-danger text-center">No donors selected. Please go back and select donors.</div>');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Blood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-danger">Request Blood</h2>
        <form action="process_request.php" method="POST">
            <!-- Hidden Field for Donor IDs -->
            <?php foreach ($donorIds as $donorId): ?>
                <input type="hidden" name="donor_ids[]" value="<?php echo htmlspecialchars($donorId); ?>">
            <?php endforeach; ?>

            <!-- Requester Details -->
            <h4>Requester Details</h4>
            <div class="mb-3">
                <label for="requester_name" class="form-label">Requester Name <span class="text-danger">*</span></label>
                <input type="text" name="requester_name" id="requester_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="requester_mobile" class="form-label">Requester Mobile No. <span class="text-danger">*</span></label>
                <input type="tel" name="requester_mobile" id="requester_mobile" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="requester_email" class="form-label">Requester Email</label>
                <input type="email" name="requester_email" id="requester_email" class="form-control">
            </div>

            <!-- Recipient Details -->
            <h4>Recipient Details</h4>
            <div class="mb-3">
                <label for="recipient_name" class="form-label">Recipient Name <span class="text-danger">*</span></label>
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
                <label for="hospital" class="form-label">Recipient Admitted Hospital <span class="text-danger">*</span></label>
                <input type="text" name="hospital" id="hospital" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="doctor_name" class="form-label">Doctor's Name <span class="text-danger">*</span></label>
                <input type="text" name="doctor_name" id="doctor_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="relation" class="form-label">Requester Relation with Recipient <span class="text-danger">*</span></label>
                <input type="text" name="relation" id="relation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message (Optional)</label>
                <textarea name="message" id="message" rows="4" class="form-control"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-danger">Submit Request</button>
            </div>
        </form>
    </div>
</body>
</html>