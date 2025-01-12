<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $last_donation = $_POST['last_donation'];

    $update_query = "UPDATE users SET last_donation = '$last_donation', availability = 0 WHERE id = $user_id";
    if ($conn->query($update_query) === TRUE) {
        echo "Your blood donation history has been updated.";
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
    <title>Update Blood Donation History</title>
</head>
<body>
    <h1>Update Blood Donation History</h1>

    <form method="POST" action="">
        <label>Last Donation Date: <input type="date" name="last_donation" required></label><br><br>
        <button type="submit">Update</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
