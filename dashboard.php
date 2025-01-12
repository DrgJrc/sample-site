<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $availability = isset($_POST['availability']) ? 1 : 0;
    $reason = $_POST['reason'];

    $update_query = "UPDATE users SET availability = $availability, reason = '$reason' WHERE id = $user_id";
    if ($conn->query($update_query) === TRUE) {
        echo "Your status has been updated.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $user['name']; ?>!</h1>

    <p><strong>Blood Group:</strong> <?php echo $user['blood_group']; ?></p>
    <p><strong>Last Donation Date:</strong> <?php echo $user['last_donation']; ?></p>

    <h2>Update Availability</h2>
    <form method="POST" action="">
        <label>Available for Donation: <input type="checkbox" name="availability" <?php echo $user['availability'] ? 'checked' : ''; ?>></label><br><br>
        <label>Reason (if unavailable): <input type="text" name="reason" value="<?php echo $user['reason']; ?>"></label><br><br>
        <button type="submit">Update</button>
    </form>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
