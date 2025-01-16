<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'blood_donation');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donorId = intval($_POST['donor_id']);
    $donorQuery = "SELECT * FROM users WHERE id = $donorId";
    $donorResult = $conn->query($donorQuery);
    $donor = $donorResult->fetch_assoc();
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
        <h1 class="text-center text-danger">Request Blood</h1>
        <form action="send_request.php" method="POST">
            <div class="mb-3">
                <label for="recipient_name" class="form-label">Recipient Name</label>
                <input type="text" name="recipient_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="recipient_contact" class="form-label">Contact Information</label>
                <input type="text" name="recipient_contact" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="recipient_message" class="form-label">Message</label>
                <textarea name="recipient_message" class="form-control" rows="3" required></textarea>
            </div>
            <input type="hidden" name="donor_id" value="<?php echo $donor['id']; ?>">
            <input type="hidden" name="donor_email" value="<?php echo $donor['email']; ?>">
            <button type="submit" class="btn btn-danger">Send Request</button>
        </form>
    </div>
</body>
</html>