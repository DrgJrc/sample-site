<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "blood_donation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Profile</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, <?php echo $user['name']; ?></h2>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p><strong>Blood Group:</strong> <?php echo $user['blood_group']; ?></p>
        <p><strong>Last Donation Date:</strong> <?php echo $user['last_donation']; ?></p>

        <form action="update_availability.php" method="POST">
            <h3>Update Availability</h3>
            <div class="mb-3">
                <label for="availability" class="form-label">Are you available to donate blood?</label>
                <select class="form-select" id="availability" name="availability">
                    <option value="1" <?php if ($user['availability'] == 1) echo 'selected'; ?>>Yes</option>
                    <option value="0" <?php if ($user['availability'] == 0) echo 'selected'; ?>>No</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label">If not available, provide a reason</label>
                <input type="text" class="form-control" id="reason" name="reason" value="<?php echo $user['reason']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>

        <p class="mt-3"><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
